<?php

namespace app\service\lib;

use beacon\Utils;

class ImageCrop
{

    private $sImage;
    private $dImage;
    private $srcFile;
    private $dstFile;
    private $srcWidth;
    private $srcHeight;
    private $srcExt;
    private $srcType;

    public function __construct($srcFile, $dstFile = '')
    {
        $this->srcFile = $srcFile;
        $this->dstFile = $dstFile;
        if (!$dstFile) {
            $this->dstFile = $this->srcFile;
        }
    }

    public function setSrcFile($srcFile)
    {
        $this->srcFile = $srcFile;
    }

    public function setDstFile($dstFile)
    {
        $this->dstFile = $dstFile;
    }

    private function loadImage()
    {
        list($this->srcWidth, $this->srcHeight, $this->srcType) = getimagesize($this->srcFile);
        if (!$this->srcWidth || !$this->srcHeight || !$this->srcType) {
            return false;
        }
        switch ($this->srcType) {
            case IMAGETYPE_JPEG :
                $this->sImage = @imagecreatefromjpeg($this->srcFile);
                $this->srcExt = 'jpg';
                break;
            case IMAGETYPE_PNG :
                $this->sImage = @imagecreatefrompng($this->srcFile);
                $this->srcExt = 'png';
                break;
            case IMAGETYPE_GIF :
                $this->sImage = @imagecreatefromgif($this->srcFile);
                $this->srcExt = 'gif';
                break;
            default:
                break;
        }
        return $this->sImage && is_resource($this->sImage) ? true : false;
    }

    public function saveImage($fileName = '')
    {
        $this->dstFile = $fileName ? $fileName : $this->dstFile;
        if ($this->dImage && is_resource($this->dImage)) {
            switch ($this->srcType) {
                case IMAGETYPE_JPEG :
                    @imagejpeg($this->dImage, $this->dstFile, 100);
                    break;
                case IMAGETYPE_PNG :
                    @imagepng($this->dImage, $this->dstFile);
                    break;
                case IMAGETYPE_GIF :
                    @imagegif($this->dImage, $this->dstFile);
                    break;
                default:
                    break;
            }
        }
    }

    public function outImage()
    {
        if ($this->dImage && is_resource($this->dImage)) {
            switch ($this->srcType) {
                case IMAGETYPE_JPEG :
                    header('Content-type: image/jpeg');
                    @imagejpeg($this->dImage, null, 100);
                    break;
                case IMAGETYPE_PNG :
                    header('Content-type: image/png');
                    @imagepng($this->dImage);
                    break;
                case IMAGETYPE_GIF :
                    header('Content-type: image/gif');
                    @imagegif($this->dImage);
                    break;
                default:
                    break;
            }
        }
    }

    public function saveAlpha($fileName = '')
    {
        $this->dstFile = $fileName ? $fileName . '.png' : $this->dstFile . '.png';
        if ($this->dImage && is_resource($this->dImage)) {
            @imagesavealpha($this->dImage, true);
            @imagepng($this->dImage, $this->dstFile);
        }
    }

    public function outAlpha()
    {
        if ($this->dImage && is_resource($this->dImage)) {
            @imagesavealpha($this->dImage, true);
            header('Content-type: image/png');
            @imagepng($this->dImage);
        }
    }

    public function destory()
    {
        if ($this->sImage && is_resource($this->sImage))
            @imagedestroy($this->sImage);
        if ($this->dImage && is_resource($this->dImage))
            @imagedestroy($this->dImage);
    }

    private function createImage($width, $height)
    {
        $im = @imagecreatetruecolor($width, $height);
        if (!$im || !is_resource($im))
            return false;
        $bg = @imagecolorallocatealpha($im, 255, 255, 255, 127);
        @imagefill($im, 0, 0, $bg);
        @imagecolortransparent($im, $bg);
        return $im;
    }

