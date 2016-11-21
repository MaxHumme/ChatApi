<?php
namespace MaxHumme\ChatApi\Infrastructure\Orm;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use MaxHumme\ChatApi\Infrastructure\Contracts\Orm\Message  as MessageContract;

/**
 * Class Message
 *
 * The Message Eloquent (ORM) model.
 *
 * @author Max Humme <max@humme.nl>
 */
final class Message extends Model implements MessageContract
{
    /** @inheritdoc */
    protected $hidden = [
        'id', 'from_user_id', 'to_user_id'
    ];

    /** @inheritdoc */
    protected $table = 'message';

    /** @inheritdoc */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /** @inheritdoc */
    public function scopeToUsername(Builder $query, string $username)
    {
        return $query->whereHas('toUser', function ($query) use ($username) {
            $query->userWithUsername($username);
        });
    }

    /** @inheritdoc */
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
