<?php
namespace MaxHumme\ChatApi\Domain\Contracts\Entities;

/**
 * Interface Person
 *
 * @author Max Humme <max@humme.nl>
 */
interface Person
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

    /**
     * Returns the username.
     *
     * @return string
     */
    public function username();
}
