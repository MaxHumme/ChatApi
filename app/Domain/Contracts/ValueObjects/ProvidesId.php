<?php
namespace MaxHumme\ChatApi\Domain\Contracts\ValueObjects;

/**
 * Interface ProvidesId
 *
 * @author Max Humme <max@humme.nl>
 */
interface ProvidesId
{
    /**
     * Returns the id.
     *
     * @return string
     */
    public function id();
}