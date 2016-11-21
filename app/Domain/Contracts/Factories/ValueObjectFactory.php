<?php
namespace MaxHumme\ChatApi\Domain\Contracts\Factories;

/**
 * Interface ValueObjectFactory
 *
 * @author Max Humme <max@humme.nl>
 */
interface ValueObjectFactory
{
    /**
     * Creates the Id value object.
     *
     * @param string $id
     * @return \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesId
     */
    public function createId(string $id);

    /**
     * Creates the Index value object.
     *
     * @param int $index
     * @return \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesIndex
     */
    public function createIndex(int $index);

    /**
     * Creates the Name value object.
     *
     * @param string $firstName
     * @param string $lastName
     * @return \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesName
     */
    public function createName(string $firstName, string $lastName);

    /**
     * Creates the MessageBody value object.
     *
     * @param string $message
     * @return \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesMessageBody
     */
    public function createMessageBody(string $message);

    /**
     * Creates the Username value object.
     *
     * @param string $username
     * @return \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesUsername
     */
    public function createUsername(string $username);
}
