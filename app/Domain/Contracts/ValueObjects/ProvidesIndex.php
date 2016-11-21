<?php
namespace MaxHumme\ChatApi\Domain\Contracts\ValueObjects;

/**
 * Interface ProvidesIndex
 *
 * @author Max Humme <max@humme.nl>
 */
interface ProvidesIndex
{
    /**
     * Returns the index.
     *
     * @return int
     */
    public function index();
}