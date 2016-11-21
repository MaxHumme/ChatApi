<?php
namespace MaxHumme\ChatApi\Domain\ValueObjects;

use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesId as ProvidesIdContract;
use MaxHumme\ChatApi\Domain\Exceptions\NotAllowedDomainException;

/**
 * Class Username
 *
 * The Username value object.
 *
 * @author Max Humme <max@humme.nl>
 */
final class Id extends AbstractValueObject implements ProvidesIdContract
{
    /**
     * The maximum string length of the id.
     *
     * @const int
     */
    const MAX_LENGTH = 50;

    /**
     * @var string
     */
    private $id;

    /**
     * Id constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        // validate input
        $this->validate($id);

        $this->id = $id;
    }

    /** @inheritdoc */
    public function equals(AbstractValueObject $valueObject)
    {
        return
            $valueObject instanceof ProvidesIdContract
            && $valueObject->id() === $this->id;
    }

    /** @inheritdoc */
    public function id()
    {
        return $this->id;
    }

    /**
     * Validates the id.
     *
     * @param string $id
     * @throws \MaxHumme\ChatApi\Domain\Exceptions\NotAllowedDomainException when $id is empty.
     * @throws \MaxHumme\ChatApi\Domain\Exceptions\NotAllowedDomainException when $id is too long.
     */
    private function validate(string $id)
    {
        if (empty($id)) {
            throw new NotAllowedDomainException('An empty id is not allowed.');
        }

        if (self::MAX_LENGTH < strlen($id)) {
            throw new NotAllowedDomainException('Id cannot exceed '.self::MAX_LENGTH.' characters.');
        }
    }
}
