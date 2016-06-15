<?php

namespace Controller;

class Index
{
    public function get()
    {
        $this->response->write('Hello, world!');
    }
}
