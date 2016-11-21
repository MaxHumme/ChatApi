<?php
namespace MaxHumme\ChatApi\Infrastructure\Contracts\Repositories;

use MaxHumme\ChatApi\Domain\Entities\AbstractEntity;

/**
 * Interface TestsEntities
 *
 * @author Max Humme <max@humme.nl>
 */
interface TestsEntities
{
    /**
     * Returns whether the $entity passes the criteria.
     *
     * @param \MaxHumme\ChatApi\Domain\Entities\AbstractEntity $entity
     * @return boolean
     */
    public function areSatisfiedBy(AbstractEntity $entity);
}
