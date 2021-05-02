<?php

namespace app\home\controller;

use beacon\core\Controller;
use beacon\core\Method;

class Index extends Controller
{
    /**
     * 首页
     */
    #[Method(act: 'index', method: Method::GET)]
    public function index()
    {
        $this->display('index.tpl');
    }
}