<?php

namespace app\service\libs;


use beacon\core\Util;


/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 2017/12/17
 * Time: 13:52
 */
class UploadException extends \Exception
{

}

class Uploader
{
    private array $config = []; //配置信息
    private string $fieldName; //文件域名
    private array $files = [];     //文件上传对象数组
    private string $error = ''; //上传状态信息,
    private array $stateMap = [//上传状态映射表，国际化用户需考虑此处数据的国际化
        "SUCCESS",
        "文件大小超出 upload_max_filesize 限制",
        "文件大小超出 MAX_FILE_SIZE 限制",
        "文件未被完整上传",
        "没有文件被上传",
        "上传文件为空",
        "ERROR_TMP_FILE" => "临时文件错误",
        "ERROR_TMP_FILE_NOT_FOUND" => "找不到临时文件",
        "ERROR_SIZE_EXCEED" => "文件大小超出网站限制",
        "ERROR_TYPE_NOT_ALLOWED" => "文件类型不允许",
        "ERROR_CREATE_DIR" => "目录创建失败",
        "ERROR_DIR_NOT_WRITEABLE" => "目录没有写权限",
        "ERROR_FILE_MOVE" => "文件保存时出错",
        "ERROR_FILE_NOT_FOUND" => "找不到上传文件",
        "ERROR_WRITE_CONTENT" => "写入文件内容错误",
        "ERROR_UNKNOWN" => "未知错误",
        "ERROR_DEAD_LINK" => "链接不可用",
        "ERROR_HTTP_LINK" => "链接不是http链接",
        "ERROR_HTTP_CONTENTS" => "链接contentType不正确"
    ];

