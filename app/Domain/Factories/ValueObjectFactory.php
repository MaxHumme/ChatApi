<?php
namespace MaxHumme\ChatApi\Domain\Factories;

use MaxHumme\ChatApi\Domain\Contracts\Factories\ValueObjectFactory as ValueObjectFactoryContract;
use MaxHumme\ChatApi\Domain\ValueObjects\Id;
use MaxHumme\ChatApi\Domain\ValueObjects\Index;
use MaxHumme\ChatApi\Domain\ValueObjects\MessageBody;
use MaxHumme\ChatApi\Domain\ValueObjects\Name;
use MaxHumme\ChatApi\Domain\ValueObjects\Username;

/**
 * Class ValueObjectFactory
 *
 * Responsible for creating the Domain's value objects.
 *
 * @author Max Humme <max@humme.nl>
 */
final class ValueObjectFactory implements ValueObjectFactoryContract
{
    /** @inheritdoc */
    public function createId(string $id)
    {
        return new Id($id);
    }

    /** @inheritdoc */
    public function createIndex(int $index)
    {
        return new Index($index);
    }

    /** @inheritdoc */
    public function createName(string $firstName, string $lastName)
    {
        return new Name($firstName, $lastName);
    }

    /** @inheritdoc */
    public function createMessageBody(string $message)
    {
        return new MessageBody($message);
    }

    /** @inheritdoc */
    public function createUsername(string $username)
    {
        return new Username($username);
    }
}
