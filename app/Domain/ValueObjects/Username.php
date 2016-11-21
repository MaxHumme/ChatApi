<?php
namespace MaxHumme\ChatApi\Domain\ValueObjects;

use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesUsername as ProvidesUsernameContract;
use MaxHumme\ChatApi\Domain\Exceptions\NotAllowedDomainException;

/**
 * Class Username
 *
 * The Username value object.
 *
 * @author Max Humme <max@humme.nl>
 */
final class Username extends AbstractValueObject implements ProvidesUsernameContract
{
    /**
     * The maximum string length of the username.
     *
     * @const int
     */
    const MAX_LENGTH = 50;

    /**
     * @var string
     */
    private $username;

    /**
     * Username constructor.
     *
     * @param string $username
     */
    public function __construct(string $username)
    {
        // validate input
        $this->validate($username);

        $this->username = $username;
    }

    /** @inheritdoc */
    public function equals(AbstractValueObject $valueObject)
    {
        return
            $valueObject instanceof ProvidesUsernameContract
            && $valueObject->username() === $this->username;
    }

    /** @inheritdoc */
    public function username()
    {
        return $this->username;
    }

    /**
     * Validates the username.
     *
     * @param string $username
     * @throws \MaxHumme\ChatApi\Domain\Exceptions\NotAllowedDomainException when $username is empty.
     * @throws \MaxHumme\ChatApi\Domain\Exceptions\NotAllowedDomainException when $username is too long.
     */
    private function validate(string $username)
    {
        if (empty($username)) {
            throw new NotAllowedDomainException('An empty username is not allowed.');
        }

        if (self::MAX_LENGTH < strlen($username)) {
            throw new NotAllowedDomainException('Username cannot exceed '.self::MAX_LENGTH.' characters.');
        }
    }
}
