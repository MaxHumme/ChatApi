<?php
namespace spec\MaxHumme\ChatApi\Domain\Services;

use MaxHumme\ChatApi\Domain\Contracts\Entities\Message as MessageContract;
use MaxHumme\ChatApi\Domain\Contracts\Entities\Person as PersonContract;
use MaxHumme\ChatApi\Domain\Contracts\Factories\MessageFactory as MessageFactoryContract;
use MaxHumme\ChatApi\Domain\Contracts\Factories\ValueObjectFactory as ValueObjectFactoryContract;
use MaxHumme\ChatApi\Domain\Contracts\Repositories\MessageRepository as MessageRepositoryContract;
use MaxHumme\ChatApi\Domain\Contracts\Repositories\PersonRepository as ActorRepositoryContract;
use MaxHumme\ChatApi\Domain\Contracts\Repositories\PersonRepository as PersonRepositoryContract;
use MaxHumme\ChatApi\Domain\Contracts\Services\MessageService as MessageServiceContract;
use MaxHumme\ChatApi\Domain\Entities\AbstractEntity;
use MaxHumme\ChatApi\Domain\Exceptions\NotFoundDomainException;
use MaxHumme\ChatApi\Domain\Services\MessageService;
use MaxHumme\ChatApi\Domain\ValueObjects\Id;
use MaxHumme\ChatApi\Domain\ValueObjects\MessageBody;
use MaxHumme\ChatApi\Domain\ValueObjects\Username;
use MaxHumme\ChatApi\Infrastructure\Repositories\AbstractEntityRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MessageServiceSpec extends ObjectBehavior
{
    function let(
        MessageFactoryContract $messageFactory,
        ValueObjectFactoryContract $valueObjectFactory,
        AbstractEntityRepository $messageRepository,
        AbstractEntityRepository $personRepository,
        AbstractEntity $messageEntity,
        AbstractEntity $senderEntity,
        AbstractEntity $recipientEntity
    ) {
        $messageRepository->implement(MessageRepositoryContract::class);
        $personRepository->implement(PersonRepositoryContract::class);
        $messageEntity->implement(MessageContract::class);
        $senderEntity->implement(PersonContract::class);
        $recipientEntity->implement(PersonContract::class);

        $this->beConstructedWith($messageFactory, $messageRepository, $personRepository, $valueObjectFactory);
    }

    function it_is_initializable()
    {
        $this->shouldImplement(MessageServiceContract::class);
    }

    function it_fetches_messages_for_the_user(
        MessageRepositoryContract $messageRepository,
        PersonRepositoryContract $personRepository,
        PersonContract $recipientEntity,
        ValueObjectFactoryContract $valueObjectFactory
    ) {
        $offset = null;
        $sanitizedOffset = MessageService::DEFAULT_OFFSET;
        $limit = null;
        $sanitizedLimit = MessageService::DEFAULT_LIMIT;
        $username = 'me';
        $usernameObject = new Username($username);
        $messages = ['20 messages for you!'];

        $valueObjectFactory->createUsername($username)->willReturn($usernameObject);
        $personRepository->personWithUsername($usernameObject)->willReturn($recipientEntity);
        $messageRepository->forRecipient($recipientEntity, $sanitizedOffset, $sanitizedLimit)->willReturn($messages);

        $this->fetchMessagesFor($username, $offset, $limit)->shouldReturn($messages);
    }

    function it_fetches_the_second_20_messages_for_the_user(
        MessageRepositoryContract $messageRepository,
        PersonRepositoryContract $personRepository,
        PersonContract $recipientEntity,
        ValueObjectFactoryContract $valueObjectFactory
    ) {
        $offset = 20;
        $limit = 20;
        $username = 'me';
        $usernameObject = new Username($username);
        $messages = ['20 messages for you!'];

        $valueObjectFactory->createUsername($username)->willReturn($usernameObject);
        $personRepository->personWithUsername($usernameObject)->willReturn($recipientEntity);
        $messageRepository->forRecipient($recipientEntity, $offset, $limit)->willReturn($messages);

        $this->fetchMessagesFor($username, $offset, $limit)->shouldReturn($messages);
    }

    function it_fetches_the_first_20_messages_for_the_user_with_a_non_numeric_offset_argument(
        MessageRepositoryContract $messageRepository,
        PersonRepositoryContract $personRepository,
        PersonContract $recipientEntity,
        ValueObjectFactoryContract $valueObjectFactory
    ) {
        $offset = 'wrong!';
        $sanitizedOffset = MessageService::DEFAULT_OFFSET;
        $limit = 20;
        $username = 'me';
        $usernameObject = new Username($username);
        $messages = ['20 messages for you!'];

        $valueObjectFactory->createUsername($username)->willReturn($usernameObject);
        $personRepository->personWithUsername($usernameObject)->willReturn($recipientEntity);
        $messageRepository->forRecipient($recipientEntity, $sanitizedOffset, $limit)->willReturn($messages);

        $this->fetchMessagesFor($username, $offset, $limit)->shouldReturn($messages);
    }

    function it_fetches_the_first_20_messages_for_the_user_with_a_negative_offset_argument(
        MessageRepositoryContract $messageRepository,
        PersonRepositoryContract $personRepository,
        PersonContract $recipientEntity,
        ValueObjectFactoryContract $valueObjectFactory
    ) {
        $offset = -20;
        $sanitizedOffset = MessageService::DEFAULT_OFFSET;
        $limit = 20;
        $username = 'me';
        $usernameObject = new Username($username);
        $messages = ['20 messages for you!'];

        $valueObjectFactory->createUsername($username)->willReturn($usernameObject);
        $personRepository->personWithUsername($usernameObject)->willReturn($recipientEntity);
        $messageRepository->forRecipient($recipientEntity, $sanitizedOffset, $limit)->willReturn($messages);

        $this->fetchMessagesFor($username, $offset, $limit)->shouldReturn($messages);
    }

    function it_fetches_the_first_20_messages_for_the_user_with_a_non_numeric_limit_argument(
        MessageRepositoryContract $messageRepository,
        PersonRepositoryContract $personRepository,
        PersonContract $recipientEntity,
        ValueObjectFactoryContract $valueObjectFactory
    ) {
        $offset = 0;
        $limit = 'wrong!';
        $sanitizedLimit = MessageService::DEFAULT_LIMIT;
        $username = 'me';
        $usernameObject = new Username($username);
        $messages = ['20 messages for you!'];

        $valueObjectFactory->createUsername($username)->willReturn($usernameObject);
        $personRepository->personWithUsername($usernameObject)->willReturn($recipientEntity);
        $messageRepository->forRecipient($recipientEntity, $offset, $sanitizedLimit)->willReturn($messages);

        $this->fetchMessagesFor($username, $offset, $limit)->shouldReturn($messages);
    }

    function it_fetches_the_first_20_messages_for_the_user_with_a_negative_limit_argument(
        MessageRepositoryContract $messageRepository,
        PersonRepositoryContract $personRepository,
        PersonContract $recipientEntity,
        ValueObjectFactoryContract $valueObjectFactory
    ) {
        $offset = 0;
        $limit = -10;
        $sanitizedLimit = MessageService::DEFAULT_LIMIT;
        $username = 'me';
        $usernameObject = new Username($username);
        $messages = ['20 messages for you!'];

        $valueObjectFactory->createUsername($username)->willReturn($usernameObject);
        $personRepository->personWithUsername($usernameObject)->willReturn($recipientEntity);
        $messageRepository->forRecipient($recipientEntity, $offset, $sanitizedLimit)->willReturn($messages);

        $this->fetchMessagesFor($username, $offset, $limit)->shouldReturn($messages);
    }

    function it_fetches_the_first_max_messages_for_the_user_with_a_too_large_limit_argument(
        MessageRepositoryContract $messageRepository,
        PersonRepositoryContract $personRepository,
        PersonContract $recipientEntity,
        ValueObjectFactoryContract $valueObjectFactory
    ) {
        $offset = 0;
        $limit = 999;
        $sanitizedLimit = MessageService::MAX_LIMIT;
        $username = 'me';
        $usernameObject = new Username($username);
        $messages = ['20 messages for you!'];

        $valueObjectFactory->createUsername($username)->willReturn($usernameObject);
        $personRepository->personWithUsername($usernameObject)->willReturn($recipientEntity);
        $messageRepository->forRecipient($recipientEntity, $offset, $sanitizedLimit)->willReturn($messages);

        $this->fetchMessagesFor($username, $offset, $limit)->shouldReturn($messages);
    }

    function it_throws_an_exception_when_the_recipient_is_not_found_on_fetching_messages_for_the_user(
        PersonRepositoryContract $personRepository,
        ValueObjectFactoryContract $valueObjectFactory
    ) {
        $offset = null;
        $limit = null;
        $username = 'me';
        $usernameObject = new Username($username);
        $recipientEntity = null; // this is why it throws an exception

        $valueObjectFactory->createUsername($username)->willReturn($usernameObject);
        $personRepository->personWithUsername($usernameObject)->willReturn($recipientEntity);

        $this->shouldThrow(NotFoundDomainException::class)->duringFetchMessagesFor($username, $offset, $limit);
    }

    function it_sends_a_message(
        MessageContract $messageEntity,
        MessageFactoryContract $messageFactory,
        MessageRepositoryContract $messageRepository,
        PersonRepositoryContract $personRepository,
        PersonContract $senderEntity,
        PersonContract $recipientEntity,
        ValueObjectFactoryContract $valueObjectFactory
    ) {
        $message = 'Testing, 1, 2, 3.';
        $fromUsername = 'me';
        $toUsername = 'you';
        $messageBodyObject = new MessageBody($message);
        $fromId = new Username($fromUsername);
        $toId = new Username($toUsername);

        $valueObjectFactory->createUsername($fromUsername)->willReturn($fromId);
        $personRepository->personWithUsername($fromId)->willReturn($senderEntity);
        $valueObjectFactory->createUsername($toUsername)->willReturn($toId);
        $personRepository->personWithUsername($toId)->willReturn($recipientEntity);
        $valueObjectFactory->createMessageBody($message)->willReturn($messageBodyObject);
        $messageFactory->create($messageBodyObject, $senderEntity, $recipientEntity)->willReturn($messageEntity);
        $messageRepository->add($messageEntity)->shouldBeCalled();

        $this->sendMessage($message, $fromUsername, $toUsername)->shouldReturn($messageEntity);
    }

    function it_throws_an_exception_when_the_sender_is_not_found_on_sending_a_message(
        ActorRepositoryContract $actorRepository,
        ValueObjectFactoryContract $valueObjectFactory
    ) {
        $message = 'Testing, 1, 2, 3.';
        $fromUsername = 'me';
        $toUsername = 'you';
        $fromId = new Username($fromUsername);
        $senderEntity = null; // this is why it throws an exception

        $valueObjectFactory->createUsername($fromUsername)->willReturn($fromId);
        $actorRepository->personWithUsername($fromId)->willReturn($senderEntity);

        $this->shouldThrow(NotFoundDomainException::class)->duringSendMessage($message, $fromUsername, $toUsername);
    }

    function it_throws_an_exception_when_the_recipient_is_not_found_on_sending_a_message(
        ActorRepositoryContract $actorRepository,
        PersonContract $senderEntity,
        ValueObjectFactoryContract $valueObjectFactory
    ) {
        $message = 'Testing, 1, 2, 3.';
        $fromUsername = 'me';
        $toUsername = 'you';
        $fromId = new Username($fromUsername);
        $toId = new Username($toUsername);
        $recipientEntity = null; // this is why it throws an exception

        $valueObjectFactory->createUsername($fromUsername)->willReturn($fromId);
        $actorRepository->personWithUsername($fromId)->willReturn($senderEntity);
        $valueObjectFactory->createUsername($toUsername)->willReturn($toId);
        $actorRepository->personWithUsername($toId)->willReturn($recipientEntity);

        $this->shouldThrow(NotFoundDomainException::class)->duringSendMessage($message, $fromUsername, $toUsername);
    }
}
