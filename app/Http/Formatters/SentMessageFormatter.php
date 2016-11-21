<?php
namespace MaxHumme\ChatApi\Http\Formatters;

/**
 * Class SentMessageFormatter
 *
 * @author Max Humme <max@humme.nl>
 */
final class SentMessageFormatter extends AbstractFormatter
{
    /**
     * @var string
     */
    private $relation;

    /**
     * @var string
     */
    private $url;

    /**
     * SentMessageFormatter constructor.
     *
     * @param string $relation
     * @param string $url
     */
    public function __construct(string $relation, string $url)
    {
        $this->relation = $relation;
        $this->url = $url;
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

        $formattedData = [
            'links' => [
                'rel' => $this->relation,
                'href' => $this->url
            ]
        ];

        // set $formattedData so we don't have to render again when asked for it
        $this->formattedData = $formattedData;

        return $this->formattedData;
    }
}
