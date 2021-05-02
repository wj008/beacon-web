<?php

namespace app\service\controller;

use app\service\libs\ValidationCode;
use beacon\core\Controller;
use beacon\core\Method;
use beacon\core\Request;

/**
 * 验证码
 * Class ImgCode
 * @package app\service\controller
 */
class ImgCode extends Controller
{
    #[Method(act: 'index', method: Method::GET)]
    public function indexAction()
    {
        $image = new ValidationCode(90, 40, 4);    //图片长度、宽度、字符个数
        $image->bgColor = '#F1F1F1';
        $image->createCode();
        Request::setSession('validCode', $image->checkCode);
        $image->outImg();
        exit;
    }
}