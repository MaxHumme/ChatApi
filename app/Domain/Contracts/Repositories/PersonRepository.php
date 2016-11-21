<?php
namespace MaxHumme\ChatApi\Domain\Contracts\Repositories;

use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesUsername as ProvidesUsernameContract;

/**
 * Interface PersonRepository
 *
 * @author Max Humme <max@humme.nl>
 */
interface PersonRepository
{
    /**
     * Returns the recipient with the given $username.
     *
     * @param \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesUsername $username
     * @return \MaxHumme\ChatApi\Domain\Contracts\Entities\Person|null
     */
    public function personWithUsername(ProvidesUsernameContract $username);
}
