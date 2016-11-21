<?php
namespace MaxHumme\ChatApi\Infrastructure\Contracts\Orm;

use Illuminate\Database\Eloquent\Builder;

/**
 * Interface Message
 *
 * @author Max Humme <max@humme.nl>
 */
interface Message
{
    /**
     * Returns the user that sent this message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromUser();

    /**
     * Returns the messages sent to the user with $username.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $username
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeToUsername(Builder $query, string $username);

    /**
     * Returns the user that this message was sent to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toUser();
}
