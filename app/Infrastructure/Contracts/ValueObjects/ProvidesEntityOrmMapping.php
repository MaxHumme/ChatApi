<?php
namespace MaxHumme\ChatApi\Infrastructure\Contracts\ValueObjects;

/**
 * Interface ProvidesEntityOrmMapping
 *
 * @author Max Humme <max@humme.nl>
 */
interface ProvidesEntityOrmMapping
{
    /**
     * Returns the entity.
     *
     * @return \MaxHumme\ChatApi\Domain\Entities\AbstractEntity
     */
    public function entity();

    /**
     * Returns the orm objects.
     *
     * @return \Illuminate\Database\Eloquent\Model[]
     */
    public function ormObjects();
}
