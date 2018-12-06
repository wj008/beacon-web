<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 2018/7/21
 * Time: 4:55
 */

namespace app\service\controller;


use app\service\lib\Uploader;
use beacon\Config;
use beacon\Controller;

class XhUpload extends Controller
{
    //通用上传
    public function indexAction()
    {
        $this->setContentType('json');
        if (!isset($_SERVER['DOCUMENT_ROOT'])) {
            $_SERVER['DOCUMENT_ROOT'] = Utils::path(ROOT_DIR, 'www');
        }
        $config = Config::get('upload.*');
        $immediate = $this->param('immediate:i', 0);
        //严格要求图片比例
        $upload = new Uploader('filedata', $config);
        try {
            $upload->saveFile();
            if ($upload->getState() != 'SUCCESS') {
                $this->error($upload->getState());
            }
            $files = $upload->getFileInfo();
            $msg = array();
            $msg['err'] = '';
            $msg['msg'] = $files[0]['url'];
            if ($immediate == 1) {
                $msg['msg'] = '!' . $msg['msg'];
            }
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