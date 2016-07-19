<?php
namespace Model\Queue;

use AMQPConnection;

/**
 * Class Producer
 */
class Producer
{
    protected $connection;
    protected $channel;

    public function __construct(AMQPConnection $connection = null)
    {
        if ($connection === null) {
            $connection = $this->getConnectionFromService();
        }

        $this->connection = $connection;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function getChannel()
    {
        $connection = $this->getConnection();
        if (!$connection->isConnected()) {
            $connection->connect();
        }

        return $this->channel = new AMQPChannel($connection);
    }

    protected function getConnectionFromService()
    {
        return new AMQPConnection();
    }
}
