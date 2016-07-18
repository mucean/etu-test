<?php

namespace Lib\SDK\Service;

use Predis\Client as RedisClient;
use InvalidArgumentException;

class Cache
{
    protected $defaultParameters = [
        'scheme' => 'tcp',
        'host' => '127.0.0.1',
        'port' => '6379',
        'timeout' => 3,
        'database' => 0,
        'persistent' => true
    ];

    protected $defaultOptions = [];

    protected $cacheKeyMap = [
        'Lib\SDK\WeChat\AccessToken' => 'weChat:AccessToken',
        'Lib\SDK\WeChat\JSSDK' => 'weChat:JSSDK',
        'Lib\SDK\WeChat\ShortLink' => 'WeChat:shortLink:isEnable',
        'Lib\SDK\Sina\ShortLink' => 'Sina:shortLink:isEnable',
        'Lib\SDK\ShortLink' => 'short:link:%s'
    ];

    protected $redisClient;
    protected $calledClass;

    protected $userParameters = [];
    protected $userOptions = [];

    public function __construct($className, $conf = [])
    {
        if (!isset($this->cacheKeyMap[$className])) {
            throw new InvalidArgumentException(sprintf('cache key is not found by passed class %s', $className));
        }

        if (isset($conf['parameters']) && is_array($conf['parameters'])) {
            $this->userParameters = $conf['parameters'];
        }

        if (isset($conf['options']) && is_array($conf['options'])) {
            $this->userOptions = $conf['options'];
        }

        $this->calledClass = $className;
    }

    public function getCacheService()
    {
        $parameters = array_merge($this->defaultParameters, $this->userParameters);
        $options = array_merge($this->defaultOptions, $this->userOptions);

        return new RedisClient($parameters, $options);
    }

    public function getCacheKey(array $parts = [], $key = '')
    {
        $cacheKey = $this->cacheKeyMap[$this->calledClass];

        if ($key && is_array($cacheKey) && isset($cacheKey[$key])) {
            $cacheKey = $cacheKey[$key];
        }

        if ($parts) {
            $args = array_merge([$cacheKey], $parts);
            $cacheKey = call_user_func_array('sprintf', $args);
        }

        return $cacheKey;
    }
}
