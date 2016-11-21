<?php
namespace MaxHumme\ChatApi\Http\Contracts\Factories;

/**
 * Interface FormatterFactory
 *
 * Responsible for creating formatters.
 *
 * @author Max Humme <max@humme.nl>
 */
interface FormatterFactory
{
    /**
     * Creates an ErrorFormatter.
     *
     * @param int $statusCode
     * @param array|string $errorMessage
     * @return \MaxHumme\ChatApi\Http\Formatters\ErrorFormatter
     */
    public function createErrorFormatter(int $statusCode, $errorMessage);

    /**
     * Creates a MessagesFormatter.
     *
     * @param \MaxHumme\ChatApi\Domain\Contracts\Entities\Message[] $messages
     * @return \MaxHumme\ChatApi\Http\Formatters\MessagesFormatter
     */
    public function createMessageFormatter(array $messages);

    /**
     * Creates a SentMessageFormatter.
     *
     * @param string $relation
     * @param string $url
     * @return \MaxHumme\ChatApi\Http\Formatters\SentMessageFormatter
     */
    public function createSentMessageFormatter(string $relation, string $url);
}
