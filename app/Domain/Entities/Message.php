<?php
namespace MaxHumme\ChatApi\Domain\Entities;

use DateTime;
use MaxHumme\ChatApi\Domain\Contracts\Entities\Message as MessageContract;
use MaxHumme\ChatApi\Domain\Contracts\Entities\Person as PersonContract;
use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesIndex as ProvidesIndexContract;
use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesMessageBody as ProvidesMessageBodyContract;
use MaxHumme\ChatApi\Domain\Exceptions\NotAllowedDomainException;
use RuntimeException;

/**
 * Class Message
 *
 * The Message entity.
 *
 * @author Max Humme <max@humme.nl>
 */
final class Message extends AbstractEntity implements MessageContract
{
    /**
     * @var \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesIndex
     */
    private $index;

    /**
     * @var \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesMessageBody
     */
    private $messageBody;

    /**
     * @var \MaxHumme\ChatApi\Domain\Contracts\Entities\Person
     */
    private $recipient;

    /**
     * @var \MaxHumme\ChatApi\Domain\Contracts\Entities\Person
     */
    private $sender;

    /**
     * @var \DateTime
     */
    private $sentAt;

    /**
     * Message constructor.
     *
     * @param \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesMessageBody $messageBody
     * @param \MaxHumme\ChatApi\Domain\Contracts\Entities\Person $sender
     * @param \MaxHumme\ChatApi\Domain\Contracts\Entities\Person $recipient
     */
    public function __construct(
        ProvidesMessageBodyContract $messageBody,
        PersonContract $sender,
        PersonContract $recipient
    ) {
        // sending a message to yourself is not allowed
        $this->validateSenderIsNotRecipient($sender, $recipient);

        $this->messageBody = $messageBody;
        $this->sender = $sender;
        $this->recipient = $recipient;
    }

    /** @inheritdoc */
    public function body()
    {
        return $this->messageBody->messageBody();
    }

    /** @inheritdoc */
    public function index()
    {
        return $this->index->index();
    }

    /** @inheritdoc */
    public function recipient()
    {
        return $this->recipient;
    }

    /** @inheritdoc */
    public function sender()
    {
        return $this->sender;
    }

    /** @inheritdoc */
    public function sentAt()
    {
        if (is_null($this->sentAt)) {
            throw new RuntimeException('Attribute $sentAt not set yet. Set it when adding it to the repository');
        }

        return $this->sentAt->format('Y-m-d H:i:s');
    }

    /** @inheritdoc */
    public function setIndex(ProvidesIndexContract $index)
    {
        $this->index = $index;
    }

    /** @inheritdoc */
    public function setSentAt(DateTime $sentAt)
    {
        $this->sentAt = $sentAt;
    }

    /**
     * Checks if the $sender is not also the $recipient.
     *
     * Sending messages to yourself is not allowed.
     *
     * @param \MaxHumme\ChatApi\Domain\Contracts\Entities\Person $sender
     * @param \MaxHumme\ChatApi\Domain\Contracts\Entities\Person $recipient
     * @throws \MaxHumme\ChatApi\Domain\Exceptions\NotAllowedDomainException when the $sender is also the $recipient
     */
    private function validateSenderIsNotRecipient(PersonContract $sender, PersonContract $recipient)
    {
        if ($sender->id() === $recipient->id()) {
            throw new NotAllowedDomainException('You may not send a message to yourself.');
        }
    }
}
