<?php

namespace Lib\SDK\Sina;

use Lib\SDK\RequestTrait;
use Lib\SDK\Interfaces\ShortLinkInterface;
use Lib\SDK\Service\Cache;

class ShortLink implements ShortLinkInterface
{
    use RequestTrait;

    protected $appKey;

    protected $cache;

    public function __construct()
    {
        $this->appKey = '2448874369';
        $this->cache = new Cache(get_called_class());
    }

    public function getShortLink($link)
    {
        // 文档 http://open.weibo.com/wiki/Short_url/shorten
        // 错误代码 http://open.weibo.com/wiki/Error_code
        $response = $this->getRequest(
            'https://api.weibo.com/2/',
            'short_url/shorten.json',
            'POST',
            [
                'form_params' => [
                    'source' => $this->appKey,
                    'url_long' => $link
                ],
                // 微博开放平台的帐号没有通过审核需要添加帐号密码，审核通过后可以去掉
                'auth' => ['mucean@163.com', 'lcll_3451069']
            ]
        );

        if ($response === false) {
            $this->logError('sina get short link failed');
            return false;
        }

        $data = json_decode((string) $response->getBody(), true);

        if (isset($data["error_code"]) && $data["error"]) {
            $this->logError(sprintf(
                'sina get short link failed: error_code: %d || message: %s',
                (int) $data['error_code'],
                $data["error"]
            ));
            if ((int) $data['error_code'] === 20502) {
                $cacheServer = $this->cache->getCacheService();
                $cacheKey = $this->cache->getCacheKey();
                $cacheServer->set($cacheKey, 'not available');
                $cacheServer->expireAt($cacheKey, strtotime(date('Y-m-d')) + 86400);
            }
            return false;
        }

        if (!isset($data["urls"][0]["url_short"])) {
            $this->logError('sina response has not urls');
            return false;
        }

        if ((isset($data["urls"][0]["result"]) && $data["urls"][0]["result"] !== true)) {
            $this->logError('sina short link is not available');
            return false;
        }

        $link = $data["urls"][0]["url_short"];
        return $link;
    }

    public function isEnable()
    {
        if ($this->cache->getCacheService()->get($this->cache->getCacheKey())) {
            return false;
        }

        return true;
    }

    protected function logError($message)
    {
        \logger()->warning($message);
    }
}
