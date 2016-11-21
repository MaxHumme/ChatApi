<?php
namespace MaxHumme\ChatApi\Domain\Entities;

use MaxHumme\ChatApi\Domain\Contracts\Entities\Person as PersonContract;
use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesName as ProvidesNameContract;
use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesUsername as ProvidesUsernameContract;

/**
 * Class Person
 *
 * @author Max Humme <max@humme.nl>
 */
final class Person extends AbstractEntity implements PersonContract
{
    /**
     * @var \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesName
     */
    private $name;

    /**
     * @var \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesUsername
     */
    private $username;

    /**
     * Person constructor.
     *
     * @param \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesUsername $username
     * @param \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesName $name
     */
    public function __construct(ProvidesUsernameContract $username, ProvidesNameContract $name)
    {
        $this->username = $username;
        $this->name = $name;
    }

    /** @inheritdoc */
    public function firstName()
    {
        return $this->name->firstName();
    }

    /** @inheritdoc */
    public function lastName()
    {
        return $this->name->lastName();
    }

    /** @inheritdoc */
    public function username()
    {
        return $this->username->username();
    }
}
