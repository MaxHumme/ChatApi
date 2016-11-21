<?php
namespace MaxHumme\ChatApi\Domain\Exceptions;

use RuntimeException;

/**
 * Class DomainException
 *
 * The base DomainException class.
 * Use it to notify the layers above the Domain of any exceptions they might know how to handle.
 *
 * @author Max Humme <max@humme.nl>
 */
class DomainException extends RuntimeException
{
    // Empty on purpose.
}
