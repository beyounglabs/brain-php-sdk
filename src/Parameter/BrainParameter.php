<?php

namespace Brain\Parameter;

use Brain\Redis\RedisConfig;
use Brain\Redis\RedisConnector;

class BrainParameter
{
    /**
     * @var array
     */
    protected static $params;

    /**
     * @var string
     */
    protected static $key;

    /**
     * @return string
     * @throws \Exception
     */
    public static function getKey(): string
    {
        if (!empty(self::$key)) {
            return self::$key;
        }

        $service = config('app.brain_service') ?? env('BRAIN_SERVICE');
        $profile = config('app.brain_profile') ?? env('BRAIN_PROFILE');

        if (!$service) {
            throw new \Exception('Service not found');
        }

        if (!$profile) {
            throw new \Exception('Profile not found');
        }

        $key = sprintf('%s:%s', $service, $profile);

        self::$key = $key;

        return $key;
    }

    /**
     * @param string $param
     * @return null|string
     * @throws \Exception
     */
    protected static function getFromRedis(string $param = null): ?string
    {
        $redis = (new RedisConnector())->connect(RedisConfig::get());
        $result = $redis->get(self::getKey());

        $params = json_decode($result, true);
        self::$params = $params;

        if (!$param) {
            return null;
        }

        return isset($params[$param]) ? $params[$param] : null;
    }

    /**
     * @param string $param
     * @return null|string
     */
    protected static function getFromCache(string $param): ?string
    {
        return isset(self::$params[$param]) ? self::$params[$param] : null;
    }

    public static function refresh()
    {
        self::getFromRedis();
    }

    /**
     * @param string $param
     * @param null $default
     * @return null|string
     * @throws \Exception
     */
    public static function getRecursive(string $param, $default = null): ?string
    {
        $param = self::get($param, $default);

        if (isset(self::$params[$param])) {
            return self::getRecursive($param);
        }

        return $param;
    }

    /**
     * @param string $param
     * @param null|string $default
     * @return null|string
     * @throws \Exception
     */
    public static function get(string $param, $default = null): ?string
    {
        if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'testing') {
            return $default;
        }

        $value = null;

        if (self::$params) {
            $value = self::getFromCache($param);
        }

        if (!self::$params) {
            $value = self::getFromRedis($param);
        }

        if (!$value) {
            return $default;
        }

        return $value;
    }
}