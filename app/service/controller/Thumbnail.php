<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 2018/4/23
 * Time: 0:44
 */

namespace app\service\controller;


use app\service\lib\ImageCrop;
use beacon\Config;
use beacon\Controller;
use beacon\Utils;

class Thumbnail extends Controller
{
    public function indexAction()
    {
        $file = $this->get('file');
        if (empty($file)) {
            exit;
        }
        $file = ltrim($file, '/');
        if (!preg_match('@^upfiles\/images\/(\d+)_(\d+)x(\d+)_(\d+)\.(jpg|jpeg|png|gif)$@i', $file, $match)) {
            exit;
        }
        $filename = $match[1];
        $width = intval($match[2]);
        $height = intval($match[3]);
        $allowSize = Config::get('thumbnail.allow_size', []);
        if (!empty($allowSize)) {
            if (!in_array($width . 'x' . $height, $allowSize)) {
                exit;
            }
        }
        $mode = intval($match[4]);
        $ext = $match[5];
        $oldPath = Utils::path(ROOT_DIR, 'www/upfiles/images', $filename . '.' . $ext);
        if (!file_exists($oldPath)) {
            exit;
        }
        $newfile = ImageCrop:: catSize($oldPath, $width, $height, $mode);
        $this->setContentType(strtolower($ext));
        readfile($newfile);
    }
}