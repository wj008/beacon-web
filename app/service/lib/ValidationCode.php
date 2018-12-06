<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 2018/1/4
 * Time: 21:07
 */

namespace app\service\lib;

/**
 * @version 1.0
 * @author   Ben
 * @date 2008-1-30
 * @email jinmaodao116@163.com
 * @验证码文件类
 * int function imagecolorallocate(rc image, int red, int green, int blue) //为一幅图像分配颜色
 * bool function imagefilledrectangle(rc image, int x1, int y1, int x2, int y2, int color) //画一矩形并填充
 * bool function imagerectangle(rc image, int x1, int y1, int x2, int y2, int col)   //画一个矩形
 * bool function imagesetpixel(rc image, int x, int y, int color)   //画一个单一像素
 */
class ValidationCode
{

    private $width, $height, $codenum;
    public $checkcode;     //产生的验证码
    private $checkimage;    //验证码图片
    private $disturbColor = ''; //干扰像素
    public $bgColor = ''; // 背景颜色
    private $_bgColor = '';
    public $fontColor = '';
    private $_fontColor = '';
    public $txtshowColor = '';

//private $isrun
    /*
     * 参数：（宽度，高度，字符个数）
     */

    function __construct($width = '70', $height = '22', $codenum = '4')
    {
        $this->width = $width;
        $this->height = $height;
        $this->codenum = $codenum;
    }

    function outImg()
    {
        if (empty($this->bgColor)) {
            $this->_bgColor = array(rand(200, 255), rand(200, 255), rand(200, 255));
        } else {
            $this->_bgColor = $this->Color2RGB($this->bgColor);
        }

        if (empty($this->fontColor)) {
            $this->_fontColor = array(rand(0, 50), rand(0, 50), rand(0, 50));
        } else {
            $this->_fontColor = $this->Color2RGB($this->fontColor);
        }
        //输出头
        $this->outFileHeader();
        //产生图片
        $this->createImage();
        //设置干扰像素
        $this->setDisturbColor();
        //往图片上写验证码
        $this->writeCheckCodeToImage();
        imagepng($this->checkimage);
        imagedestroy($this->checkimage);
    }

    /*
     * @brief 输出头
     */

    private function outFileHeader()
    {
        header("Content-type: image/png");
    }

    /**
     * 产生验证码
     */
    public function createCode()
    {
        $this->checkcode = $this->randString($this->codenum);
        $this->checkcode = str_pad($this->checkcode, $this->codenum, '0', STR_PAD_LEFT);
    }

    private function randString($len = 4)
    {
        $chars = "1234567890ABCDEFGHJKLMNPQRSTUVWXYZ";
        $string = "";
        for ($i = 0; $i < $len; $i++) {
            $rand = rand(0, strlen($chars) - 1);
            $string .= substr($chars, $rand, 1);
        }
        return $string;
    }

    /**
     * 产生验证码图片
     */
    private function createImage()
    {
        $this->checkimage = @imagecreate($this->width, $this->height);
        $back = imagecolorallocate($this->checkimage, $this->_bgColor[0], $this->_bgColor[1], $this->_bgColor[2]);
        $border = imagecolorallocate($this->checkimage, 0, 0, 0);
        imagefilledrectangle($this->checkimage, 0, 0, $this->width - 1, $this->height - 1, $back); // 白色底
        //imagerectangle($this->checkimage,0,0,$this->width - 1,$this->height - 1,$border);   // 黑色边框
    }

    /**
     * 设置图片的干扰像素
     */
    private function setDisturbColor()
    {
        for ($i = 0; $i <= 2; $i++) {
            $this->disturbColor = imagecolorallocate($this->checkimage, rand(150, 200), rand(150, 200), rand(150, 200));
            imageline($this->checkimage, rand(1, $this->width - 1), rand(1, $this->height - 1), rand(1, $this->width - 1), rand(1, $this->height - 1), $this->disturbColor);
        }
        for ($i = 0; $i <= 50; $i++) {
            $this->disturbColor = imagecolorallocate($this->checkimage, rand(30, 150), rand(30, 150), rand(30, 150));
            imagesetpixel($this->checkimage, rand(2, 48), rand(2, 14), $this->disturbColor);
        }
    }

    /**
     *
     * 在验证码图片上逐个画上验证码
     *
     */
    private function writeCheckCodeToImage()
    {
        for ($i = 0; $i < $this->codenum; $i++) {
            $bg_color1 = imagecolorallocate($this->checkimage, $this->_fontColor[0], $this->_fontColor[1], $this->_fontColor[2]);
            $x = floor($this->width / $this->codenum) * $i + 2;
            $y = rand(0, $this->height - 14);
            if (!empty($this->txtshowColor)) {
                $color = $this->Color2RGB($this->txtshowColor);
                $bg_color2 = imagecolorallocate($this->checkimage, $color[0], $color[1], $color[2]);
            } else {
                $bg_color2 = imagecolorallocate($this->checkimage, rand(50, 150), rand(50, 150), rand(50, 150));
            }
            imagechar($this->checkimage, 5, $x - 1, $y - 1, $this->checkcode[$i], $bg_color2);
            imagechar($this->checkimage, 5, $x, $y, $this->checkcode[$i], $bg_color1);
        }
    }

    function __destruct()
    {
        unset($this->width, $this->height, $this->codenum);
    }

    private function Color2RGB($hexColor)
    {
        if (preg_match('@^rgb\((\d+),\s?(\d+),\s?(\d+)\)$@i', $hexColor, $data)) {
            return array($data[1], $data[2], $data[3]);
        }
        $color = str_replace('#', '', $hexColor);
        if (strlen($color) > 3) {
            $rgb = array(
                hexdec(substr($color, 0, 2)),
                hexdec(substr($color, 2, 2)),
                hexdec(substr($color, 4, 2))
            );
        } else {
            $color = str_replace('#', '', $hexColor);
            $r = substr($color, 0, 1) . substr($color, 0, 1);
            $g = substr($color, 1, 1) . substr($color, 1, 1);
            $b = substr($color, 2, 1) . substr($color, 2, 1);
            $rgb = array(
                hexdec($r),
                hexdec($g),
                hexdec($b)
            );
        }
        return $rgb;
    }

}
