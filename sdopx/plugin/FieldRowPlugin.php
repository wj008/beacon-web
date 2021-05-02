<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 18-12-6
 * Time: 上午1:28
 */

namespace sdopx\plugin;


use beacon\core\Field;
use beacon\core\Logger;
use beacon\widget\Button;
use beacon\widget\Container;
use beacon\widget\Line;
use beacon\widget\Single;
use sdopx\lib\Outer;
use sdopx\SdopxException;

/**
 * 输出控件行插件
 * Class FieldRowPlugin
 * @package sdopx\plugin
 */
class FieldRowPlugin
{
    /**
     * 合并行
     * @param Field $field
     * @param Outer $out
     */
    private static function inline(Field $field, Outer $out)
    {
        if ($field->prev) {
            self::inline($field->prev, $out);
        }
        if ($field instanceof Button) {
            InputPlugin::render(['field' => $field], $out);
            if (!empty($field->prompt)) {
                $out->html('<span class="yee-field-prompt">' . $field->prompt . '</p>');
            }
        } else {
            $out->html('<div class="yee-row-inline');
            $out->html('" id="row_' . $field->boxId . '">' . PHP_EOL);
            if (isset($field->label[0]) && $field->label[0] != '!') {
                $out->html('<label class="inline-label">' . htmlspecialchars($field->label) . '：</label>' . PHP_EOL);
            }
            $out->html('<span style="margin-right: 10px">' . PHP_EOL);
            InputPlugin::render(['field' => $field], $out);
            if (!empty($field->prompt)) {
                $out->html('<span class="yee-field-prompt">' . $field->prompt . '</span>');
            }
            $out->html('</span>');
            $out->html('</div>');
        }

        if ($field->next) {
            self::inline($field->next, $out);
        }
    }

    /**
     * 渲染行
     * @param array $param
     * @param Outer $out
     * @throws SdopxException
     */
    public static function render(array $param, Outer $out)
    {
        if (!isset($param['field'])) {
            $out->throw('field 参数缺失');
        }
        /** @var Field $field */
        $field = $param['field'];
        //容器
        if ($field instanceof Container || $field instanceof Single) {
            InputPlugin::render(['field' => $field], $out);
            return;
        }
        if ($field instanceof Line) {
            $out->html('<div class="yee-line">');
            $out->html('<label class="line-label">' . htmlspecialchars($field->label) . '</label>');
            if (!empty($field->prompt)) {
                $out->html('<span style="margin-left: 15px;" class="yee-field-prompt">' . $field->prompt . '</span>');
            }
            $out->html('</div>');
            return;
        }

        $out->html('<div class="yee-row" id="row_' . $field->boxId . '">' . PHP_EOL);
        if ($field->star) {
            $out->html('<label class="row-label"><em></em>' . htmlspecialchars($field->label) . '：</label>' . PHP_EOL);
        } else {
            $out->html('<label class="row-label">' . htmlspecialchars($field->label) . '：</label>' . PHP_EOL);
        }
        $out->html('<div class="row-cell">' . PHP_EOL);
        if ($field->prev) {
            self::inline($field->prev, $out);
        }
        InputPlugin::render(['field' => $field], $out);
        $out->html(PHP_EOL);
        if ($field->next) {
            self::inline($field->next, $out);
        }
        if (!empty($field->valid)) {
            $out->html('<span id="');
            $out->text($field->boxId);
            $out->html('-validation"></span>' . PHP_EOL);
        }
        if (!empty($field->prompt)) {
            $out->html('<p class="yee-field-prompt">' . $field->prompt . '</p>' . PHP_EOL);
        }
        $out->html('</div>' . PHP_EOL);
        $out->html('</div>');
    }
}