<?php
namespace MaxHumme\ChatApi\Domain\Contracts\Factories;

use DateTime;
use MaxHumme\ChatApi\Domain\Contracts\Entities\Person as PersonContract;
use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesId as ProvidesIdContract;
use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesIndex as ProvidesIndexContract;
use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesMessageBody as ProvidesMessageBodyContract;

/**
 * Interface MessageFactory
 *
 * Use it for classes responsible for creating and reconstituting Message entities.
 *
 * @author Max Humme <max@humme.nl>
 */
interface MessageFactory
{
    /**
     * Creates a Message.
     *
     * @param \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesMessageBody $messageBody
     * @param \MaxHumme\ChatApi\Domain\Contracts\Entities\Person $sender
     * @param \MaxHumme\ChatApi\Domain\Contracts\Entities\Person $recipient
     * @return \MaxHumme\ChatApi\Domain\Contracts\Entities\Message
     */
    public function create(ProvidesMessageBodyContract $messageBody, PersonContract $sender, PersonContract $recipient);

    /**
     * Reconstitutes a Message back to memory from the data store.
     *
     * @param \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesId $id
     * @param \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesIndex $index
     * @param \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesMessageBody $messageBody
     * @param \MaxHumme\ChatApi\Domain\Contracts\Entities\Person $sender
     * @param \MaxHumme\ChatApi\Domain\Contracts\Entities\Person $recipient
     * @param \DateTime $sentAt
     * @return \MaxHumme\ChatApi\Domain\Contracts\Entities\Message
     */
    public function reconstitute(
        ProvidesIdContract $id,
        ProvidesIndexContract $index,
        ProvidesMessageBodyContract $messageBody,
        PersonContract $sender,
        PersonContract $recipient,
        DateTime $sentAt
    );
}
