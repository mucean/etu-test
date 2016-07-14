<?php

require './boot.php';

$amqp = new \Lib\RabbitMQ();

$amqp->test();
