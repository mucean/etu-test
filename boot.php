<?php

defined('TEST') || define('TEST', true);

$root_dir = __DIR__;

require_once $root_dir . '/vendor/autoload.php';

\Etu\Application::registerNamespace(__DIR__, '\\');
