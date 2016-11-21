<?php
namespace MaxHumme\ChatApi\Domain\ValueObjects;

use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesName as ProvidesNameContract;
use MaxHumme\ChatApi\Domain\Exceptions\NotAllowedDomainException;

/**
 * Class Name
 *
 * The Name value object.
 *
 * @author Max Humme <max@humme.nl>
 */
final class Name extends AbstractValueObject implements ProvidesNameContract
{
    /**
     * The maximum string length of a name part.
     *
     * @const int
     */
    const MAX_LENGTH = 50;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * Name constructor.
     *
     * @param string $firstName
     * @param string $lastName
     */
    public function __construct(string $firstName, string $lastName)
    {
        // validate input
        $this->validate($firstName, 'first');
        $this->validate($lastName, 'last');

        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /** @inheritdoc */
    public function equals(AbstractValueObject $valueObject)
    {
        return
            $valueObject instanceof ProvidesNameContract
            && $valueObject->firstName() === $this->firstName
            && $valueObject->lastName() === $this->lastName;
    }

    /** @inheritdoc */
    public function firstName()
    {
        return $this->firstName;
    }

    /** @inheritdoc */
    public function lastName()
    {
        return $this->lastName;
    }

    /**
     * Validates the name part.
     *
     * @param string $namePart
     * @param string $type
     * @throws \MaxHumme\ChatApi\Domain\Exceptions\NotAllowedDomainException when $namePart is empty.
     * @throws \MaxHumme\ChatApi\Domain\Exceptions\NotAllowedDomainException when $namePart is too long.
     */
    private function validate(string $namePart, string $type)
    {
        if (empty($namePart)) {
            throw new NotAllowedDomainException('An empty '.$type.' name is not allowed.');
        }

        if (self::MAX_LENGTH < strlen($namePart)) {
            throw new NotAllowedDomainException(ucfirst($type).' name may not exceed '.self::MAX_LENGTH.' characters.');
        }
    }
}
