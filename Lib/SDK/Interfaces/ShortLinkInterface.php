<?php

namespace Lib\SDK\Interfaces;

interface ShortLinkInterface
{
    public function getShortLink($link);

    public function isEnable();
}
