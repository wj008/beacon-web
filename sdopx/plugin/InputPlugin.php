<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 2018/1/2
 * Time: 21:40
 */

namespace sdopx\plugin;


use beacon\Field;
use beacon\Form;
use sdopx\lib\Outer;

/**
 * 输出控件的插件
 * Class InputPlugin
 * @package sdopx\plugin
 */
class InputPlugin
{
    public static function render(array $param, Outer $out)
    {
        if (isset($param['field']) && $param['field'] instanceof Field) {
            $field = $param['field'];
            unset($param['field']);
        } else if (isset($param['form']) && $param['form'] instanceof Form && !empty($param['form'])) {
            $field = $param['form']->getField($param['name']);
            if (empty($field)) {
                $out->throw('form is not found the field：' . $param['name']);
            }
            unset($param['form']);
            unset($param['name']);
        } else {
            $field = new Field(null, $param);
        }
        $code = [];
        if ($field->beforeText) {
            $code[] = '<span class="before"> ' . htmlspecialchars($field->beforeText) . '</span>';
        }
        if ($field->type == 'check') {
            $code[] = '<label>';
        }
        $code[] = $field->code($param);
        if ($field->afterText) {
            $code[] = '<span class="after"> ' . htmlspecialchars($field->afterText) . '</span>';
        }
        if ($field->type == 'check') {
            $code[] = '</label>';
        }
        $out->html(join('', $code));
    }

}