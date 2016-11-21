<?php
namespace MaxHumme\ChatApi\Framework\Providers;

use Illuminate\Support\ServiceProvider;
use MaxHumme\ChatApi\Domain\Contracts\Factories\MessageFactory as MessageFactoryContract;
use MaxHumme\ChatApi\Domain\Contracts\Factories\PersonFactory as PersonFactoryContract;
use MaxHumme\ChatApi\Domain\Contracts\Factories\ValueObjectFactory as ValueObjectFactoryContract;
use MaxHumme\ChatApi\Domain\Contracts\Services\MessageService as MessageServiceContract;
use MaxHumme\ChatApi\Domain\Factories\MessageFactory;
use MaxHumme\ChatApi\Domain\Factories\PersonFactory;
use MaxHumme\ChatApi\Domain\Factories\ValueObjectFactory;
use MaxHumme\ChatApi\Domain\Services\MessageService;

/**
 * Class DomainServiceProvider
 *
 * @author Max Humme <max@humme.nl>
 */
final class DomainServiceProvider extends ServiceProvider
{
    /**
     * Registers domain services and binds contracts to implementations.
     */
    public function register()
    {
        $this->app->singleton(PersonFactoryContract::class, PersonFactory::class);
        $this->app->singleton(MessageFactoryContract::class, MessageFactory::class);
        $this->app->singleton(MessageServiceContract::class, MessageService::class);
        $this->app->singleton(ValueObjectFactoryContract::class, ValueObjectFactory::class);
    }
}
