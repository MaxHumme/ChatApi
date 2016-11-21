<?php
namespace MaxHumme\ChatApi\Infrastructure\Factories;

use Closure;
use MaxHumme\ChatApi\Infrastructure\Contracts\Factories\CriteriaFactory as CriteriaFactoryContract;
use MaxHumme\ChatApi\Infrastructure\Repositories\Criteria;

/**
 * Class CriteriaFactory
 *
 * Responsible for creating Criteria objects.
 *
 * @author Max Humme <max@humme.nl>
 */
final class CriteriaFactory implements CriteriaFactoryContract
{
    /** @inheritdoc */
    public function create(Closure $test)
    {
        return new Criteria($test);
    }
}
