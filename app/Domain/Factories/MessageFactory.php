<?php
namespace MaxHumme\ChatApi\Domain\Factories;

use DateTime;
use MaxHumme\ChatApi\Domain\Contracts\Entities\Person as PersonContract;
use MaxHumme\ChatApi\Domain\Contracts\Factories\MessageFactory as MessageFactoryContract;
use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesId as ProvidesIdContract;
use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesIndex as ProvidesIndexContract;
use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesMessageBody as ProvidesMessageBodyContract;
use MaxHumme\ChatApi\Domain\Entities\Message;

/**
 * Class MessageFactory
 *
 * Responsible for creating and reconstituting Message entities.
 *
 * @author Max Humme <max@humme.nl>
 */
final class MessageFactory implements MessageFactoryContract
{
    /** @inheritdoc */
    public function create(ProvidesMessageBodyContract $messageBody, PersonContract $sender, PersonContract $recipient)
    {
        return new Message($messageBody, $sender, $recipient);
    }

    /** @inheritdoc */
    public function reconstitute(
        ProvidesIdContract $id,
        ProvidesIndexContract $index,
        ProvidesMessageBodyContract $messageBody,
        PersonContract $sender,
        PersonContract $recipient,
        DateTime $sentAt
    ) {
        $message = new Message($messageBody, $sender, $recipient);
        $message->setId($id);
        $message->setIndex($index);
        $message->setSentAt($sentAt);

        return $message;
    }
}
