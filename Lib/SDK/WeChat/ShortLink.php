<?php

namespace Lib\SDK\WeChat;

use Lib\SDK\RequestTrait;
use Lib\SDK\LogTrait;
use Lib\SDK\Interfaces\ShortLinkInterface;
use Lib\SDK\Service\Cache;

class ShortLink implements ShortLinkInterface
{
    use RequestTrait, LogTrait;

    protected $accessToken;

    protected $cache;

    public function __construct()
    {
        $this->accessToken = new AccessToken();
        $this->cache = new Cache(get_called_class());
    }

    public function getShortLink($link, $refreshToken = false)
    {
        $response = $this->getRequest(
            'https://api.weixin.qq.com/cgi-bin/shorturl',
            '',
            'POST',
            [
                'query' => [
                    'access_token' => $this->accessToken->getAccessToken(!$refreshToken),
                ],
                'json' => [
                    'action' => 'long2short',
                    'long_url' => $link
                ]
            ]
        );

        if ($response === false) {
            $this->logError('get short link failed');
            return false;
        }

        $data = json_decode((string) $response->getBody(), true);

        if (isset($data['errcode']) && (int) $data['errcode'] === 0) {
            return $data['short_url'];
        } elseif ($data['errcode'] == 42001 && !$refreshToken) {
            return $this->getShortLink($link, true);
        } elseif ((int) $data['errcode'] === 45009) {
            $cacheServer = $this->cache->getCacheService();
            $cacheKey = $this->cache->getCacheKey();
            $cacheServer->set($cacheKey, 'not available');
            $cacheServer->expireAt($cacheKey, strtotime(date('Y-m-d')) + 86400);
        }

        $this->logError(isset($data['errmsg'])
            ? 'get short link failed, code '. $data['errcode'] . '|| message: ' . $data['errmsg']
            : 'get short link failed');

        return false;
    }

    public function isEnable()
    {
        if ($this->cache->getCacheService()->get($this->cache->getCacheKey())) {
            return false;
        }

        return true;
    }
}
