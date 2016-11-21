<?php
namespace MaxHumme\ChatApi\Framework\Providers;

use Illuminate\Support\ServiceProvider;
use MaxHumme\ChatApi\Http\Contracts\Factories\FormatterFactory as FormatterFactoryContract;
use MaxHumme\ChatApi\Http\Factories\FormatterFactory;

/**
 * Class HttpServiceProvider
 *
 * @author Max Humme <max@humme.nl>
 */
final class HttpServiceProvider extends ServiceProvider
{
    /**
     * Registers http services and binds contracts to implementations.
     */
    public function register()
    {
        $this->app->singleton(FormatterFactoryContract::class, FormatterFactory::class);
    }
}
