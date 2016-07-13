<?php

namespace Lib\SDK;

use Lib\SDK\Service\Cache;

/**
 * Class ShortLink
 */
class ShortLink
{
    use RequestTrait;

    protected $cache;

    const CACHE_TTL = 86400;

    protected $servers = [
        'weChat' => '\Lib\SDK\WeChat\ShortLink',
        'Sina' => '\Lib\SDK\Sina\ShortLink'
    ];

    public function __construct()
    {
        $this->cache = new Cache(get_called_class());
    }

    public function getShortLink($link)
    {
        $cacheKey = $this->cache->getCacheKey([$link]);
        $cacheServer = $this->cache->getCacheService();
        if ($shortLink = $cacheServer->get($cacheKey)) {
            return $shortLink;
        }

        foreach ($this->servers as $server) {
            if (!class_exists($server)) {
                throw new \RuntimeException();
            }
            /** @var $server ShortLinkInterface*/
            $server = new $server();
            if (!$server->isEnable()) {
                continue;
            }

            $shortLink = $server->getShortLink($link);
            if ($shortLink !== false) {
                $link = $shortLink;
                $cacheServer->set($cacheKey, $link);
                $cacheServer->expire($cacheKey, self::CACHE_TTL);
                break;
            }

            continue;
        }

        return $link;
    }

    protected function logError($message)
    {
        \logger()->warning($message);
    }
}
