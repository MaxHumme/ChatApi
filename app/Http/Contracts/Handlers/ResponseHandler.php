<?php
namespace MaxHumme\ChatApi\Http\Contracts\Handlers;

use MaxHumme\ChatApi\Http\Formatters\AbstractFormatter;

/**
 * Interface ResponseHandler
 *
 * @author Max Humme <max@humme.nl>
 */
interface ResponseHandler
{
    /**
     * Creates the response.
     *
     * @param int $statusCode
     * @param \MaxHumme\ChatApi\Http\Formatters\AbstractFormatter|null $responseFormatter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(int $statusCode, AbstractFormatter $responseFormatter = null);

    /**
     * Formats $data into an error message
     *
     * @param mixed[]|string $data
     * @return string
     */
    public function formatDataToErrorMessage($data);
}
