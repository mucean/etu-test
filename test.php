<?php

require __DIR__ . '/boot.php';

/*$mysql = new \Etu\Service\Sql\Mysql([
    //'dsn' => 'mysql:host=127.0.0.1;dbname=lottery',
    'dsn' => 'mysql:host=127.0.0.1;dbname=test',
    'user' => 'root',
    'password' => 'omymysql'
]);*/

/*$delete = $mysql->delete('order');

var_dump($delete->where('id = ?', 2)->execute());*/

/*$insert = $mysql->insert('order');

var_dump($insert->setColumns('id', 'name')->setValues([2, 'hello'])->execute());*/

\Etu\Service\Container::getInstance()->addService('test', function () {
    return new \Etu\Service\Sql\Mysql([
        'dsn' => 'mysql:host=127.0.0.1;dbname=test',
        'user' => 'root',
        'password' => 'omymysql'
    ]);
});

$select = \Model\User\Entity::select();

$select->where('userId = ?', 10000001);

var_dump($select->get());