<?php
namespace MaxHumme\ChatApi\Http\Controllers;

use Illuminate\Http\Request;
use MaxHumme\ChatApi\Domain\Contracts\Services\MessageService as MessageServiceContract;
use MaxHumme\ChatApi\Domain\Exceptions\DomainException;
use MaxHumme\ChatApi\Http\Contracts\Controllers\MessageController as MessageControllerContract;
use MaxHumme\ChatApi\Http\Contracts\Factories\FormatterFactory as FormatterFactoryContract;
use MaxHumme\ChatApi\Http\Contracts\Repositories\UserRepository as UserRepositoryContract;
use MaxHumme\ChatApi\Http\Handlers\JsonResponseHandler;

/**
 * Class MessageController
 *
 * Responsible for handling all request that have to do with the Messages resource.
 * And creates responses for those requests.
 *
 * @author Max Humme <max@humme.nl>
 */
final class MessageController extends AbstractController implements MessageControllerContract
{
    /**
     * @var \MaxHumme\ChatApi\Http\Contracts\Factories\FormatterFactory
     */
    private $formatterFactory;

    /**
     * @var \MaxHumme\ChatApi\Domain\Contracts\Services\MessageService
     */
    private $messageService;

    /**
     * @var \MaxHumme\ChatApi\Http\Contracts\Repositories\UserRepository
     */
    private $users;

    /**
     * MessageController constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \MaxHumme\ChatApi\Http\Contracts\Factories\FormatterFactory $formatterFactory
     * @param \MaxHumme\ChatApi\Http\Handlers\JsonResponseHandler $jsonResponseHandler
     * @param \MaxHumme\ChatApi\Http\Contracts\Repositories\UserRepository $userRepository
     * @param \MaxHumme\ChatApi\Domain\Contracts\Services\MessageService $messageService
     */
    public function __construct(
        Request $request,
        FormatterFactoryContract $formatterFactory,
        JsonResponseHandler $jsonResponseHandler,
        UserRepositoryContract $userRepository,
        MessageServiceContract $messageService
    ) {
        parent::__construct($request, $jsonResponseHandler);

        $this->formatterFactory = $formatterFactory;
        $this->users = $userRepository;
        $this->messageService = $messageService;
    }

    /** @inheritdoc */
    public function getMessagesForUser()
    {
        // validate input
        $this->validateInputGetMessagesForUser();

        // grab input needed to fetch the messages
        $username = $this->request->user()->username;
        $offset = $this->request->input('offset');
        $limit = $this->request->input('limit');

        // Ask the domain to fetch the messages.
        // Catch DomainExceptions and notify the user of it.
        try {
            $messages = $this->messageService->fetchMessagesFor($username, $offset, $limit);
        } catch (DomainException $e) {
            $this->handleDomainException($e);
        }

        // format response
        $formatter = $this->formatterFactory->createMessageFormatter($messages);

        return $this->response(200, $formatter);
    }

    /** @inheritdoc */
    public function sendMessageTo(string $toUsername)
    {
        // validate input
        $this->validateInputSendMessageTo();

        // grab input needed to send the message
        $message = $this->request->input('message');
        $fromUsername = $this->request->user()->username;

        // Ask the domain to send the message.
        // Catch DomainExceptions and notify the user of it.
        try {
            $message = $this->messageService->sendMessage($message, $fromUsername, $toUsername);
        } catch (DomainException $e) {
            $this->handleDomainException($e);
        }

        // format response
        $url = route('getMessage', [
            'username' => $message->recipient()->id(),
            'index' => $message->id()
        ]);
        $relation = 'self';
        $formatter = $this->formatterFactory->createSentMessageFormatter($relation, $url);

        return $this->response(201, $formatter);
    }

    /**
     * Validates the input for the getMessagesForUser method.
     */
    private function validateInputGetMessagesForUser()
    {
        $errorMessages = [
            'offset.integer' => 'Offset should be passed as an integer.',
            'limit.integer' => 'Limit should be passed as an integer.'
        ];

        $this->validate($this->request, [
            'offset' => 'integer',
            'limit' => 'integer'
        ], $errorMessages);
    }

    /**
     * Validates the input for the sendMessageTo method.
     */
    private function validateInputSendMessageTo()
    {
        $errorMessages = ['message.required' => 'A message is required.'];

        $this->validate($this->request, [
            'message' => 'required'
        ], $errorMessages);
    }
}
