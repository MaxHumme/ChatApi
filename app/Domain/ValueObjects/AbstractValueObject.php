<?php
namespace MaxHumme\ChatApi\Domain\ValueObjects;

/**
 * Class AbstractValueObject
 *
 * @author Max Humme <max@humme.nl>
 */
abstract class AbstractValueObject
{
    /**
     * Returns if this value object's content equals another's.
     *
     * @param \MaxHumme\ChatApi\Domain\ValueObjects\AbstractValueObject $valueObject
     * @return boolean
     */
    abstract public function equals(AbstractValueObject $valueObject);
}
