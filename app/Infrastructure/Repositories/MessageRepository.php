<?php
namespace MaxHumme\ChatApi\Infrastructure\Repositories;

use DateTime;
use InvalidArgumentException;
use MaxHumme\ChatApi\Domain\Contracts\Entities\Message as MessageContract;
use MaxHumme\ChatApi\Domain\Contracts\Entities\Person as PersonContract;
use MaxHumme\ChatApi\Domain\Contracts\Factories\MessageFactory as MessageFactoryContract;
use MaxHumme\ChatApi\Domain\Contracts\Factories\ValueObjectFactory as ValueObjectFactoryContract;
use MaxHumme\ChatApi\Domain\Contracts\Repositories\MessageRepository  as MessageRepositoryContract;
use MaxHumme\ChatApi\Domain\Contracts\Repositories\PersonRepository as PersonRepositoryContract;
use MaxHumme\ChatApi\Domain\Entities\AbstractEntity;
use MaxHumme\ChatApi\Infrastructure\Contracts\Factories\CriteriaFactory as CriteriaFactoryContract;
use MaxHumme\ChatApi\Infrastructure\Contracts\Orm\Message as MessageOrmContract;
use MaxHumme\ChatApi\Infrastructure\Orm\Message as MessageOrm;

/**
 * Class MessageRepository
 *
 * @author Max Humme <max@humme.nl>
 */
final class MessageRepository extends AbstractEntityRepository implements MessageRepositoryContract
{
    /**
     * @var \MaxHumme\ChatApi\Domain\Contracts\Factories\MessageFactory
     */
    private $messageFactory;

    /**
     * @var \MaxHumme\ChatApi\Domain\Contracts\Repositories\PersonRepository
     */
    private $people;

    /**
     * @var \MaxHumme\ChatApi\Domain\Contracts\Factories\ValueObjectFactory
     */
    private $valueObjectFactory;

    /**
     * MessageRepository constructor.
     *
     * @param \MaxHumme\ChatApi\Infrastructure\Contracts\Factories\CriteriaFactory $criteriaFactory
     * @param \MaxHumme\ChatApi\Domain\Contracts\Factories\MessageFactory $messageFactory
     * @param \MaxHumme\ChatApi\Domain\Contracts\Repositories\PersonRepository $personRepository
     * @param \MaxHumme\ChatApi\Domain\Contracts\Factories\ValueObjectFactory $valueObjectFactory
     */
    public function __construct(
        CriteriaFactoryContract $criteriaFactory,
        MessageFactoryContract $messageFactory,
        PersonRepositoryContract $personRepository,
        ValueObjectFactoryContract $valueObjectFactory
    ) {
        parent::__construct($criteriaFactory);

        $this->messageFactory = $messageFactory;
        $this->people = $personRepository;
        $this->valueObjectFactory = $valueObjectFactory;
    }

