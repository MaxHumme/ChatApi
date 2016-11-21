<?php
namespace MaxHumme\ChatApi\Infrastructure\Contracts\Orm;

use Illuminate\Database\Eloquent\Builder;

/**
 * Interface User
 *
 * @author Max Humme <max@humme.nl>
 */
interface User
{
    /**
     * Returns the messages that were sent to this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages();

    /**
     * Limits the $query to selecting users with $username.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $username
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUserWithUsername(Builder $query, string $username);
}
