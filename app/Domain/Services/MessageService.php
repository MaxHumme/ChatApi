<?php
namespace MaxHumme\ChatApi\Domain\Services;

use MaxHumme\ChatApi\Domain\Contracts\Factories\MessageFactory as MessageFactoryContract;
use MaxHumme\ChatApi\Domain\Contracts\Factories\ValueObjectFactory as ValueObjectFactoryContract;
use MaxHumme\ChatApi\Domain\Contracts\Repositories\MessageRepository as MessageRepositoryContract;
use MaxHumme\ChatApi\Domain\Contracts\Repositories\PersonRepository as PersonRepositoryContract;
use MaxHumme\ChatApi\Domain\Contracts\Services\MessageService as MessageServiceContract;
use MaxHumme\ChatApi\Domain\Exceptions\NotFoundDomainException;

/**
 * Class MessageService
 *
 * @author Max Humme <max@humme.nl>
 */
final class MessageService implements MessageServiceContract
{
    /**
     * The default offset to use when fetching messages.
     *
     * @const int
     */
    const DEFAULT_OFFSET = 0;

    /**
     * The default limit to use when fetching messages.
     *
     * @const int
     */
    const DEFAULT_LIMIT = 20;

    /**
     * The maximum limit of messages to fetch.
     *
     * @const int
     */
    const MAX_LIMIT = 50;

    /**
     * @var \MaxHumme\ChatApi\Domain\Contracts\Factories\MessageFactory
     */
    private $messageFactory;

    /**
     * @var \MaxHumme\ChatApi\Domain\Contracts\Repositories\MessageRepository
     */
    private $messages;

    /**
     * @var \MaxHumme\ChatApi\Domain\Contracts\Repositories\PersonRepository
     */
    private $people;

    /**
     * @var \MaxHumme\ChatApi\Domain\Contracts\Factories\ValueObjectFactory
     */
    private $valueObjectFactory;

    /**
     * FetchMessagesService constructor.
     *
     * @param \MaxHumme\ChatApi\Domain\Contracts\Factories\MessageFactory $messageFactory
     * @param \MaxHumme\ChatApi\Domain\Contracts\Repositories\MessageRepository $messageRepository
     * @param \MaxHumme\ChatApi\Domain\Contracts\Repositories\PersonRepository $personRepository
     * @param \MaxHumme\ChatApi\Domain\Contracts\Factories\ValueObjectFactory $valueObjectFactory
     */
    public function __construct(
        MessageFactoryContract $messageFactory,
        MessageRepositoryContract $messageRepository,
        PersonRepositoryContract $personRepository,
        ValueObjectFactoryContract $valueObjectFactory
    ) {
        $this->messageFactory = $messageFactory;
        $this->messages = $messageRepository;
        $this->people = $personRepository;
        $this->valueObjectFactory = $valueObjectFactory;
    }

    /** @inheritdoc */
    public function fetchMessagesFor(string $username, $offset = null, $limit = null)
    {
        $usernameObject = $this->valueObjectFactory->createUsername($username);
        $recipient = $this->people->personWithUsername($usernameObject);
        if (is_null($recipient)) {
            throw new NotFoundDomainException('Recipient not found.');
        }

        $offset = $this->sanitizeOffset($offset);
        $limit = $this->sanitizeLimit($limit);

        return $this->messages->forRecipient($recipient, $offset, $limit);
    }

    /** @inheritdoc */
    public function sendMessage(string $message, string $fromUsername, string $toUsername)
    {
        $fromUsernameObject = $this->valueObjectFactory->createUsername($fromUsername);
        $sender = $this->people->personWithUsername($fromUsernameObject);
        if (is_null($sender)) {
            throw new NotFoundDomainException('Sender not found.');
        }

        $toUsernameObject = $this->valueObjectFactory->createUsername($toUsername);
        $recipient = $this->people->personWithUsername($toUsernameObject);
        if (is_null($recipient)) {
            throw new NotFoundDomainException('Recipient not found.');
        }

        $messageBody = $this->valueObjectFactory->createMessageBody($message);
        $messageEntity = $this->messageFactory->create($messageBody, $sender, $recipient);
        $this->messages->add($messageEntity);

        return $messageEntity;
    }

    /**
     * Sanitizes $offset.
     *
     * @param mixed $offset
     * @return int
     */
    private function sanitizeOffset($offset)
    {
        // If we have a numeric value, but it is no int, cast it to int.
        if (is_numeric($offset) && !is_integer($offset)) {
            $offset = (int) $offset;
        }

        // Make sure we have don't have null,
        // and that we have a numeric value,
        // and that $offset is not negative
        if (is_null($offset) ||
            !is_numeric($offset)
            || $offset < 0
        ) {
            $offset = self::DEFAULT_OFFSET;
        }

        return $offset;
    }

    /**
     * Sanitizes $limit.
     *
     * @param mixed $limit
     * @return int
     */
    private function sanitizeLimit($limit)
    {
        // If we have a numeric value, but it is no int, cast it to int.
        if (is_numeric($limit) && !is_integer($limit)) {
            $limit = (int) $limit;
        }

        // Make sure we have don't have null,
        // and that we have a numeric value,
        // and that $limit is not negative
        // and that we don't fetch to much messages.
        if (is_null($limit) ||
            !is_numeric($limit)
            || $limit < 0
        ) {
            $limit = self::DEFAULT_LIMIT;
        } elseif (self::MAX_LIMIT < $limit) {
            $limit = self::MAX_LIMIT;
        }

        return $limit;
    }
}
