<?php


namespace libs;


use beacon\core\Config;
use beacon\core\Util;

class Helper
{
    /**
     * 转换为数组
     * @param array|string $string
     * @param array $def
     * @return array
     */
    public static function convertArray(array|string|null $string, array $def = []): array
    {
        if ($string === null) {
            return $def;
        }
        if (is_array($string)) {
            return $string;
        }
        $string = trim($string);
        if (empty($string)) {
            return $def;
        }
        if (!Util::isJson($string)) {
            return $def;
        }
        return json_decode($string, true);
    }

    public static function changeUrl(?string $path): string
    {
        if (empty($path)) {
            return '';
        }
        $host = Config::get('app.host', 'http://' . $_SERVER['SERVER_NAME']);
        if (preg_match('@^(\/upfiles|\/common)@i', $path)) {
            return $host . $path;
        }
        return $path;
    }

    public static function file($data): array
    {
        if (is_string($data) && Util::isJson($data)) {
            $data = json_decode($data, true);
        }
        if (!is_array($data)) {
            if (is_string($data) && !empty($data)) {
                $data = [$data];
            } else {
                $data = [];
            }
        }
        $temp = [];
        foreach ($data as $item) {
            if ($item === null || $item === '' || $item === 'null') {
                continue;
            }
            if (is_string($item)) {
                $item = ['url' => $item, 'name' => basename($item)];
            }
            if (!is_array($item)) {
                continue;
            }
            if (empty($item['url'])) {
                continue;
            }
            $item['url'] = Helper::changeUrl($item['url']);
            if (empty($item['name']) && !empty($item['localname'])) {
                $item['name'] = $item['localname'];
            }
            if (empty($item['name']) && !empty($item['url'])) {
                $item['name'] = basename($item['url']);
            }
            $temp[] = $item;
        }
        return $temp;
    }

    public static function hideNum(string $code = ''): string
    {
        if (empty($code) || strlen($code) == 1) {
            return $code;
        }
        $len = strlen($code);
        if ($len == 2) {
            return $code[0] . '*';
        } elseif ($len == 3) {
            return $code[0] . '*' . $code[2];
        } elseif ($len == 4) {
            return $code[0] . '**' . $code[3];
        } elseif ($len == 5) {
            return $code[0] . $code[1] . '**' . $code[4];
        } elseif ($len == 6) {
            return $code[0] . $code[1] . '**' . $code[4] . $code[5];
        } else if ($len > 6 && $len <= 8) {
            return preg_replace_callback('@^(.{2})(.*)(.{3})$@', function ($m) {
                return $m[1] . str_pad('', strlen($m[2]), '*') . $m[3];
            }, $code);
        } else if ($len > 8 && $len <= 11) {
            return preg_replace_callback('@^(.{3})(.*)(.{4})$@', function ($m) {
                return $m[1] . str_pad('', strlen($m[2]), '*') . $m[3];
            }, $code);
        } else if ($len > 11) {
            return preg_replace_callback('@^(.{4})(.*)(.{4})$@', function ($m) {
                return $m[1] . str_pad('', strlen($m[2]), '*') . $m[3];
            }, $code);
        }
        return $code;
    }

}