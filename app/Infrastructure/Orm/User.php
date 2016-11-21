<?php
namespace MaxHumme\ChatApi\Infrastructure\Orm;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use MaxHumme\ChatApi\Infrastructure\Contracts\Orm\User as UserContract;

/**
 * Class User
 *
 * The User Eloquent (ORM) model.
 *
 * @author Max Humme <max@humme.nl>
 */
final class User extends Model implements UserContract
{
    /** @inheritdoc */
    protected $hidden = [
        'id', 'auth_token'
    ];

    /** @inheritdoc */
    protected $table = 'user';

    /** @inheritdoc */
    public $timestamps = false;

    /** @inheritdoc */
    public function messages()
    {
        return $this->hasMany(Message::class, 'to_user_id');
    }

    /** @inheritdoc */
    public function scopeUserWithUsername(Builder $query, string $username)
    {
        return $query->where('username', $username);
    }
}
