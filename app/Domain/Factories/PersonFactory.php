<?php
namespace MaxHumme\ChatApi\Domain\Factories;

use MaxHumme\ChatApi\Domain\Contracts\Factories\PersonFactory as PersonFactoryContract;
use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesId as ProvidesIdContract;
use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesName as ProvidesNameContract;
use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesUsername as ProvidesUsernameContract;
use MaxHumme\ChatApi\Domain\Entities\Person;

/**
 * Class PersonFactory
 *
 * Responsible for reconstituting (and in the future: creating) Person entities.
 *
 * @author Max Humme <max@humme.nl>
 */
final class PersonFactory implements PersonFactoryContract
{
    /** @inheritdoc */
    public function reconstitute(
        ProvidesIdContract $id,
        ProvidesUsernameContract $username,
        ProvidesNameContract $name
    ) {
        $person = new Person($username, $name);
        $person->setId($id);

        return $person;
    }
}
