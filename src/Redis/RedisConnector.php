<?php

namespace Brain\Redis;

use Predis\Client;

class RedisConnector
{
    /**
     * @var Client|null
     */
    protected static $redis = null;

    /**
     * @param array $config
     * @return Client
     * @throws \Exception
     */
    public function connect(array $config): Client
    {
        if (self::$redis) {
            return self::$redis;
        }

        $redis = new Client($config);
        $redis->connect();

        if (!$redis->isConnected()) {
            throw new \Exception('Can\'t connect to Brain Redis');
        }

        self::$redis = $redis;

        return $redis;
    }
}