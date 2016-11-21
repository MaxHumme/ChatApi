<?php
namespace MaxHumme\ChatApi\Http\Handlers;

use Laravel\Lumen\Http\ResponseFactory;
use MaxHumme\ChatApi\Http\Contracts\Factories\FormatterFactory as FormatterFactoryContract;
use MaxHumme\ChatApi\Http\Contracts\Handlers\ResponseHandler as ResponseHandlerContract;
use MaxHumme\ChatApi\Http\Formatters\AbstractFormatter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class JsonResponseHandler
 *
 * Formats the given data and status code in a standard JSON response and adds the correct headers.
 * Formats an error response to an error object with an error code and message.
 *
 * @author Max Humme <max@humme.nl>
 */
final class JsonResponseHandler implements ResponseHandlerContract
{
    /**
     * @var \MaxHumme\ChatApi\Http\Contracts\Factories\FormatterFactory
     */
    private $formatterFactory;

    /**
     * @var \Laravel\Lumen\Http\ResponseFactory
     */
    private $responseFactory;

    /**
     * JsonResponseHandler constructor.
     *
     * @param \MaxHumme\ChatApi\Http\Contracts\Factories\FormatterFactory $formatterFactory
     * @param \Laravel\Lumen\Http\ResponseFactory $responseFactory
     */
    public function __construct(
        FormatterFactoryContract $formatterFactory,
        ResponseFactory $responseFactory
    ) {
        $this->formatterFactory = $formatterFactory;
        $this->responseFactory = $responseFactory;
    }

    /**
     * Creates the JSON response.
     *
     * @param int $statusCode
     * @param \MaxHumme\ChatApi\Http\Formatters\AbstractFormatter|null $formatter
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(int $statusCode, AbstractFormatter $formatter = null)
    {
        // take the $statusCode and decide if we have an error or a success response
        if ($this->isErrorCode($statusCode)) {
            // If we have no $responseFormatter, we fetch the standard error message from the Symfony package.
            // To prevent an error because of getting the standard message for an invalid status code, we need to
            // check if it is valid first. The Symfony package will validate the status code itself (and throw an
            // exception when invalid) when we create the json response, so we don't need to do this in this class.
            if (is_null($formatter) && $this->isValidStatusCode($statusCode)) {
                $formatter =
                    $this->formatterFactory->createErrorFormatter($statusCode, Response::$statusTexts[$statusCode]);
            }
        }

        return $this->response($statusCode, $formatter);
    }

    /** @inheritdoc */
    public function formatDataToErrorMessage($data)
    {
        return json_encode($data);
    }

    /**
     * Returns whether $statusCode is an error code.
     *
     * @param int $statusCode
     * @return boolean
     */
    private function isErrorCode(int $statusCode)
    {
        return 400 <= $statusCode;
    }

    /**
     * Returns whether $statusCode is a valid code.
     *
     * @param int $statusCode
     * @return boolean
     */
    private function isValidStatusCode(int $statusCode)
    {
        return key_exists($statusCode, Response::$statusTexts);
    }

    /**
     * Creates the JSON response.
     *
     * @param int $statusCode
     * @param \MaxHumme\ChatApi\Http\Formatters\AbstractFormatter $formatter
     * @return \Illuminate\Http\JsonResponse
     */
    private function response(int $statusCode, AbstractFormatter $formatter)
    {
        $headers = [];
        $data = $formatter->render();
        if (!empty($data)) {
            $headers = ['Content-Type' => 'application/json'];
        }

        return $this->responseFactory->json($data, $statusCode, $headers);
    }
}
