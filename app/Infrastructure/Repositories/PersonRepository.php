<?php
namespace MaxHumme\ChatApi\Infrastructure\Repositories;

use InvalidArgumentException;
use MaxHumme\ChatApi\Domain\Contracts\Entities\Person as PersonContract;
use MaxHumme\ChatApi\Domain\Contracts\Factories\PersonFactory as PersonFactoryContract;
use MaxHumme\ChatApi\Domain\Contracts\Factories\ValueObjectFactory as ValueObjectFactoryContract;
use MaxHumme\ChatApi\Domain\Contracts\Repositories\PersonRepository as PersonRepositoryContract;
use MaxHumme\ChatApi\Domain\Contracts\ValueObjects\ProvidesUsername as ProvidesUsernameContract;
use MaxHumme\ChatApi\Domain\Entities\AbstractEntity;
use MaxHumme\ChatApi\Infrastructure\Contracts\Factories\CriteriaFactory as CriteriaFactoryContract;
use MaxHumme\ChatApi\Infrastructure\Orm\User;

/**
 * Class PersonRepository
 *
 * @author Max Humme <max@humme.nl>
 */
final class PersonRepository extends AbstractEntityRepository implements PersonRepositoryContract
{
    /**
     * @var \MaxHumme\ChatApi\Domain\Contracts\Factories\PersonFactory
     */
    private $personFactory;

    /**
     * @var \MaxHumme\ChatApi\Domain\Contracts\Factories\ValueObjectFactory
     */
    private $valueObjectFactory;

    /**
     * ActorRepository constructor.
     *
     * @param \MaxHumme\ChatApi\Infrastructure\Contracts\Factories\CriteriaFactory $criteriaFactory
     * @param \MaxHumme\ChatApi\Domain\Contracts\Factories\PersonFactory $personFactory
     * @param \MaxHumme\ChatApi\Domain\Contracts\Factories\ValueObjectFactory $valueObjectFactory
     */
    public function __construct(
        CriteriaFactoryContract $criteriaFactory,
        PersonFactoryContract $personFactory,
        ValueObjectFactoryContract $valueObjectFactory
    ) {
        parent::__construct($criteriaFactory);

        $this->personFactory = $personFactory;
        $this->valueObjectFactory = $valueObjectFactory;
    }

    /** @inheritdoc */
    public function personWithUsername(ProvidesUsernameContract $username)
    {
        // Try to fetch the Person with $username from loaded entities.
        // First create the test for the Criteria object. We will feed that to the firstMatching method
        // provided by the AbstractEntityRepository.
        $test = function (PersonContract $person) use ($username) {
            return $person->username() === $username->username();
        };

        $criteria = $this->criteriaFactory->create($test);

        // Try to fetch the Person based on the Criteria we just created.
        // And return it if found.
        $person = $this->firstMatching($criteria);
        if (!is_null($person)) {
            return $person;
        }

        // Person not found in loaded entities, so query the data store for it.
        $userOrmObject = User::userWithUsername($username->username())->first();
        if (is_null($userOrmObject)) {
            return null;
        }

        // Bring the Person back to memory from our data store.
        $person = $this->personFactory->reconstitute(
            $this->valueObjectFactory->createId($userOrmObject->username),
            $this->valueObjectFactory->createUsername($userOrmObject->username),
            $this->valueObjectFactory->createName($userOrmObject->first_name, $userOrmObject->last_name)
        );

        // load the Person in the repo, so we don't have to query our data store for it
        // should we need it again in this request.
        $this->load($person, [$userOrmObject]);

        return $person;
    }

    /** @inheritdoc */
    protected function createOrmObjectsFor(AbstractEntity $person)
    {
        if (!$person instanceof PersonContract) {
            throw new InvalidArgumentException('Expected Person object. Got '.get_class($person).'.');
        }

        // create and save the ORM object.
        $user = new User;
        $user->username = $person->id();
        $user->auth_token = str_random(16);
        $user->first_name = $person->firstName();
        $user->last_name = $person->lastName();
        $user->save();

        return [$user];
    }
}
