<?php
namespace MaxHumme\ChatApi\Domain\Contracts\Services;

/**
 * Interface MessageService
 *
 * @author Max Humme <max@humme.nl>
 */
interface MessageService
{
    /**
     * Fetches the messages for the recipient with $personId.
     *
     * @param string $username
     * @param mixed $offset Default is 0
     * @param mixed $limit Default is 20, max is 50
     * @throws \MaxHumme\ChatApi\Domain\Exceptions\NotFoundDomainException when the recipient is not found.
     * @return \MaxHumme\ChatApi\Domain\Contracts\Entities\Message[]
     */
    public function fetchMessagesFor(string $username, $offset = null, $limit = null);

    /**
     * Sends the $message $fromPersonId $toPersonId.
     *
     * @param string $message
     * @param string $fromUsername
     * @param string $toUsername
     * @throws \MaxHumme\ChatApi\Domain\Exceptions\NotFoundDomainException when the sender is not found.
     * @throws \MaxHumme\ChatApi\Domain\Exceptions\NotFoundDomainException when the recipient is not found.
     * @return \MaxHumme\ChatApi\Domain\Contracts\Entities\Message
     */
    public function sendMessage(string $message, string $fromUsername, string $toUsername);
}
