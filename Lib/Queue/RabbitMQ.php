<?php

namespace Lib\Queue;

/**
 * Class RabbitMQ
 */
class RabbitMQ
{
    protected $amqp;

    public function __construct($credentials = [])
    {
        $this->amqp = new \AMQPConnection($credentials);
    }

    public function connect()
    {
        if ($this->amqp->isConnected()) {
            return true;
        }

        if ($this->amqp->connect()) {
            return true;
        }

        return false;
    }

    public function test()
    {
        // $this->connect();
        var_dump($this->amqp->getHeartbeatInterval());
    }
}
