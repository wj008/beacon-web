<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 2018/1/4
 * Time: 21:04
 */

namespace app\service\controller;


use app\service\lib\ValidationCode;
use beacon\Controller;

class Code extends Controller
{
    public function indexAction()
    {
        $image = new ValidationCode('60', '26', '4');    //图片长度、宽度、字符个数
        $image->bgColor = '#F1F1F1';
        $image->createCode();
        $this->setSession('validCode', $image->checkcode);
        $image->outImg();
        exit;
    }
}