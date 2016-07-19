<?php
namespace Model\Queue;

use AMQPConnection;

/**
 * Class Connection
 */
class Connection
{
    protected $credentials = [
        'host' => '127.0.0.1',
        'port' => '5672',
        'vhost' => '/',
        'login' => 'guest',
        'password' => 'guest',
        'connect_timeout' => 3
    ];

    protected $isPersistent;

    protected $connection;

    public function __construct($credentials, $isPersistent = false)
    {
        $this->credentials = array_merge($this->credentials, $credentials);
        $this->isPersistent = $isPersistent;
        $this->connection = new AMQPConnection($this->credentials);
    }

    public function connect($isPersistent = null)
    {
        if ($isPersistent !== null) {
            $this->isPersistent = $isPersistent;
        }

        if ($this->isPersistent) {
            return $this->connection->pconnect();
        } else {
            return $this->connection->connect();
        }
    }

    public function disconnect()
    {
        if ($this->isPersistent) {
            return $this->connection->pdisconnect();
        } else {
            return $this->connection->disconnect();
        }
    }

    public function __call($method, $args = [])
    {
        return $args
            ? call_user_func_array([$this->connection, $method], $args)
            : $this->connection->{$method}();
    }
}
