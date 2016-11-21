<?php
namespace MaxHumme\ChatApi\Domain\Contracts\ValueObjects;

/**
 * Interface ProvidesName
 *
 * @author Max Humme <max@humme.nl>
 */
interface ProvidesName
{
    /**
     * Returns the first name.
     *
     * @return string
     */
    public function firstName();

    /**
     * Returns the last name.
     *
     * @return string
     */
    public function lastName();
}