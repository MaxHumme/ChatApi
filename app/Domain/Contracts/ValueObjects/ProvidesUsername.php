<?php
namespace MaxHumme\ChatApi\Domain\Contracts\ValueObjects;

/**
 * Interface ProvidesUsername
 *
 * @author Max Humme <max@humme.nl>
 */
interface ProvidesUsername
{
    /**
     * Returns the username.
     *
     * @return string
     */
    public function username();
}