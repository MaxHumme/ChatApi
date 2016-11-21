<?php
namespace MaxHumme\ChatApi\Domain\Contracts\Factories;

use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesId as ProvidesIdContract;
use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesName as ProvidesNameContract;
use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesUsername as ProvidesUsernameContract;

/**
 * Interface PersonFactory
 *
 * Use it for classes responsible for creating and reconstituting Person entities.
 *
 * @author Max Humme <max@humme.nl>
 */
interface PersonFactory
{
    /**
     * Reconstitutes a Person back to memory from the data store.
     *
     * @param \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesId $id
     * @param \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesUsername $username
     * @param \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesName $name
     * @return \MaxHumme\ChatApi\Domain\Contracts\Entities\Person
     */
    public function reconstitute(ProvidesIdContract $id, ProvidesUsernameContract $username, ProvidesNameContract $name);
}
