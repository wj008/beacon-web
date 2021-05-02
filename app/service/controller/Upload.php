<?php


namespace app\service\controller;


use app\service\libs\Uploader;
use app\service\libs\UploadException;
use beacon\core\Config;
use beacon\core\Controller;
use beacon\core\Method;
use beacon\core\Request;
use beacon\core\Util;

/**
 * 上传
 * Class Upload
 * @package app\service\controller
 */
class Upload extends Controller
{
    #[Method(act: 'index', method: Method::POST)]
    public function indexAction()
    {
        Request::setContentType('json');
        if (!isset($_SERVER['DOCUMENT_ROOT'])) {
            $_SERVER['DOCUMENT_ROOT'] = Util::path(ROOT_DIR, 'www');
        }
        $config = Config::get('upload.*');
        $upload = new Uploader('filedata', $config);
        try {
            $list = $upload->saveFile();
            $data = [];
            $data['url'] = $list[0]['url'];
            $data['localName'] = $list[0]['fileName'];
            $data['orgName'] = $list[0]['orgName'];
            $data['files'] = [];
            foreach ($list as $file) {
                $data['files'][] = [
                    'url' => $file['url'],
                    'localName' => $file['fileName'],
                    'orgName' => $file['orgName'],
                ];
            }
            $this->success("上传成功", ['data' => $data]);
        } catch (UploadException $e) {
            $this->error($e->getMessage());
        } catch (\Exception $e) {
            if (defined('DEV_DEBUG') && DEV_DEBUG) {
                $this->error('文件上传失败:' . $e->getMessage());
            } else {
                $this->error('文件上传失败');
            }
        }
    }

    #[Method(act: 'test', method: Method::GET)]
    public function testAction()
    {
        $info = [];
        $info[] = '<form action="/service/upload" enctype="multipart/form-data" method="post">';
        $info[] = '上传文件：<input type="file" name="filedata" multiple="multiple"><br>';
        $info[] = '<input type="submit" value="Upload"></form>';
        echo join('', $info);
    }

}