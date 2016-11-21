<?php
namespace MaxHumme\ChatApi\Domain\ValueObjects;

use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesMessageBody as ProvidesMessageBodyContract;
use MaxHumme\ChatApi\Domain\Exceptions\NotAllowedDomainException;

/**
 * Class MessageBody.
 *
 * The message body value object.
 *
 * @author Max Humme <max@humme.nl>
 */
final class MessageBody extends AbstractValueObject implements ProvidesMessageBodyContract
{
    /**
     * The maximum string length of a message body.
     *
     * @const int
     */
    const MAX_LENGTH = 1000;

    /**
     * @var string
     */
    private $messageBody;

    /**
     * MessageBody constructor.
     *
     * @param string $message
     */
    public function __construct(string $message)
    {
        // validate input
        $this->validate($message);

        $this->messageBody = $message;
    }

    /** @inheritdoc */
    public function messageBody()
    {
        return $this->messageBody;
    }

    /** @inheritdoc */
    public function equals(AbstractValueObject $valueObject)
    {
        return
            $valueObject instanceof ProvidesMessageBodyContract
            && $valueObject->messageBody() === $this->messageBody;
    }

    /**
     * Validates the message.
     *
     * @param string $message
     * @throws \MaxHumme\ChatApi\Domain\Exceptions\DomainException when $message is empty.
     * @throws \MaxHumme\ChatApi\Domain\Exceptions\DomainException when $message is too long.
     */
    private function validate(string $message)
    {
        if (empty($message)) {
            throw new NotAllowedDomainException('Empty message body not allowed.');
        }

        if (self::MAX_LENGTH < strlen($message)) {
            throw new NotAllowedDomainException('Message cannot exceed '.self::MAX_LENGTH.' characters.');
        }
    }
}
