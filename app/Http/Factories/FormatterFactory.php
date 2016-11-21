<?php
namespace MaxHumme\ChatApi\Http\Factories;

use MaxHumme\ChatApi\Http\Contracts\Factories\FormatterFactory as FormatterFactoryContract;
use MaxHumme\ChatApi\Http\Formatters\ErrorFormatter;
use MaxHumme\ChatApi\Http\Formatters\MessagesFormatter;
use MaxHumme\ChatApi\Http\Formatters\SentMessageFormatter;

/**
 * Class FormatterFactory
 *
 * Responsible for creating formatters.
 *
 * @author Max Humme <max@humme.nl>
 */
final class FormatterFactory implements FormatterFactoryContract
{
    /**
     * Creates an ErrorFormatter.
     *
     * @param int $statusCode
     * @param array|string $errorMessage
     * @return \MaxHumme\ChatApi\Http\Formatters\ErrorFormatter
     */
    public function createErrorFormatter(int $statusCode, $errorMessage)
    {
        return new ErrorFormatter($statusCode, $errorMessage);
    }

    /**
     * Creates a MessagesFormatter.
     *
     * @param \MaxHumme\ChatApi\Domain\Contracts\Entities\Message[] $messages
     * @return \MaxHumme\ChatApi\Http\Formatters\MessagesFormatter
     */
    public function createMessageFormatter(array $messages)
    {
        return new MessagesFormatter($messages);
    }

    /**
     * Creates a SentMessageFormatter.
     *
     * @param string $relation
     * @param string $url
     * @return \MaxHumme\ChatApi\Http\Formatters\SentMessageFormatter
     */
    public function createSentMessageFormatter(string $relation, string $url)
    {
        return new SentMessageFormatter($relation, $url);
    }
}
