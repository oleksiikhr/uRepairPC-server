<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to a Redis channel';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Redis::psubscribe(['last_seen.now'], function ($message, $channel) {
            try {
                $data = json_decode($message);

                if (! in_array($data->type, ['user', 'pc'])) {
                    return;
                }

                $model = "App\\{$data->type}";
                $obj = $model::find($data->id);

                if (! $obj) {
                    return;
                }

                $obj->last_seen = now();
                $obj->save();

            } catch (\Exception $e) { }
        });
    }
}
