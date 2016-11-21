<?php
namespace MaxHumme\ChatApi\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as LumenController;
use MaxHumme\ChatApi\Domain\Exceptions\DomainException;
use MaxHumme\ChatApi\Domain\Exceptions\NotAllowedDomainException;
use MaxHumme\ChatApi\Domain\Exceptions\NotFoundDomainException;
use MaxHumme\ChatApi\Http\Contracts\Handlers\ResponseHandler;
use MaxHumme\ChatApi\Http\Traits\SendsResponse;

/**
 * Class AbstractController
 *
 * @author Lumen
 * @author Max Humme <max@humme.nl>
 */
abstract class AbstractController extends LumenController
{
    use SendsResponse;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * AbstractController constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \MaxHumme\ChatApi\Http\Contracts\Handlers\ResponseHandler $responseHandler
     */
    public function __construct(
        Request $request,
        ResponseHandler $responseHandler
    ) {
        $this->request = $request;
        $this->setResponseHandler($responseHandler);
    }

    /**
     * Validate the given request with the given rules.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     */
    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $message = array_flatten(array_values($validator->errors()->getMessages()));
            $this->abort(422, $message);
        }
    }

    /**
     * Handles the DomainException.
     *
     * @param \MaxHumme\ChatApi\Domain\Exceptions\DomainException $e
     */
    protected function handleDomainException(DomainException $e)
    {
        $message = $e->getMessage();

        if ($e instanceof NotFoundDomainException) {
            $this->abort(404, $message);
        } elseif ($e instanceof NotAllowedDomainException) {
            $this->abort(403, $message);
        } else {
            $this->abort(500, $message);
        }
    }
}
