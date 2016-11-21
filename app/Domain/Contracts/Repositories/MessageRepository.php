<?php
namespace MaxHumme\ChatApi\Domain\Contracts\Repositories;

use MaxHumme\ChatApi\Domain\Contracts\Entities\Person as PersonContract;

/**
 * Interface MessageRepository
 *
 * @author Max Humme <max@humme.nl>
 */
interface MessageRepository
{
    /**
     * Returns the asked for messages for the given $recipient.
     *
     * @param \MaxHumme\ChatApi\Domain\Contracts\Entities\Person $recipient
     * @param int $offset
     * @param int $limit
     * @return \MaxHumme\ChatApi\Domain\Contracts\Entities\Message[]
     */
    public function forRecipient(PersonContract $recipient, int $offset = 0, int $limit = 20);
}
