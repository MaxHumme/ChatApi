<?php
namespace MaxHumme\ChatApi\Infrastructure\Repositories;

use Closure;
use MaxHumme\ChatApi\Domain\Entities\AbstractEntity;
use MaxHumme\ChatApi\Infrastructure\Contracts\Repositories\TestsEntities as TestsEntitiesContract;

/**
 * Class Criteria
 *
 * Use it in entity repositories to test if an entity passes the $test.
 *
 * @author Max Humme <max@humme.nl>
 */
final class Criteria implements TestsEntitiesContract
{
    /**
     * @var Closure
     */
    private $test;

    /**
     * Criteria constructor.
     *
     * @param Closure $test
     */
    public function __construct(Closure $test)
    {
        $this->test = $test;
    }

    /** @inheritdoc */
    public function areSatisfiedBy(AbstractEntity $entity)
    {
        return call_user_func($this->test, $entity);
    }
}
