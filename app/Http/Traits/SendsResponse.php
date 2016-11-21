<?php
namespace MaxHumme\ChatApi\Http\Traits;

use MaxHumme\ChatApi\Http\Contracts\Handlers\ResponseHandler as ResponseHandlerContract;
use MaxHumme\ChatApi\Http\Formatters\AbstractFormatter;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Trait SendsResponse
 *
 * Use it in classes that need to send a JSON response.
 *
 * @author Max Humme <max@humme.nl>
 */
trait SendsResponse
{
    /**
     * @var \MaxHumme\ChatApi\Http\Contracts\Handlers\ResponseHandler
     */
    private $responseHandler;

    /**
     * Aborts the app and sends an error response.
     *
     * @param int $statusCode
     * @param mixed $data
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException to abort and send the response through our
     *         Exception handler.
     */
    protected function abort(int $statusCode, $data = null)
    {
        $errorMessage = $this->responseHandler->formatDataToErrorMessage($data);
        throw new HttpException($statusCode, $errorMessage);
    }

    /**
     * Builds the response.
     *
     * @param int $statusCode
     * @param \MaxHumme\ChatApi\Http\Formatters\AbstractFormatter $formatter
     * @throws \RuntimeException when $jsonResponse attribute is not set.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function response(int $statusCode, AbstractFormatter $formatter = null)
    {
        if (is_null($this->responseHandler)) {
            throw new RuntimeException('Attribute $responseHandler not set.');
        }

        return $this->responseHandler->create($statusCode, $formatter);
    }

    /**
     * Sets the response handler instance.
     *
     * @param \MaxHumme\ChatApi\Http\Contracts\Handlers\ResponseHandler $responseHandler
     */
    protected function setResponseHandler(ResponseHandlerContract $responseHandler)
    {
        $this->responseHandler = $responseHandler;
    }
}
