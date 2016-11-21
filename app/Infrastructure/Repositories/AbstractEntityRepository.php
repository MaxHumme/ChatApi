<?php
namespace MaxHumme\ChatApi\Infrastructure\Repositories;

use MaxHumme\ChatApi\Domain\Entities\AbstractEntity;
use MaxHumme\ChatApi\Infrastructure\Contracts\Factories\CriteriaFactory as CriteriaFactoryContract;
use MaxHumme\ChatApi\Infrastructure\Contracts\Repositories\TestsEntities as TestsEntitiesContract;
use MaxHumme\ChatApi\Infrastructure\ValueObjects\EntityOrmMap;

/**
 * Class AbstractEntityRepository
 *
 * @author Max Humme <max@humme.nl>
 */
abstract class AbstractEntityRepository
{
    /**
     * @var \MaxHumme\ChatApi\Infrastructure\Contracts\Factories\CriteriaFactory
     */
    protected $criteriaFactory;

    /**
     * @var \MaxHumme\ChatApi\Infrastructure\Contracts\ValueObjects\ProvidesEntityOrmMapping[]
     */
    private $entityOrmMaps = [];

    /**
     * AbstractEntityRepository constructor.
     *
     * @param \MaxHumme\ChatApi\Infrastructure\Contracts\Factories\CriteriaFactory $criteriaFactory
     */
    public function __construct(CriteriaFactoryContract $criteriaFactory)
    {
        $this->criteriaFactory = $criteriaFactory;
    }

    /**
     * Adds the $entity to the repository.
     *
     * Only when it's not loaded yet.
     *
     * @param \MaxHumme\ChatApi\Domain\Entities\AbstractEntity $entity
     */
    public function add(AbstractEntity $entity)
    {
        if (!$this->hasLoaded($entity)) {
            $ormObjects = $this->createOrmObjectsFor($entity);
            $this->load($entity, $ormObjects);
        }
    }

    /**
     * Returns the orm objects that belong to the given $entity.
     *
     * @param \MaxHumme\ChatApi\Domain\Entities\AbstractEntity $entity
     * @return \Illuminate\Database\Eloquent\Model[]
     */
    public function ormObjectsFor(AbstractEntity $entity)
    {
        foreach ($this->entityOrmMaps as $entityOrmMap) {
            if ($entityOrmMap->entity()->id() === $entity->id()) {
                return $entityOrmMap->ormObjects();
            }
        }
    }

    /**
     * Creates the ORM objects for the given $entity.
     *
     * Should be implemented by the repo, because it knows everything about those objects.
     *
     * @param \MaxHumme\ChatApi\Domain\Entities\AbstractEntity $entity
     * @throws \InvalidArgumentException when $entity is not of a type that is supported by the implementing repository.
     * @return \Illuminate\Database\Eloquent\Model[]
     */
    abstract protected function createOrmObjectsFor(AbstractEntity $entity);

    /**
     * Returns the first entity that satisfies the $criteria.
     *
     * @param \MaxHumme\ChatApi\Infrastructure\Contracts\Repositories\TestsEntities $criteria
     * @return \MaxHumme\ChatApi\Domain\Entities\AbstractEntity
     */
    protected function firstMatching(TestsEntitiesContract $criteria)
    {
        // grab the loaded entities from $entityOrmMaps and test each one until
        // we have one that satisfies the criteria.
        foreach ($this->entityOrmMaps as $entityOrmMap) {
            $loadedEntity = $entityOrmMap->entity();
            if ($criteria->areSatisfiedBy($loadedEntity)) {
                return $loadedEntity;
            }
        }
    }

    /**
     * Returns if the given $entity is loaded in this repo's memory.
     *
     * @param \MaxHumme\ChatApi\Domain\Entities\AbstractEntity $entity
     * @return boolean
     */
    protected function hasLoaded(AbstractEntity $entity)
    {
        // Try to find this entity in the $entityOrmMaps.
        // Match it on the entity's class and id attribute.
        $test = function ($loadedEntity) use ($entity) {
            $entityClassName = get_class($entity);
            return $loadedEntity instanceof $entityClassName && $loadedEntity->id() === $entity->id();
        };

        $criteria = $this->criteriaFactory->create($test);

        return !is_null($this->firstMatching($criteria));
    }

    /**
     * Loads the given $entity and $ormObjects together in this repo's memory.
     *
     * @param \MaxHumme\ChatApi\Domain\Entities\AbstractEntity $entity
     * @param \Illuminate\Database\Eloquent\Model[] $ormObjects
     */
    protected function load(AbstractEntity $entity, array $ormObjects)
    {
        if (!$this->hasLoaded($entity)) {
            array_push($this->entityOrmMaps, new EntityOrmMap($entity, $ormObjects));
        }
    }

    /**
     * Returns the entities that satisfy the $criteria.
     *
     * @param \MaxHumme\ChatApi\Infrastructure\Contracts\Repositories\TestsEntities $criteria
     * @return \MaxHumme\ChatApi\Domain\Entities\AbstractEntity[]
     */
    protected function matching(TestsEntitiesContract $criteria)
    {
        // grab the loaded entities from $entityOrmMaps.
        // Return the ones that satisfy the $criteria.
        $matchingLoadedEntities = [];
        foreach ($this->entityOrmMaps as $entityOrmMap) {
            $loadedEntity = $entityOrmMap->entity();
            if ($criteria->areSatisfiedBy($loadedEntity)) {
                array_push($matchingLoadedEntities, $loadedEntity);
            }
        }

        return $matchingLoadedEntities;
    }
}
