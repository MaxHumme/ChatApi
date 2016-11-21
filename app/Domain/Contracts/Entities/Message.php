<?php
namespace MaxHumme\ChatApi\Domain\Contracts\Entities;

use DateTime;
use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesIndex as ProvidesIndexContract;

/**
 * Interface Message
 *
 * @author Max Humme <max@humme.nl>
 */
interface Message
{
    /**
     * Returns the message.
     *
     * @return string
     */
    public function body();

    /**
     * Returns the index.
     *
     * @return int
     */
    public function index();

    /**
     * Returns the Recipient.
     *
     * @return \MaxHumme\ChatApi\Domain\Contracts\Entities\Person
     */
    public function recipient();

    /**
     * Returns the Sender.
     *
     * @return \MaxHumme\ChatApi\Domain\Contracts\Entities\Person
     */
    public function sender();

    /**
     * Returns the time the message was sent.
     *
     * @throws \RuntimeException when $sentAt is not yet set.
     * @return \DateTime
     */
    public function sentAt();

    /**
     * Sets the index of the message.
     *
     * @param \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesIndex $index
     */
    public function setIndex(ProvidesIndexContract $index);

    /**
     * Sets the time the message was sent.
     *
     * @param \DateTime $sentAt
     */
    public function setSentAt(DateTime $sentAt);
}
