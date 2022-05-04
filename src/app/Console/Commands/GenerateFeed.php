<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Support\Arr;
use App\Jobs\FeedGeneratorJob;
use App\Models\Feeds\GoogleCsv;
use App\Models\Feeds\GoogleXml;
use App\Models\Feeds\YandexXml;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class GenerateFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:generate
                            {instance? : Feed instance name}
                            {currency? : Currency for feed}';

    /**
     * @var array
     */
    const INSTANCES = [
        'yandex_xml' => YandexXml::class,
        'google_xml' => GoogleXml::class,
        'google_csv' => GoogleCsv::class,
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all instances or instance from arguments
     *
     * @return array
     */
    protected function getInstances(): array
    {
        $instances = self::INSTANCES;

        if (!empty($this->argument('instance'))) {
            $instance = strtolower($this->argument('instance'));
            $instances = Arr::only($instances, $instance);

            if (empty($instances)) {
                throw new \Exception('Unknown instance');
            }
        }

        return $instances;
    }

    /**
     * Get all currencies or currency from argument
     *
     * @return EloquentCollection
     */
    protected function getCurrencies(): EloquentCollection
    {
        $allCurrencies = Currency::all(['code', 'country', 'rate', 'decimals', 'symbol'])->keyBy('code');

        if (!empty($this->argument('currency'))) {
            $currency = strtoupper($this->argument('currency'));
            $allCurrencies = $allCurrencies->only($currency);

            if ($allCurrencies->isEmpty()) {
                throw new \Exception('Unknown currency');
            }
        }

        return $allCurrencies;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach ($this->getInstances() as $instance) {
            foreach ($this->getCurrencies() as $currency) {
                dispatch(new FeedGeneratorJob(new $instance, $currency));
            }
        }

        $this->info('Tasks created');
        return 0;
    }
}