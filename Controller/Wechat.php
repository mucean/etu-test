<?php

namespace Controller;

use Lib\SDK\WeChat\AccessToken;
use Lib\SDK\WeChat\JSSDK;
use Lib\SDK\ShortLink;

class Wechat
{
    public function get()
    {
        /* $jsServer = new JSSDK();
        $config = json_encode($jsServer->getConfig($this->request));
        $this->response->write($config); */
        $shortLink = new ShortLink();
        $url = (string) $this->request->getUri();
        $this->response->write($shortLink->getShortLink($url));
    }
}
