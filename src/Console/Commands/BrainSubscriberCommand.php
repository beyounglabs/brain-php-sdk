<?php

namespace Brain\Console\Commands;

use Brain\Parameter\BrainParameter;
use Brain\Redis\RedisConfig;
use Illuminate\Console\Command;
use Predis\Client;

class BrainSubscriberCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:brain-parameter-subscriber';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to Redis Brain parameters channel';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $redis = new Client(RedisConfig::get());
        
        $redis->connect();

        if (!$redis->isConnected()) {
            throw new \Exception('Can\'t connect to Brain Redis when subscribing');
        }

        $redis->pubSubLoop(['subscribe' => BrainParameter::getKey()], function ($l, $msg) {
            BrainParameter::refresh();
            \Artisan::call('config:cache');
        });
    }
}