    /**
     * 构造函数
     * @param string $fileField 表单名称
     * @param ?array $config 配置项
     */
    public function __construct(string $fileField = 'file', ?array $config = null)
    {
        $this->fieldName = $fileField;
        if ($config != null && is_array($config)) {
            $this->config = $config;
        }
        $this->config = array_merge([
            'imagePathFormat' => '/upfiles/images/{time}{rand:4}',
            'filePathFormat' => "/upfiles/files/{time}{rand:4}",
            'uploadMaxSize' => 52428800,
            'uploadAllowFiles' => [".png", ".jpg", ".jpeg", ".gif",
                ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
                ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
                ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
                ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml", ".dat"],
        ], $this->config);
    }

    /**
     * 上传文件
     * @return array
     * @throws UploadException
     */
    public function upFile(): array
    {
        $html5 = false;
        if (isset($_SERVER['HTTP_CONTENT_DISPOSITION']) && preg_match('/attachment;\s+name="(.+?)";\s+filename="(.+?)"/i', $_SERVER['HTTP_CONTENT_DISPOSITION'], $info)) {
            $html5 = true;
            $tmp_dir = ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();
            $tempPath = $tmp_dir . DIRECTORY_SEPARATOR . microtime();
            file_put_contents($tempPath, file_get_contents("php://input"));
            $localName = urldecode($info[2]);
            $this->files[0] = [
                'error' => 0,
                'tmp_name' => $tempPath,
                'name' => $localName,
                'size' => filesize($tempPath),
                'type' => 'application/octet-stream'
            ];
        } else {
            if (!isset($_FILES[$this->fieldName])) {
                $this->error = $this->getStateText("ERROR_FILE_NOT_FOUND");
                throw new UploadException($this->error);
            }
            $files = [];
            foreach ($_FILES[$this->fieldName] as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $idx => $val) {
                        if (!isset($files[$idx])) {
                            $files[$idx] = [];
                        }
                        $files[$idx][$key] = $val;
                    }
                } else {
                    if (!isset($files[0])) {
                        $files[0] = [];
                    }
                    $files[0][$key] = $value;
                }
            }
            $this->files = $files;
        }
        //没有任何可上传的文件
        if (count($this->files) == 0) {
            $this->error = $this->getStateText("ERROR_FILE_NOT_FOUND");
            throw new UploadException($this->error);
        }
        //检查错误
        foreach ($this->files as &$file) {
            if ($file['error']) {
                $this->error = $this->getStateText($file['error']);
                throw new UploadException($this->error);
            } else if (!file_exists($file['tmp_name'])) {
                $this->error = $this->getStateText("ERROR_TMP_FILE_NOT_FOUND");
                throw new UploadException($this->error);
            } else if (!$html5 && !is_uploaded_file($file['tmp_name'])) {
                $this->error = $this->getStateText("ERROR_TMP_FILE");
                throw new UploadException($this->error);
            }
            $file['ext'] = strtolower(strrchr($file['name'], '.'));
            if (!$this->checkSize($file)) {
                $this->error = $this->getStateText("ERROR_SIZE_EXCEED");
                throw new UploadException($this->error);
            }
            if (!$this->checkExt($file)) {
                $this->error = $this->getStateText("ERROR_TYPE_NOT_ALLOWED");
                throw new UploadException($this->error);
            }
        }
        return $this->files;
    }

    /**
     * 保存文件
     * @return array
     * @throws UploadException
     */
    public function saveFile(): array
    {
        $this->upFile();
        $list = [];
        $saved = [];
        foreach ($this->files as &$file) {
            $file['isImage'] = preg_match('/jpg|jpeg|gif|png|bmp/i', $file['ext']) ? true : false;
            $file['url'] = $this->getFileURL($file);
            $file['filePath'] = Util::path($_SERVER['DOCUMENT_ROOT'], $file['url']);
            $file['fileName'] = substr(strrchr($file['url'], '/'), 1);
            $file['orgName'] = $file['name'];
            $dirName = dirname($file['filePath']);
            Util::makeDir($dirName, 0777);
            if (!file_exists($dirName)) {
                $this->error = $this->getStateText("ERROR_CREATE_DIR");
                foreach ($saved as $item) {
                    if (file_exists($item)) {
                        @unlink($item);
                    }
                }
                throw new UploadException($this->error);
            } else if (!is_writeable($dirName)) {
                $this->error = $this->getStateText("ERROR_DIR_NOT_WRITEABLE");
                foreach ($saved as $item) {
                    if (file_exists($item)) {
                        @unlink($item);
                    }
                }
                throw new UploadException($this->error);
            }
            $rm = rename($file['tmp_name'], $file['filePath']);
            if ($rm) {
                $this->error = $this->stateMap[0];
                $saved[] = $file['filePath'];
                $list[] = $file;
                continue;
            }
            //移动
            if (!(move_uploaded_file($file["tmp_name"], $file['filePath']) && file_exists($file['filePath']))) {
                $this->error = $this->getStateText("ERROR_FILE_MOVE");
                foreach ($saved as $item) {
                    if (file_exists($item)) {
                        @unlink($item);
                    }
                }
                throw new UploadException($this->error);
            } else { //移动成功
                $this->error = $this->stateMap[0];
                $saved[] = $file['filePath'];
                $list[] = $file;
                continue;
            }
        }
        return $list;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * 获取状态码值内容
     * @param $errCode
     * @return string
     */
    private function getStateText($errCode): string
    {
        return !$this->stateMap[$errCode] ? $this->stateMap["ERROR_UNKNOWN"] : $this->stateMap[$errCode];
    }

    /**
     * 获取文件URL
     * @param array $file
     * @return string
     */
    private function getFileURL(array $file): string
    {
        $t = time();
        $d = explode('-', date("Y-y-m-d-H-i-s"));
        if ($file['isImage']) {
            $format = $this->config["imagePathFormat"];
        } else {
            $format = $this->config["filePathFormat"];
        }
        $format = str_replace("{yyyy}", $d[0], $format);
        $format = str_replace("{yy}", $d[1], $format);
        $format = str_replace("{mm}", $d[2], $format);
        $format = str_replace("{dd}", $d[3], $format);
        $format = str_replace("{hh}", $d[4], $format);
        $format = str_replace("{ii}", $d[5], $format);
        $format = str_replace("{ss}", $d[6], $format);
        $format = str_replace("{time}", $t, $format);
        if (stripos($format, '{md5}') !== false) {
            $md5 = md5_file($file['tmp_name']);
            $format = str_replace("{md5}", $md5, $format);
        }
        $oriName = substr($file['name'], 0, strrpos($file['name'], '.'));
        $oriName = preg_replace('@[|?"<>/*\\\\]+@', '', $oriName);
        $format = str_replace("{filename}", $oriName, $format);
        $randNum = rand(1, 1000000) . rand(1, 1000000);
        if (preg_match("@{rand:([\d]*)}@i", $format, $matches)) {
            $format = preg_replace("@{rand:[\d]*}@i", substr($randNum, 0, $matches[1]), $format);
        }
        return $format . $file['ext'];
    }


    /**
     * 检查文件后缀
     * @param $file
     * @return bool
     */
    private function checkExt($file): bool
    {
        if (count($this->config["uploadAllowFiles"]) == 0) {
            return true;
        }
        return in_array($file['ext'], $this->config["uploadAllowFiles"]);
    }

    /**
     * 检查文件大小
     * @param $file
     * @return bool
     */
    private function checkSize($file): bool
    {
        return intval($file['size']) <= intval($this->config["uploadMaxSize"]);
    }


}