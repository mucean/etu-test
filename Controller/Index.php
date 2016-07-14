<?php

namespace Controller;

class Index
{
    public function get()
    {
        $this->response->write(phpinfo());
    }

    public function post()
    {
        $this->response->write('post method');
    }
}
