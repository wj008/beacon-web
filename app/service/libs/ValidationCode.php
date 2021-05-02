<?php

namespace app\service\libs;

use beacon\core\Logger;
use beacon\core\Util;


class ValidationCode
{

    private int $width, $height, $codeNum;
    public string $checkCode;     //产生的验证码
    private ?\GdImage $checkImage;    //验证码图片

    public string $bgColor = ''; // 背景颜色
    public string $fontColor = '';
    public string $showColor = '';

    /**
     * ValidationCode constructor.
     * @param int $width
     * @param int $height
     * @param int $codeNum
     */
    public function __construct(int $width = 70, int $height = 22, int $codeNum = 4)
    {
        $this->width = $width;
        $this->height = $height;
        $this->codeNum = $codeNum;
    }

    public function outImg()
    {
        //输出头
        header("Content-type: image/png");
        //产生图片
        try {
            $this->createImage();
            //设置干扰像素
            $this->setInterferenceColor();
            //往图片上写验证码
            $this->writeCheckCodeToImage();
            imagepng($this->checkImage);
            imagedestroy($this->checkImage);
        } catch (\Exception $e) {

        }
    }

    /**
     * 获取base64的图片
     * @return string
     * @throws \Exception
     */
    public function getBase64(): string
    {
        ob_start();
        $this->createImage();
        $this->setInterferenceColor();
        $this->writeCheckCodeToImage();
        imagepng($this->checkImage);
        $imageData = ob_get_contents();
        imagedestroy($this->checkImage);
        ob_end_clean();
        return base64_encode($imageData);
    }

    /**
     * 产生验证码
     */
    public function createCode()
    {
        $this->checkCode = $this->randString($this->codeNum);
        $this->checkCode = str_pad($this->checkCode, $this->codeNum, '0', STR_PAD_LEFT);
    }

    /**
     * 生成随机数
     * @param int $len
     * @return string
     */
    private function randString($len = 4): string
    {
        $chars = "123456789ABCDEFGHJKMNPQRSTUVWXYZ";
        $string = "";
        for ($i = 0; $i < $len; $i++) {
            $rand = rand(0, strlen($chars) - 1);
            $string .= substr($chars, $rand, 1);
        }
        return $string;
    }

    /**
     * 产生验证码图片
     * @throws \Exception
     */
    public function createImage()
    {
        try {
            $this->checkImage = imagecreate($this->width, $this->height);
            if (empty($this->bgColor)) {
                $bgColor = [rand(200, 255), rand(200, 255), rand(200, 255)];
            } else {
                $bgColor = $this->Color2RGB($this->bgColor);
            }
            $back = imagecolorallocate($this->checkImage, $bgColor[0], $bgColor[1], $bgColor[2]);
            $border = imagecolorallocate($this->checkImage, 0, 0, 0);
            imagefilledrectangle($this->checkImage, 0, 0, $this->width - 1, $this->height - 1, $back);
        } catch (\Exception $exception) {
            Logger::log($exception->getMessage(), $exception->getTraceAsString());
            throw $exception;
        }
    }

    /**
     * 设置图片的干扰像素
     */
    private function setInterferenceColor()
    {
        for ($i = 0; $i <= 4; $i++) {
            $InterferenceColor = imagecolorallocate($this->checkImage, rand(100, 200), rand(100, 200), rand(100, 200));
            $x1 = rand(1, $this->width - 1);
            $x2 = rand(1, $this->width - 1);
            $y1 = rand(1, $this->height - 1);
            $y2 = rand(1, $this->height - 1);
            imageline($this->checkImage, $x1, $y1, $x2, $y2, $InterferenceColor);
            imageline($this->checkImage, $x1 - 1, $y1 - 1, $x2 - 1, $y2 - 1, $InterferenceColor);
        }
        for ($i = 0; $i <= 30; $i++) {
            $InterferenceColor = imagecolorallocate($this->checkImage, rand(30, 150), rand(30, 150), rand(30, 150));
            $x1 = rand(1, $this->width - 1);
            $y1 = rand(1, $this->height - 1);
            imagesetpixel($this->checkImage, $x1, $y1, $InterferenceColor);
            imagesetpixel($this->checkImage, $x1 - 1, $y1 - 1, $InterferenceColor);
            imagesetpixel($this->checkImage, $x1 - 1, $y1, $InterferenceColor);
            imagesetpixel($this->checkImage, $x1, $y1 - 1, $InterferenceColor);
        }

    }

    /**
     * 在验证码图片上逐个画上验证码
     */
    private function writeCheckCodeToImage()
    {
        if (empty($this->fontColor)) {
            $fontColor = [rand(200, 250), rand(200, 250), rand(200, 250)];
        } else {
            $fontColor = $this->Color2RGB($this->fontColor);
        }
        for ($i = 0; $i < $this->codeNum; $i++) {
            $bg_color1 = imagecolorallocate($this->checkImage, $fontColor[0], $fontColor[1], $fontColor[2]);
            $x = floor($this->width / $this->codeNum) * $i + 2;
            $y = $this->height - rand(8, 16);
            if (!empty($this->showColor)) {
                $color = $this->Color2RGB($this->showColor);
                $bg_color2 = imagecolorallocate($this->checkImage, $color[0], $color[1], $color[2]);
            } else {
                $bg_color2 = imagecolorallocate($this->checkImage, rand(50, 150), rand(50, 150), rand(50, 150));
            }
            $array = [-1, 0, 1];
            $p = array_rand($array);
            $an = $array[$p] * mt_rand(1, 20);
            $fontArr = ['nunito.ttf', 'merriweather.ttf', 'roboto.ttf'];
            $p = array_rand($fontArr);
            $font = $fontArr[$p];
            $fontFile = Util::path(ROOT_DIR, '/app/service/font/' . $font);
            imagettftext($this->checkImage, 16, $an, $x + 1, $y + 1, $bg_color1, $fontFile, $this->checkCode[$i]);
            imagettftext($this->checkImage, 16, $an, $x, $y, $bg_color2, $fontFile, $this->checkCode[$i]);
        }
    }

    /**
     * 生成RGB颜色
     * @param $hexColor
     * @return array
     */
    public function Color2RGB($hexColor): array
    {
        if (preg_match('@^rgb\((\d+),\s?(\d+),\s?(\d+)\)$@i', $hexColor, $data)) {
            return [$data[1], $data[2], $data[3]];
        }
        $color = str_replace('#', '', $hexColor);
        if (strlen($color) > 3) {
            $rgb = [
                hexdec(substr($color, 0, 2)),
                hexdec(substr($color, 2, 2)),
                hexdec(substr($color, 4, 2))
            ];
        } else {
            $color = str_replace('#', '', $hexColor);
            $r = substr($color, 0, 1) . substr($color, 0, 1);
            $g = substr($color, 1, 1) . substr($color, 1, 1);
            $b = substr($color, 2, 1) . substr($color, 2, 1);
            $rgb = [
                hexdec($r),
                hexdec($g),
                hexdec($b)
            ];
        }
        return $rgb;
    }

}
