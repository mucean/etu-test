<?php

namespace Model\User;

use Etu\ORM\Sql\Data;

class Entity extends Data
{
    protected static $mapperOptions = [
        'service' => 'test',
        'table' => 'users'
    ];
}