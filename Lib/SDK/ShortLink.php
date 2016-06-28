<?php

namespace Lib\SDK;

use Lib\SDK\WeChat\AccessToken;

/**
 * Class ShortLink
 */
class ShortLink
{
    use RequestTrait;

    protected $accessToken;

    public function __construct()
    {
        $this->accessToken = new AccessToken();
    }

    public function getShortLink($link)
    {
        $response = $this->getRequest(
            'https://api.weixin.qq.com/cgi-bin/shorturl',
            '',
            'POST',
            [
                'query' => [
                    'access_token' => $this->accessToken->getAccessToken(),
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
        }

        $this->logError(isset($data['errmsg'])
            ? 'get short link failed, code '. $data['errcode'] . '|| message: ' . $data['errmsg']
            : 'get short link failed');

        return false;
    }

    protected function logError($message)
    {
        error_log($message);
    }
}
