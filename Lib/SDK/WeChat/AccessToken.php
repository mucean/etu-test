<?php

namespace Lib\SDK\WeChat;

use Lib\SDK\Interfaces\AccessInterface;
use Lib\SDK\RequestTrait;
use Lib\SDK\Service\Cache;

/**
 * Class AccessToken
 */
class AccessToken implements AccessInterface
{
    use RequestTrait;

    protected $appID = 'wx5d1b1e5c687de420';

    protected $appSecret = 'd4624c36b6795d1d99dcf0547af5443d';

    protected $cache;

    public function __construct($appID = null, $appSecret = null)
    {
        if ($appID !== null) {
            $this->appID = $appID;
        }

        if ($appSecret !== null) {
            $this->appSecret = $appSecret;
        }

        $this->cache = new Cache(get_called_class());
    }

    public function getAccessToken()
    {
        $cacheServer = $this->cache->getCacheService();
        $cacheKey = $this->cache->getCacheKey();

        if ($accessToken = $cacheServer->get($cacheKey)) {
            return $accessToken;
        }

        $response = $this->getRequest(
            'https://api.weixin.qq.com/cgi-bin/token',
            '',
            'GET',
            [
                'query' => [
                    'grant_type' => 'client_credential',
                    'appid' => $this->appID,
                    'secret' => $this->appSecret
                ]
            ]
        );

        if ($response === false) {
            $this->logError('get access token failed');
            return false;
        }

        $data = json_decode((string) $response->getBody(), true);

        if (isset($data['access_token'])) {
            if (isset($data['expires_in'])) {
                $expire = (int) $data['expires_in'];
                if ($expire > 50) {
                    $expire -= 50;
                }
            } else {
                $expire = 7150;
            }

            $cacheServer->set($cacheKey, $data['access_token']);
            $cacheServer->expire($cacheKey, $expire);
            return $data['access_token'];
        }

        $this->logError(isset($data['errmsg'])
            ? 'get access token failed message: ' . $data['errmsg']
            : 'get access token failed');

        return false;
    }

    public function getAppID()
    {
        return $this->appID;
    }

    protected function logError($message)
    {
        error_log($message);
    }
}
