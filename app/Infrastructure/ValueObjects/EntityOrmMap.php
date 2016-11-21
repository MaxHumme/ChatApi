<?php
namespace MaxHumme\ChatApi\Infrastructure\ValueObjects;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use MaxHumme\ChatApi\Domain\Entities\AbstractEntity;
use MaxHumme\ChatApi\Infrastructure\Contracts\ValueObjects\ProvidesEntityOrmMapping as ProvidesEntityOrmMappingContract;

/**
 * Class EntityOrmMap
 *
 * A value object to map entities to orm objects.
 * Use it in Entity repositories to load entities and their orm objects.
 *
 * @author Max Humme <max@humme.nl>
 */
final class EntityOrmMap implements ProvidesEntityOrmMappingContract
{
    /**
     * @var \MaxHumme\ChatApi\Domain\Entities\AbstractEntity
     */
    private $entity;

    /**
     * @var \Illuminate\Database\Eloquent\Model[]
     */
    private $ormObjects;

    /**
     * EntityOrmMap constructor.
     *
     * @param \MaxHumme\ChatApi\Domain\Entities\AbstractEntity $entity
     * @param \Illuminate\Database\Eloquent\Model[] $ormObjects
     */
    public function __construct(AbstractEntity $entity, array $ormObjects)
    {
        foreach ($ormObjects as $ormObject) {
            if (!$ormObject instanceof Model) {
                throw new InvalidArgumentException('Eloquent object expected. Got '.get_class($ormObject).'.');
            }
        }

        $this->entity = $entity;
        $this->ormObjects = $ormObjects;
    }

    /** @inheritdoc */
    public function entity()
    {
        return $this->entity;
    }

    /** @inheritdoc */
    public function ormObjects()
    {
        return $this->ormObjects;
    }
}
