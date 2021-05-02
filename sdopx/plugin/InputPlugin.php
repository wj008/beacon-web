<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 2018/1/2
 * Time: 21:40
 */

namespace sdopx\plugin;


use beacon\core\Field;
use beacon\core\Form;
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
            $args = Field::getTagArgs($param);
            $field->setting($args);
            if (isset($args['value'])) {
                $field->bindValue($args['value']);
            }
        } else if (isset($param['form']) && $param['form'] instanceof Form && !empty($param['name'])) {
            $field = $param['form']->getField($param['name']);
            if (empty($field)) {
                $out->throw('form is not found the field：' . $param['name']);
            }
            unset($param['form']);
            unset($param['name']);
            $args = Field::getTagArgs($param);
            $field->setting($args);
            if (isset($args['value'])) {
                $field->bindValue($args['value']);
            }
        } else {
            try {
                $field = Field::create($param);
            } catch (\Exception $e) {
                $out->throw($e->getMessage());
            }
        }
        $out->html($field->render());
    }
}