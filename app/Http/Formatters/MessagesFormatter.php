<?php
namespace MaxHumme\ChatApi\Http\Formatters;

use InvalidArgumentException;
use MaxHumme\ChatApi\Domain\Contracts\Entities\Message;

/**
 * Class MessagesFormatter
 *
 * @author Max Humme <max@humme.nl>
 */
final class MessagesFormatter extends AbstractFormatter
{
    /**
     * @var \MaxHumme\ChatApi\Domain\Contracts\Entities\Message[]
     */
    private $messages;

    /**
     * MessagesFormatter constructor.
     *
     * @param \MaxHumme\ChatApi\Domain\Contracts\Entities\Message[] $messages
     */
    public function __construct(array $messages)
    {
        $this->validate($messages);

        $this->messages = $messages;
    }

    /**
     * {@inheritdoc}
     *
     * @return string[]
     */
    public function render()
    {
        // return $formattedData when we have rendered it before
        if (!is_null($this->formattedData)) {
            return $this->formattedData;
        }

        $formattedData = [];
        $formattedData['messages'] = [];
        foreach ($this->messages as $message) {
            $author = $message->sender()->firstName().' '.$message->sender()->lastName();
            $body = $message->body();
            $formattedData['messages'][] = [
                'index' => $message->index(),
                'author' => $author,
                'body' => $body,
                'sentAt' => $message->sentAt()
            ];
        }

        // set $formattedData so we don't have to render again when asked for it
        $this->formattedData = $formattedData;

        return $this->formattedData;
    }

    /**
     * Validates the $errorMessage on being an array or string.
     *
     * @param \MaxHumme\ChatApi\Domain\Contracts\Entities\Message[] $messages
     * @throws \InvalidArgumentException when have an array with an object that is not a Message.
     */
    private function validate(array $messages)
    {
        foreach ($messages as $message) {
            if (!$message instanceof Message) {
                throw new InvalidArgumentException('Parameter $messages should be an array of Messages. Got '.get_class($message).'.');
            }
        }
    }
}
