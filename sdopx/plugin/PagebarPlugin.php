<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 18-5-17
 * Time: 上午1:44
 */

namespace sdopx\plugin;


use beacon\Logger;
use sdopx\lib\Outer;
use sdopx\SdopxException;

class PagebarPlugin
{

    /**
     * 处理请求转换
     * @param array $data
     * @param array $param
     * @param string $key
     * @param int $val
     * @return string
     */
    private static function buildQuery(array &$data, array $param, string $key, int $val): string
    {
        $param[$key] = $val;
        $queryStr = http_build_query($param);
        $scheme = isset($data['scheme']) ? $data['scheme'] . '://' : '';
        $host = isset($data['host']) ? $data['host'] : '';
        $port = isset($data['port']) ? ':' . $data['port'] : '';
        $user = isset($data['user']) ? $data['user'] : '';
        $pass = isset($data['pass']) ? ':' . $data['pass'] : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = isset($data['path']) ? $data['path'] : '';
        $query = !empty($queryStr) ? '?' . $queryStr : '';
        $fragment = isset($data['fragment']) ? '#' . $data['fragment'] : '';
        return $scheme . $user . $pass . $host . $port . $path . $query . $fragment;
    }

    /**
     * 渲染数据
     * @param array $args
     * @param Outer $out
     * @throws SdopxException
     */
    public static function render(array $args, Outer $out)
    {
        // Logger::log($args);
        if (!isset($args['info'])) {
            $out->throw('pagebar 控件缺少分页信息  info ');
        }
        $info = $args['info'];
        $key = empty($args['key']) ? $info['keyName'] : $args['url'];
        $link = isset($args['url']) ? $args['url'] : $_SERVER['REQUEST_URI'];
        $data = parse_url($link);
        if (isset($args['fragment'])) {
            $data['fragment'] = $args['fragment'];
        }
        $param = [];
        if (isset($data['query'])) {
            parse_str($data['query'], $param);
        }
        $page = $info['page'];
        $pageCount = $info['pageCount'];
        $start = $page - 2 >= 1 ? $page - 2 : 1;
        $end = $start + 4 <= $pageCount ? $start + 4 : $pageCount;
        if ($end - 4 != $start) {
            $start = $end - 4;
            if ($start < 1) {
                $start = 1;
            }
        }
        $next = isset($args['next']) ? $args['next'] : '下一页';
        $prev = isset($args['prev']) ? $args['prev'] : '上一页';
        if ($page - 1 < 1) {
            $out->html('<a href="javascript:;" class="prev disabled">' . $prev . '</a>');
        } else {
            $out->html('<a href="' . self::buildQuery($data, $param, $key, $page - 1) . '" class="prev">' . $prev . '</a>');
        }
        if (!isset($args['num']) || $args['num'] !== false) {
            if ($start - 1 >= 1) {
                $out->html('<a href="' . self::buildQuery($data, $param, $key, 1) . '" class="num">1</a>');
                if ($start - 2 >= 1) {
                    $out->html('<span class="more">...</span>');
                }
            }
            for ($i = $start; $i <= $end; $i++) {
                if ($page == $i) {
                    $out->html('<b>' . $i . '</b>');
                } else {
                    $out->html('<a href="' . self::buildQuery($data, $param, $key, $i) . '" class="num');
                    $out->html('">' . $i . '</a>');
                }
            }
            if ($end + 1 <= $pageCount) {
                if ($end + 2 <= $pageCount) {
                    $out->html('<span class="more">...</span>');
                }
                $out->html('<a href="' . self::buildQuery($data, $param, $key, $pageCount) . '" class="num">' . $pageCount . '</a>');
            }
        }
        if ($page + 1 > $pageCount) {
            $out->html('<a href="javascript:;" class="next disabled">' . $next . '</a>');
        } else {
            $out->html('<a href="' . self::buildQuery($data, $param, $key, $page + 1) . '" class="next">' . $next . '</a>');
        }

    }
}