<?php

namespace Lib\SDK\WeChat;

use Lib\SDK\RequestTrait;
use Lib\SDK\Service\Cache;
use Lib\SDK\LogTrait;

/**
 * Class JSSDK
 */
class JSSDK
{
    use RequestTrait, LogTrait;

    protected $accessToken;

    protected $cache;

    public function __construct()
    {
        $this->accessToken = new AccessToken();
        $this->cache = new Cache(get_called_class());
    }

    public function getConfig($url)
    {
        $timestamp = time();
        $nonceStr = $this->getNonceStr();
        return [
            'appId' => $this->accessToken->getAppID(),
            'timestamp' => $timestamp,
            'nonceStr' => $nonceStr,
            'signature' => $this->getSignature($url, $timestamp, $nonceStr)
        ];
    }

    public function getSignature(
        $url,
        $timestamp,
        $nonceStr
    ) {
        $data = [
            sprintf('jsapi_ticket=%s', $this->getJsTicket()),
            sprintf('noncestr=%s', $nonceStr),
            sprintf('timestamp=%s', $timestamp),
            sprintf('url=%s', $url)
        ];
        asort($data);

        return sha1(implode('&', $data));
    }

    public function getJsTicket()
    {
        $cacheServer = $this->cache->getCacheService();
        $cacheKey = $this->cache->getCacheKey();

        if ($jsTicket = $cacheServer->get($cacheKey)) {
            return $jsTicket;
        }

        $response = $this->getRequest(
            'https://api.weixin.qq.com/cgi-bin/ticket/getticket',
            '',
            'GET',
            [
                'query' => [
                    'access_token' => $this->accessToken->getAccessToken(),
                    'type' => 'jsapi'
                ]
            ]
        );

        if ($response === false) {
            $this->logError('get js ticket failed');
            return false;
        }

        $data = json_decode((string) $response->getBody(), true);

        if (isset($data['errcode']) && (int) $data['errcode'] === 0) {
            if (isset($data['expires_in'])) {
                $expire = (int) $data['expires_in'];
                if ($expire > 50) {
                    $expire -= 50;
                }
            } else {
                $expire = 7150;
            }

            $cacheServer->set($cacheKey, $data['ticket']);
            $cacheServer->expire($cacheKey, $expire);
            return $data['ticket'];
        }

        $this->logError(isset($data['errmsg'])
            ? 'get js ticket failed message: ' . $data['errmsg']
            : 'get js ticket failed');

        return false;
    }

    public function getNonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charsLength = strlen($chars);
        $str = '';

        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, $charsLength - 1), 1);
        }

        return $str;
    }
}
