<?php


namespace libs;

use sdopx\lib\Raw;

/**
 * 创建动态按钮
 * Class BtnUtil
 * @package libs
 */
class BtnUtil
{
    /**
     * 生成按钮
     * @param array $buttons
     * @param bool $detail
     * @return Raw
     */
    public static function makeButton(array $buttons, bool $detail = false): Raw
    {
        $html = [];
        $out = [];
        foreach ($buttons as $name => $btn) {
            $code = [];
            if (isset($btn['span'])) {
                $code[] = '<span ';
            } else {
                $code[] = '<a href="' . $btn['url'] . '"';
                $module = [];
                if (!empty($btn['confirm'])) {
                    $module[] = 'confirm';
                    $code[] = ' data-confirm="' . htmlentities($btn['confirm']) . '"';
                }
                if (isset($btn['dialog']) && $btn['dialog']) {
                    $module[] = 'dialog';
                    if (isset($btn['dialog'][0]) && isset($btn['dialog'][1])) {
                        if (!empty($btn['dialog'][0])) {
                            $code[] = ' data-width="' . $btn['dialog'][0] . '"';
                        }
                        if (!empty($btn['dialog'][1])) {
                            $code[] = ' data-height="' . $btn['dialog'][1] . '"';
                        }
                    } else {
                        if (!empty($btn['width'])) {
                            $code[] = ' data-width="' . $btn['width'] . '"';
                        }
                        if (!empty($btn['height'])) {
                            $code[] = ' data-height="' . $btn['height'] . '"';
                        }
                        if (isset($btn['autoSize'])) {
                            $code[] = ' data-auto-size="' . ($btn['autoSize'] ? 'true' : 'false') . '"';
                        }
                    }
                    if (isset($btn['maxMin'])) {
                        $code[] = ' data-maxmin="' . ($btn['maxMin'] ? 'true' : 'false') . '"';
                    }
                }
                if (isset($btn['ajax']) && $btn['ajax']) {
                    $module[] = 'ajax';
                    if (!empty($btn['alert'])) {
                        $code[] = ' data-alert="' . $btn['alert'] . '"';
                    }
                    if (!empty($btn['timeout'])) {
                        $code[] = ' data-load-timeout="' . $btn['timeout'] . '"';
                    }
                }
                if (isset($btn['choice']) && $btn['choice']) {
                    $module[] = 'choice';
                }
                if (isset($btn['new-tab']) && $btn['new-tab']) {
                    $module[] = 'new-tab';
                }
                if (isset($btn['export']) && $btn['export']) {
                    $module[] = 'export';
                }
                if (count($module) > 0) {
                    $code[] = ' yee-module="' . join(' ', $module) . '"';
                }

                if (isset($btn['top-page']) && $btn['top-page']) {
                    $code[] = ' target="_top"';
                } elseif (isset($btn['new-page']) && $btn['new-page']) {
                    $code[] = ' target="_blank"';
                }
                if (isset($btn['method'])) {
                    $code[] = ' data-method="' . $btn['method'] . '"';
                }
                if (isset($btn['success'])) {
                    if (!empty($btn['success'])) {
                        $code[] = ' on-success="' . $btn['success'] . '"';
                    }
                } else {
                    if (!$detail) {
                        if (!isset($btn['reload']) || $btn['reload']) {
                            $code[] = ' on-success="$(\'#list\').emit(\'reload\');"';
                        }
                    } else {
                        $code[] = ' on-success="$(window).emit(\'success\',ret);"';
                    }
                }
            }
            if (!isset($btn['css'])) {
                $code[] = ' class="yee-btn"';
            } else if (isset($btn['css']) && empty($btn['css'])) {
            } else {
                $code[] = ' class="yee-btn ' . $btn['css'] . '"';
            }
            if (!empty($btn['style'])) {
                $code[] = ' style="' . $btn['style'] . '"';
            }
            $code[] = '>';
            if (isset($btn['icon']) && $btn['icon']) {
                $code[] = '<i class="' . $btn['icon'] . '"></i>';
            }
            if (isset($btn['span'])) {
                $code[] = $btn['span'] . '</a>';
                $out[] = join('', $code);
            } else {
                $code[] = $name . '</a>';
                if ($detail) {
                    $html[] = join('', $code);
                } else {
                    if (isset($btn['fold']) && $btn['fold']) {
                        $html[] = join('', $code);
                    } else {
                        $out[] = join('', $code);
                    }
                }
            }
        }
        if ($detail) {
            return new Raw(join("\n", $html));
        }
        if (count($html) <= 1) {
            $out = array_merge($html, $out);
            return new Raw(join("\n", $out));
        }
        $warp[] = '<div class="yee-btn-warp">';
        $warp[] = '<div class="yee-btn-more"><a href="javascript:;" class="yee-btn"><i class="icofont-settings"></i>操作</a></div>';
        $warp[] = '<div class="yee-btn-menu">';
        $warp[] = '<span class="arrow"></span>';
        $warp[] = join("\n", $html);
        $warp[] = '</div></div>';
        $warp[] = join("\n", $out);
        return new Raw(join("\n", $warp));
    }
}