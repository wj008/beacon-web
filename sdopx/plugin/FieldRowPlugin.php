<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 18-12-6
 * Time: 上午1:28
 */

namespace sdopx\plugin;


use beacon\Field;
use sdopx\lib\Outer;

/**
 * 输出控件行插件
 * Class FieldRowPlugin
 * @package sdopx\plugin
 */
class FieldRowPlugin
{
    //合并行
    private static function inline(Field $field, Outer $out)
    {
        if ($field->prev) {
            self::inline($field->prev, $out);
        }
        if ($field->type == 'button') {
            InputPlugin::render(['field' => $field], $out);
            if (!empty($field->tips)) {
                $out->html('<span class="yee-field-tips ' . $field->type . '">' . $field->tips . '</p>');
            }
        } else {
            $out->html('<div class="yee-row-inline');
            if ($field->viewHide) {
                $out->html(' none');
            }
            $out->html('" id="row_' . $field->boxId . '">' . PHP_EOL);
            if (isset($field->label[0]) && $field->label[0] != '!') {
                $out->html('<label class="inline-label">' . htmlspecialchars($field->label) . '：</label>' . PHP_EOL);
            }
            $out->html('<span style="margin-right: 10px">' . PHP_EOL);
            InputPlugin::render(['field' => $field], $out);
            if (!empty($field->tips)) {
                $out->html('<span class="yee-field-tips ' . $field->type . '">' . $field->tips . '</span>');
            }
            $out->html('</span>');
            $out->html('</div>');
        }
        if ($field->next) {
            self::inline($field->next, $out);
        }
    }

    //渲染行
    public static function render(array $param, Outer $out)
    {
        $field = isset($param['field']) ? $param['field'] : new Field(null, $param);
        //容器
        if ($field->type == 'container') {
            InputPlugin::render(['field' => $field], $out);
            return;
        }
        if ($field->type == 'line') {
            $out->html('<div class="yee-line">');
            $out->html('<label class="line-label">' . htmlspecialchars($field->label) . '</label>');
            if (!empty($field->tips)) {
                $out->html('<span style="margin-left: 15px;" class="yee-field-tips ' . $field->type . '">' . $field->tips . '</span>');
            }
            $out->html('</div>');
            return;
        }
        $out->html('<div class="yee-row');
        if ($field->viewHide) {
            $out->html(' none');
        }
        $out->html('" id="row_' . $field->boxId . '">' . PHP_EOL);
        $out->html('<label class="row-label">' . htmlspecialchars($field->label) . '：</label>' . PHP_EOL);
        $out->html('<div class="row-cell">' . PHP_EOL);
        if ($field->prev) {
            self::inline($field->prev, $out);
        }
        InputPlugin::render(['field' => $field], $out);
        $out->html(PHP_EOL);
        if ($field->next) {
            self::inline($field->next, $out);
        }
        if (!empty($field->dataValRule) || !empty($field->dataValGroup)) {
            $out->html('<span id="');
            $out->text($field->boxId);
            $out->html('-validation"></span>' . PHP_EOL);
        }
        if (!empty($field->tips)) {
            $out->html('<p class="yee-field-tips ' . $field->type . '">' . $field->tips . '</p>' . PHP_EOL);
        }
        $out->html('</div>' . PHP_EOL);
        $out->html('</div>');
    }
}