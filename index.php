<?php

include './boot.php';

// $app = new \Etu\Application();
$app = new \Etu\Application(['setting' => ['showErrorDetails' => true]]);

$app->run();
