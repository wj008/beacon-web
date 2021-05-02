<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 2018/7/21
 * Time: 4:55
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
 * Xh编辑器上传
 * Class XhUpload
 * @package app\service\controller
 */
class XhUpload extends Controller
{
    #[Method(act: 'index', method: Method::POST)]
    public function indexAction(): array
    {
        Request::setContentType('json');
        if (!isset($_SERVER['DOCUMENT_ROOT'])) {
            $_SERVER['DOCUMENT_ROOT'] = Util::path(ROOT_DIR, 'www');
        }
        $config = Config::get('upload.*');
        $immediate = $this->param('immediate:i', 0);
        //严格要求图片比例
        $upload = new Uploader('filedata', $config);
        try {
            $files = $upload->saveFile();
            $msg = [];
            $msg['err'] = '';
            $msg['msg'] = $files[0]['url'];
            if ($immediate == 1) {
                $msg['msg'] = '!' . $msg['msg'];
            }
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