    public function crop($dst_width, $dst_height, $mode = 1, $dst_file = '')
    {
        // 判断是否需要裁减：
        if ($dst_width < 1 || $dst_height < 1)
            return false;
        list($this->srcWidth, $this->srcHeight, $this->srcType) = getimagesize($this->srcFile);
        if ($this->srcWidth == $dst_width && $this->srcHeight == $dst_height) {
            if ($this->srcFile == $this->dstFile) {
                return true;
            } else {  // 复制一份文件：
                return @copy($this->srcFile, $this->dstFile);
            }
        }
        $this->loadImage();
        if ($dst_file)
            $this->dstFile = $dst_file;

        $ratio_w = 1.0 * $dst_width / $this->srcWidth;
        $ratio_h = 1.0 * $dst_height / $this->srcHeight;
        $ratio = 1.0;

        switch ($mode) {
            case 1:        // always crop
                $this->dImage = $this->createImage($dst_width, $dst_height);
                if (!$this->dImage) {
                    return false;
                } // failed
                if (($ratio_w < 1 && $ratio_h < 1) || ($ratio_w > 1 && $ratio_h > 1)) {
                    $ratio = $ratio_w < $ratio_h ? $ratio_h : $ratio_w;
                    $tmp_w = (int)($dst_width / $ratio);
                    $tmp_h = (int)($dst_height / $ratio);
                    $tmp_img = @imagecreatetruecolor($tmp_w, $tmp_h);
                    if ($this->srcType == IMAGETYPE_PNG) {
                        @imagealphablending($tmp_img, FALSE);
                        @imagesavealpha($tmp_img, TRUE);
                    }
                    $src_x = abs(($this->srcWidth - $tmp_w) / 2);
                    $src_y = abs(($this->srcHeight - $tmp_h) / 2);
                    @imagecopy($tmp_img, $this->sImage, 0, 0, $src_x, $src_y, $tmp_w, $tmp_h);
                    @imagecopyresampled($this->dImage, $tmp_img, 0, 0, 0, 0, $dst_width, $dst_height, $tmp_w, $tmp_h);
                    @imagedestroy($tmp_img);
                } else {
                    $ratio = $ratio_w < $ratio_h ? $ratio_h : $ratio_w;
                    $tmp_w = (int)($this->srcWidth * $ratio);
                    $tmp_h = (int)($this->srcHeight * $ratio);
                    $tmp_img = @imagecreatetruecolor($tmp_w, $tmp_h);
                    if ($this->srcType == IMAGETYPE_PNG) {
                        @imagealphablending($tmp_img, FALSE);
                        @imagesavealpha($tmp_img, TRUE);
                    }
                    @imagecopyresampled($tmp_img, $this->sImage, 0, 0, 0, 0, $tmp_w, $tmp_h, $this->srcWidth, $this->srcHeight);
                    $src_x = abs($tmp_w - $dst_width) / 2;
                    $src_y = abs($tmp_h - $dst_height) / 2;
                    @imagecopy($this->dImage, $tmp_img, 0, 0, $src_x, $src_y, $dst_width, $dst_height);
                    @imagedestroy($tmp_img);
                }
                break;
            case 2:        // only small
                $this->dImage = $this->createImage($dst_width, $dst_height);
                if (!$this->dImage) {
                    return false;
                } // failed
                if ($ratio_w < 1 && $ratio_h < 1) {
                    $ratio = $ratio_w < $ratio_h ? $ratio_h : $ratio_w;
                    $tmp_w = (int)($dst_width / $ratio);
                    $tmp_h = (int)($dst_height / $ratio);
                    $tmp_img = @imagecreatetruecolor($tmp_w, $tmp_h);
                    if ($this->srcType == IMAGETYPE_PNG) {
                        @imagealphablending($tmp_img, FALSE);
                        @imagesavealpha($tmp_img, TRUE);
                    }
                    $src_x = (int)($this->srcWidth - $tmp_w) / 2;
                    $src_y = (int)($this->srcHeight - $tmp_h) / 2;
                    @imagecopy($tmp_img, $this->sImage, 0, 0, $src_x, $src_y, $tmp_w, $tmp_h);
                    @imagecopyresampled($this->dImage, $tmp_img, 0, 0, 0, 0, $dst_width, $dst_height, $tmp_w, $tmp_h);
                    @imagedestroy($tmp_img);
                } elseif ($ratio_w > 1 && $ratio_h > 1) {
                    $dst_x = (int)abs($dst_width - $this->srcWidth) / 2;
                    $dst_y = (int)abs($dst_height - $this->srcHeight) / 2;
                    @imagecopy($this->dImage, $this->sImage, $dst_x, $dst_y, 0, 0, $this->srcWidth, $this->srcHeight);
                } else {
                    $src_x = 0;
                    $dst_x = 0;
                    $src_y = 0;
                    $dst_y = 0;
                    if (($dst_width - $this->srcWidth) < 0) {
                        $src_x = (int)($this->srcWidth - $dst_width) / 2;
                        $dst_x = 0;
                    } else {
                        $src_x = 0;
                        $dst_x = (int)($dst_width - $this->srcWidth) / 2;
                    }

                    if (($dst_height - $this->srcHeight) < 0) {
                        $src_y = (int)($this->srcHeight - $dst_height) / 2;
                        $dst_y = 0;
                    } else {
                        $src_y = 0;
                        $dst_y = (int)($dst_height - $this->srcHeight) / 2;
                    }
                    @imagecopy($this->dImage, $this->sImage, $dst_x, $dst_y, $src_x, $src_y, $this->srcWidth, $this->srcHeight);
                }
                break;
            case 3:        // keep all image size and create need size
                $this->dImage = $this->createImage($dst_width, $dst_height);
                if (!$this->dImage) {
                    return false;
                } // failed
                if ($ratio_w > 1 && $ratio_h > 1) {
                    $dst_x = (int)(abs($dst_width - $this->srcWidth) / 2);
                    $dst_y = (int)(abs($dst_height - $this->srcHeight) / 2);
                    @imagecopy($this->dImage, $this->sImage, $dst_x, $dst_y, 0, 0, $this->srcWidth, $this->srcHeight);
                } else {
                    $ratio = $ratio_w > $ratio_h ? $ratio_h : $ratio_w;
                    $tmp_w = (int)($this->srcWidth * $ratio);
                    $tmp_h = (int)($this->srcHeight * $ratio);
                    $tmp_img = @imagecreatetruecolor($tmp_w, $tmp_h);
                    if ($this->srcType == IMAGETYPE_PNG) {
                        @imagealphablending($tmp_img, FALSE);
                        @imagesavealpha($tmp_img, TRUE);
                    }
                    @imagecopyresampled($tmp_img, $this->sImage, 0, 0, 0, 0, $tmp_w, $tmp_h, $this->srcWidth, $this->srcHeight);
                    $dst_x = (int)(abs($tmp_w - $dst_width) / 2);
                    $dst_y = (int)(abs($tmp_h - $dst_height) / 2);
                    @imagecopy($this->dImage, $tmp_img, $dst_x, $dst_y, 0, 0, $tmp_w, $tmp_h);
                    @imagedestroy($tmp_img);
                }
                break;
            case 4:        // keep all image but create actually size
                if ($ratio_w > 1 && $ratio_h > 1) {
                    $this->dImage = $this->sImage; // do nothing!
                } else {
                    $ratio = $ratio_w > $ratio_h ? $ratio_h : $ratio_w;
                    $tmp_w = (int)($this->srcWidth * $ratio);
                    $tmp_h = (int)($this->srcHeight * $ratio);
                    $this->dImage = @imagecreatetruecolor($tmp_w, $tmp_h);
                    @imagecopyresampled($this->dImage, $this->sImage, 0, 0, 0, 0, $tmp_w, $tmp_h, $this->srcWidth, $this->srcHeight);
                }
                break;
            case 5: // if dst > rc , crop , if (dst < rc) crop fixed ratio
                $ratio = $ratio_w < $ratio_h ? $ratio_h : $ratio_w;
                $tmp_w = (int)($dst_width / $ratio);
                $tmp_h = (int)($dst_height / $ratio);
                $src_x = floor(abs(($this->srcWidth - $tmp_w) / 2));
                $src_y = floor(abs(($this->srcHeight - $tmp_h) / 2));
                if (($ratio_w < 1 && $ratio_h < 1) || ($ratio_w > 1 && $ratio_h > 1)) {
                    if ($ratio_w < 1 && $ratio_h < 1) {
                        $tmp_img = imagecreatetruecolor($tmp_w, $tmp_h);
                        if ($this->srcType == IMAGETYPE_PNG) {
                            @imagealphablending($tmp_img, FALSE);
                            @imagesavealpha($tmp_img, TRUE);
                        }
                        $this->dImage = imagecreatetruecolor($dst_width, $dst_height);
                        imagecopy($tmp_img, $this->sImage, 0, 0, $src_x, $src_y, $tmp_w, $tmp_h);
                        imagecopyresampled($this->dImage, $tmp_img, 0, 0, 0, 0, $dst_width, $dst_height, $tmp_w, $tmp_h);
                        imagedestroy($tmp_img);
                    } elseif ($ratio_w > 1 && $ratio_h > 1) {
                        $this->dImage = @imagecreatetruecolor($tmp_w, $tmp_h);
                        @imagecopy($this->dImage, $this->sImage, 0, 0, $src_x, $src_y, $tmp_w, $tmp_h);
                    }
                } else {
                    $this->dImage = @imagecreatetruecolor($tmp_w, $tmp_h);
                    @imagecopy($this->dImage, $this->sImage, 0, 0, $src_x, $src_y, $this->srcWidth, $this->srcHeight);
                }
                break;
        }
        return $this->dImage && is_resource($this->dImage);
    }

    public static function catSize($path, $width, $height, $mode)
    {
        if ($mode < 1) {
            $mode = 1;
        }
        if ($mode > 5) {
            $mode = 5;
        }
        $path = Utils::path($path);
        $file = pathinfo($path);
        $tofile = Utils::path($file['dirname'], $file['filename'] . '_' . $width . 'x' . $height . '_' . $mode . '.' . $file['extension']);
        $ic = new ImageCrop($path, $tofile);
        $ic->crop($width, $height, $mode);
        $ic->saveImage();
        $ic->destory();
        return $tofile;
    }

}