    /** @inheritdoc */
    public function forRecipient(PersonContract $recipient, int $offset = 0, int $limit = 20)
    {
        // Try to grab the Messages we need from memory.
        // First create the test for the Criteria object. We will feed that to the matching method
        // (provided by the AbstractEntityRepository) to get our messages.
        $test = function (MessageContract $message) use ($recipient, $offset, $limit) {
            $startIndex = $offset + 1;
            $lastIndex = $startIndex + $limit;
            return
                $message->recipient()->id() === $recipient->id()
                && $startIndex <= $message->index() && $message->index() <= $lastIndex;
        };

        $criteria = $this->criteriaFactory->create($test);

        // Try to fetch the Messages based on the Criteria we just created.
        // We might be lucky and have found all messages from memory. If so sort them by index and return them.
        $messages = $this->matching($criteria);
        $messages = $this->sort($messages);
        if (count($messages) === $limit) {
            return $messages;
        }

        // When we're here, not all messages were found in our loaded entities.
        // So wee need to query the data store for them.
        $messageOrmObjects =
            MessageOrm
                ::toUsername($recipient->username())
                ->with('fromUser')
                ->orderBy('id')
                ->offset($offset)
                ->limit($limit)
                ->get();

        // No results from our data store? Return the $messages we have (which will be none if we think about it).
        if (count($messageOrmObjects) === 0) {
            return $messages;
        }

        // Being here means we have some messages returned from the data store.
        // We need to check which ones we have loaded and which ones to reconstitute.
        // To do that, we keep the ORM objects we don't have in our loaded $messages, so we can reconstitute them later.
        foreach ($messages as $message) {
            foreach ($messageOrmObjects as $key => $messageOrmObject) {
                if ($message->index() === $offset + 1 + $key) {
                    unset($messageOrmObjects[$key]);
                    break;
                }
            }
        }

        // Return the messages we have loaded if we have no ORM objects left.
        // It means we have all messages we need already (and that we reached the last message, because we
        // didn't hit the $limit, and because of that we didn't return them in the first place).
        if (count($messageOrmObjects) === 0) {
            return $messages;
        }

        // Reconstitute the messages from the ORM objects we have.
        foreach ($messageOrmObjects as $key => $messageOrmObject) {
            $messageBody = $this->valueObjectFactory->createMessageBody($messageOrmObject->body);
            $username = $this->valueObjectFactory->createUsername($messageOrmObject->fromUser->username);
            $sender = $this->people->personWithUsername($username);
            $messageId = $this->valueObjectFactory->createId($offset + 1 + $key);
            $index = $this->valueObjectFactory->createIndex($offset + 1 + $key);
            $createdAt = new DateTime($messageOrmObject->created_at);

            // Bring the Recipient back to memory from our data store.
            $message = $this->messageFactory->reconstitute(
                $messageId,
                $index,
                $messageBody,
                $sender,
                $recipient,
                $createdAt
            );

            // Load the Message in the repo, so we don't have to query our data store for it
            // should we need it again in this request.
            $this->load($message, [$messageOrmObject]);

            // Add the message to our result
            array_push($messages, $message);
        }

        $this->sort($messages);

        return $messages;
    }

    /** {@inheritdoc} */
    protected function createOrmObjectsFor(AbstractEntity $message)
    {
        if (!$message instanceof MessageContract) {
            throw new InvalidArgumentException('Expected Message object. Got '.get_class($message).'.');
        }

        // Grab what we need from Sender and Recipient to create the message ORM object.
        $senderOrmObjects = $this->people->ormObjectsFor($message->sender());
        $recipientOrmObjects = $this->people->ormObjectsFor($message->recipient());

        // Create and save the ORM object.
        $messageOrmObject = new MessageOrm;
        $messageOrmObject->from_user_id = $senderOrmObjects[0]->id;
        $messageOrmObject->to_user_id = $recipientOrmObjects[0]->id;
        $messageOrmObject->body = $message->body();
        $messageOrmObject->save();

        // Now the ORM object is saved, we can figure out which index this message has and add it to the entity.
        $this->updateNewlyPersistedMessage($message, $messageOrmObject);

        return [$messageOrmObject];
    }

    /**
     * Sorts the given messages by index.
     *
     * @param \MaxHumme\ChatApi\Domain\Contracts\Entities\Message[] $messages
     * @return \MaxHumme\ChatApi\Domain\Contracts\Entities\Message[]
     */
    private function sort(array $messages)
    {
        return array_sort($messages, function ($message) {
            return $message->index();
        });
    }

    /**
     * Updates the message with data we have now it is stored in persistence.
     *
     * @param \MaxHumme\ChatApi\Domain\Contracts\Entities\Message $message
     * @param \MaxHumme\ChatApi\Infrastructure\Contracts\Orm\Message $messageOrmObject
     */
    private function updateNewlyPersistedMessage(MessageContract $message, MessageOrmContract $messageOrmObject)
    {
        $id = $this->valueObjectFactory->createId(MessageOrm::toUsername($message->recipient()->username())->count());
        $index = $this->valueObjectFactory->createIndex($id->id());
        $message->setId($id);
        $message->setIndex($index);
        $message->setSentAt(new DateTime($messageOrmObject->created_at));
    }
}
