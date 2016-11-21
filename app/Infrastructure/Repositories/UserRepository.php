<?php
namespace MaxHumme\ChatApi\Infrastructure\Repositories;

use MaxHumme\ChatApi\Http\Contracts\Repositories\UserRepository as UserRepositoryContract;
use MaxHumme\ChatApi\Infrastructure\Orm\User;

/**
 * Class UserRepository
 *
 * @author Max Humme <max@humme.nl>
 */
final class UserRepository implements UserRepositoryContract
{
    /** @inheritdoc */
    public function userWithUsername(string $username)
    {
        return User::userWithUsername($username)->first();
    }
}
