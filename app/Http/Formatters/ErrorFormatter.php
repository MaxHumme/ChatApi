<?php
namespace MaxHumme\ChatApi\Http\Formatters;

use InvalidArgumentException;

/**
 * Class ErrorFormatter
 *
 * @author Max Humme <max@humme.nl>
 */
final class ErrorFormatter extends AbstractFormatter
{
    /**
     * @var array|string
     */
    private $errorMessage;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * ErrorFormatter constructor.
     *
     * @param int $statusCode
     * @param array|string $errorMessage
     */
    public function __construct(int $statusCode, $errorMessage)
    {
        $this->validateErrorMessage($errorMessage);

        $this->statusCode = $statusCode;
        $this->errorMessage = $errorMessage;
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed[]
     */
    public function render()
    {
        // return $formattedData when we have rendered it before
        if (!is_null($this->formattedData)) {
            return $this->formattedData;
        }

        // format errorMessageBody part of response.
        // if we have encode json stuff, decode it.
        if (is_string($this->errorMessage)) {
            $errorMessageBody = json_decode($this->errorMessage);
            if (is_null($errorMessageBody)) {
                $errorMessageBody = $this->errorMessage;
            }
        } else {
            $errorMessageBody = $this->errorMessage;
        }

        $formattedData = [
            'error' => [
                'status' => $this->statusCode,
                'message' => $errorMessageBody
            ]
        ];

        // set $formattedData so we don't have to render again when asked for it
        $this->formattedData = $formattedData;

        return $this->formattedData;
    }

    /**
     * Validates the $errorMessage on being an array or string.
     *
     * @param array|string $errorMessage
     * @throws \InvalidArgumentException when $errorMessage is not of type array or string
     */
    private function validateErrorMessage($errorMessage)
    {
        if (!is_array($errorMessage) && !is_string($errorMessage)) {
            throw new InvalidArgumentException('Parameter $errorMessage may be an array or string. Got '.gettype($errorMessage).'.');
        }
    }
}
