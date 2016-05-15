<?php

defined('TEST') || define('TEST', true);

$root_dir = __DIR__;

require_once $root_dir . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

\Etu\Application::registerNamespace(__DIR__, '\\');

// echo stream_get_contents(fopen('php://input', 'r'));
// var_dump($_POST);

echo '<pre><code>';
var_dump($_SERVER);
// var_dump($_REQUEST);
echo '</code></pre>';
