<?php
namespace MaxHumme\ChatApi\Domain\Entities;

use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesId as ProvidesIdContract;
use RuntimeException;

/**
 * Class AbstractEntity
 *
 * @author Max Humme <max@humme.nl>
 */
abstract class AbstractEntity
{
    /**
     * @var \MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesId
     */
    private $id;

    /**
     * Returns the id.
     *
     * @return string
     */
    public function id()
    {
        if (is_null($this->id)) {
            throw new RuntimeException('Attribute $id not set.');
        }

        return $this->id->id();
    }

    /**
     * Sets the $id attribute.
     *
     * @param \MaxHumme\ChatApi\Domain\Contracts\ProvidesId
     */
    public function setId(ProvidesIdContract $id)
    {
        $this->id = $id;
    }
}
