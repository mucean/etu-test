<?php

namespace Controller;

class Index
{
    public function get()
    {
        $this->response->write('Hello, world!' . PHP_EOL)
            ->write($this->request->getUri()->getQuery());
    }

    public function post()
    {
        $this->response->write('post method');
    }
}
