<?php

require __DIR__ . '/boot.php';

$mysql = new \Etu\Service\Sql\Mysql([
    'dsn' => 'mysql:host=127.0.0.1;dbname=lottery',
    'user' => 'root',
    'password' => 'omymysql'
]);

//$statement = $mysql->execute('SELECT * FROM ? WHERE userId = ?', ['users', 10000000]);
$statement = $mysql->execute('SELECT * FROM users WHERE userId = ?', [10000000]);
echo $statement->queryString, PHP_EOL;
$statement->debugDumpParams();
//var_dump($statement->fetchAll());
