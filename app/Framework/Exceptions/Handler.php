<?php
namespace MaxHumme\ChatApi\Framework\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use MaxHumme\ChatApi\Domain\Exceptions\DomainException;
use MaxHumme\ChatApi\Http\Contracts\Factories\FormatterFactory as FormatterFactoryContract;
use MaxHumme\ChatApi\Http\Handlers\JsonResponseHandler;
use MaxHumme\ChatApi\Http\Traits\SendsResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class Handler
 *
 * @author Lumen
 * @author Max Humme <max@humme.nl>
 */
final class Handler extends ExceptionHandler
{
    use SendsResponse;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        DomainException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * @var \MaxHumme\ChatApi\Http\Contracts\Factories\FormatterFactory
     */
    private $formatterFactory;

    /**
     * Handler constructor.
     *
     * @param \MaxHumme\ChatApi\Http\Contracts\Factories\FormatterFactory $formatterFactory
     * @param \MaxHumme\ChatApi\Http\Handlers\JsonResponseHandler $jsonResponseHandler
     */
    public function __construct(
        FormatterFactoryContract $formatterFactory,
        JsonResponseHandler $jsonResponseHandler
    ) {
        $this->formatterFactory = $formatterFactory;
        $this->setResponseHandler($jsonResponseHandler);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $e)
    {
        // send http exception responses as json
        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            $message = $e->getMessage();

            // Set empty message to standard error message
            if (empty($message)) {
                $message = Response::$statusTexts[$statusCode];
            }

            $errorFormatter = $this->formatterFactory->createErrorFormatter($statusCode, $message);

            return $this->response($e->getStatusCode(), $errorFormatter);
        }

        // send http error 500 in json format when we're not debugging
        if (!env('APP_DEBUG')) {
            return $this->response(500);
        }

        // all other exceptions are errors in the program
        return parent::render($request, $e);
    }
}
