<?php
namespace Controllers\User;

use Controllers\Controller;

class Index extends Controller
{
    protected function before()
    {
        $this->checkAuthorization();
    }
}
