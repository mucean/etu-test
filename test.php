<?php

require './boot.php';

$weChatAccessToken = new \Lib\SDK\WeChat\AccessToken();

echo $weChatAccessToken->getAccessToken(), PHP_EOL;

$jsSDK = new \Lib\SDK\WeChat\JSSDK();

var_dump($jsSDK->getConfig('http://www.mucean.com'));

/* $weChatShortLink = new \Lib\SDK\WeChat\ShortLink();

echo $weChatShortLink->getShortLink('http://www.mucean.com'), PHP_EOL; */

/* $sinaShortLink = new \Lib\SDK\Sina\ShortLink();

echo $sinaShortLink->getShortLink('http://www.mucean.com'), PHP_EOL; */

$shortLink = new \Lib\SDK\ShortLink();

echo $shortLink->getShortLink('http://www.mucean.com'), PHP_EOL;
