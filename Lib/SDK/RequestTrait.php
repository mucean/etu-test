<?php

namespace Lib\SDK;

use GuzzleHttp\Client as HttpClient;
use InvalidArgumentException;

/**
 * Trait RequestTrait
 */
trait RequestTrait
{
    protected function getRequest($baseUrl = null, $path = null, $method = 'GET', $params = [])
    {
        $args = [];

        if ($baseUrl !== null) {
            $args['base_uri'] = $baseUrl;
            if ($path === null) {
                $path = '';
            }
        } elseif ($path === null) {
            throw new InvalidArgumentException();
        }

        $client = new HttpClient($args);

        return $client->request($method, $path, $params);
    }
}
