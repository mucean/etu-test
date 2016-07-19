<?php
namespace Model\Queue;

use AMQPExchange;
use AMQPChannel;

/**
 * Class Exchange
 */
class Exchange
{
    use Middleware;

    protected $routingKey = null;
    protected $flags = AMQP_NOPARAM;
    protected $attributes = [];

    protected $exchange;

    public static function publish($message, $routingKey = null, $flags = null, $attributes = null)
    {
        $self = new static();
        $exchange = $self->executeMiddleware($this->getExchange());

        if ($routingKey === null) {
            $routingKey = $self->routingKey;
        }

        if ($flags === null) {
            $flags = $self->flags;
        }

        if ($attributes === null) {
            $attributes = $self->attributes;
        }

        // todo publish failed or exception throwed
        $exchange->publish($message, $routingKey, $flags, $attributes);
    }

    public function __construct()
    {
        $this->exchange = new AMQPChannel($this->getChannel());
    }

    protected function getChannel()
    {
        ;
    }

    protected function __call($method, $args)
    {
        $method();
        return $args;
    }

    protected function __invoke(APQMExchange $exchange)
    {
        return $exchange;
    }
}
