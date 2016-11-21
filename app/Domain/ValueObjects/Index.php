<?php
namespace MaxHumme\ChatApi\Domain\ValueObjects;

use InvalidArgumentException;
use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesIndex as ProvidesIndexContract;

/**
 * Class Index
 *
 * The Index value object.
 *
 * @author Max Humme <max@humme.nl>
 */
final class Index extends AbstractValueObject implements ProvidesIndexContract
{
    /**
     * @var int
     */
    private $index;

    /**
     * Index constructor.
     *
     * @param int $index
     */
    public function __construct(int $index)
    {
        // validate input
        $this->validate($index);

        $this->index = $index;
    }

    /** @inheritdoc */
    public function equals(AbstractValueObject $valueObject)
    {
        return
            $valueObject instanceof ProvidesIndexContract
            && $valueObject->index() === $this->index;
    }

    /** @inheritdoc */
    public function index()
    {
        return $this->index;
    }

    /**
     * Validates the index.
     *
     * @param int $index
     * @throws \InvalidArgumentException when $index is negative.
     */
    private function validate(int $index)
    {

        if ($index < 0) {
            throw new InvalidArgumentException('Index cannot be negative.');
        }
    }
}
