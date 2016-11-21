<?php
namespace MaxHumme\ChatApi\Http\Contracts\Repositories;

/**
 * Interface UserRepository
 *
 * @author Max Humme <max@humme.nl>
 */
interface UserRepository
{
    /**
     * Returns the User with the given $username.
     *
     * @param string $username
     * @return \MaxHumme\ChatApi\Infrastructure\Contracts\Orm\User|null
     */
    public function userWithUsername(string $username);
}
