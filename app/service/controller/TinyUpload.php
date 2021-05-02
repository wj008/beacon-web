<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 2018/7/21
 * Time: 6:13
 */

namespace app\service\controller;


use app\service\libs\Uploader;
use app\service\libs\UploadException;
use beacon\core\Config;
use beacon\core\Controller;
use beacon\core\Method;
use beacon\core\Request;
use beacon\core\Util;

/**
 * Class Tiny编辑器上传
 * @package app\service\controller
 */
class TinyUpload extends Controller
{
    #[Method(act: 'index', method: Method::POST)]
    public function indexAction(): array
    {
        Request::setContentType('json');
        if (!isset($_SERVaER['DOCUMENT_ROOT'])) {
            $_SERVER['DOCUMENT_ROOT'] = Util::path(ROOT_DIR, 'www');
        }
        $config = Config::get('upload.*');
        //严格要求图片比例
        $upload = new Uploader('file', $config);
        try {
            $files = $upload->saveFile();
            $msg = [];
            $msg['err'] = '';
            $msg['location'] = $files[0]['url'];
            $msg['localName'] = $files[0]['fileName'];
            $msg['orgName'] = $files[0]['name'];
            return $msg;
        } catch (UploadException $e) {
            $msg = [];
            $msg['err'] = $e->getMessage();
            return $msg;
        } catch (\Exception $e) {
            $msg = [];
            $msg['err'] = '上传文件失败';
            return $msg;
        }
    }
}