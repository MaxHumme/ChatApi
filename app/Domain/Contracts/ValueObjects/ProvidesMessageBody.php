<?php
namespace MaxHumme\ChatApi\Domain\Contracts\ValueObjects;

/**
 * Class MessageBody.
 *
 * @author Max Humme <max@humme.nl>
 */
interface ProvidesMessageBody
{
    /**
     * Returns the message.
     *
     * @return string
     */
    public function messageBody();
}
