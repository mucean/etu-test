<?php
namespace Model\Queue;

/**
 * Class Exchange
 */
class Exchange
{
    use ModdlewareTrait;

    protected $routingKey = null;
    protected $flags = AMQP_NOPARAM;
    protected $attributes = [];

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
    }

    protected function __invoke(Exchange $exchange)
    {
        return $exchange;
    }
}
