<?php

namespace Brain\Redis;

class RedisConfig
{
    public static function get()
    {
        $config = [
            'host' => config('app.brain_redis_host') ?? env('BRAIN_REDIS_HOST'),
            'port' => config('app.brain_redis_port') ?? env('BRAIN_REDIS_PORT'),
            'database' => 0,
        ];

        $password = config('app.brain_redis_password') ?? env('BRAIN_REDIS_PASSWORD');

        if ($password) {
            $config['password'] = $password;
        }

        return $config;
    }
}