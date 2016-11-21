<?php
namespace MaxHumme\ChatApi\Framework\Providers;

use Illuminate\Support\ServiceProvider;
use MaxHumme\ChatApi\Domain\Contracts\Repositories\MessageRepository as MessageRepositoryContract;
use MaxHumme\ChatApi\Domain\Contracts\Repositories\PersonRepository as PersonRepositoryContract;
use MaxHumme\ChatApi\Http\Contracts\Repositories\UserRepository as UserRepositoryContract;
use MaxHumme\ChatApi\Infrastructure\Contracts\Factories\CriteriaFactory as CriteriaFactoryContract;
use MaxHumme\ChatApi\Infrastructure\Factories\CriteriaFactory;
use MaxHumme\ChatApi\Infrastructure\Repositories\MessageRepository;
use MaxHumme\ChatApi\Infrastructure\Repositories\PersonRepository;
use MaxHumme\ChatApi\Infrastructure\Repositories\UserRepository;

/**
 * Class InfrastructureServiceProvider
 *
 * @author Max Humme <max@humme.nl>
 */
final class InfrastructureServiceProvider extends ServiceProvider
{
    /**
     * Registers infrastructure services and binds contracts to implementations.
     */
    public function register()
    {
        $this->app->singleton(CriteriaFactoryContract::class, CriteriaFactory::class);
        $this->app->singleton(MessageRepositoryContract::class, MessageRepository::class);
        $this->app->singleton(PersonRepositoryContract::class, PersonRepository::class);
        $this->app->singleton(UserRepositoryContract::class, UserRepository::class);
    }
}
