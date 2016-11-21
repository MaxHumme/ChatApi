<?php

namespace MaxHumme\ChatApi\Framework\BehatExtension\Context;

use Illuminate\Contracts\Console\Kernel;

trait Migrator
{

    /**
     * Migrate the database before each scenario.
     *
     * @beforeScenario
     */
    public function migrate()
    {
        app(Kernel::class)->call('migrate');
    }
}
