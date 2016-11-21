<?php
namespace MaxHumme\ChatApi\Http\Formatters;

/**
 * Class AbstractFormatter
 *
 * @author Max Humme <max@humme.nl>
 */
abstract class AbstractFormatter
{
    /**
     * Here you can store the formatted data when rendered.
     *
     * @var mixed
     */
    protected $formattedData;

    /**
     * Renders the formatter.
     *
     * @return mixed
     */
    abstract public function render();
}
