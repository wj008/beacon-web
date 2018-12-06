<?php

namespace app\home\controller;

use beacon\Controller;

class Index extends Controller
{
    public function indexAction()
    {
        $this->display('Index.tpl');
    }

}