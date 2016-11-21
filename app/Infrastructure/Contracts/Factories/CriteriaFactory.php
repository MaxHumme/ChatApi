<?php
namespace MaxHumme\ChatApi\Infrastructure\Contracts\Factories;

use Closure;

/**
 * Interface CriteriaFactory
 *
 * @author Max Humme <max@humme.nl>
 */
interface CriteriaFactory
{
    /**
     * Creates a Criteria object
     *
     * @param \Closure $test
     * @return \MaxHumme\ChatApi\Infrastructure\Contracts\Repositories\TestsEntities
     */
    public function create(Closure $test);
}
