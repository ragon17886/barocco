<?php

namespace Tests;

use App\Facades\Currency;
use Database\Seeders\CurrencySeeder;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Cache;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        $this->prepareApp($app);

        return $app;
    }

    /**
     * Prepare app
     *
     * @param mixed $app
     * @return void
     */
    private function prepareApp($app)
    {
        $app->call(CurrencySeeder::class);

        Cache::flush();
        Currency::getCurrentCurrency();
    }
}
