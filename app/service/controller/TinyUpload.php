<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 2018/7/21
 * Time: 6:13
 */

namespace app\service\controller;


use app\service\lib\Uploader;
use beacon\Config;
use beacon\Controller;


class TinyUpload extends Controller
{
    public function indexAction()
    {

        $this->setContentType('json');
        if (!isset($_SERVER['DOCUMENT_ROOT'])) {
            $_SERVER['DOCUMENT_ROOT'] = Utils::path(ROOT_DIR, 'www');
        }
        $config = Config::get('upload.*');
        //严格要求图片比例
        $upload = new Uploader('file', $config);
        try {
            $upload->saveFile();
            if ($upload->getState() != 'SUCCESS') {
                $this->error($upload->getState());
            }
            $files = $upload->getFileInfo();
            $msg = array();

            $msg['err'] = '';
            $msg['location'] = $files[0]['url'];
            $msg['localName'] = $files[0]['fileName'];
            $msg['orgName'] = $files[0]['name'];
            return $msg;
        } catch (\Exception $e) {
            $msg = array();
            $msg['err'] = $upload->getState();
            return $msg;
        }
    }
}