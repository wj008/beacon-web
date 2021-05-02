<?php

use \beacon\core\Util;

return [
    //字段默认样式
    'field_default' => [
        'class' => function ($fieldType) {
            return match ($fieldType) {
                'Button' => 'form-btn',
                'Hidden' => '',
                'CheckGroup', 'RadioGroup', 'Container', 'Single' => Util::camelToAttr($fieldType),
                default => 'form-inp ' . Util::camelToAttr($fieldType),
            };
        },
        'inp-class' => function ($fieldType) {
            return match ($fieldType) {
                'CheckGroup', 'RadioGroup' => 'form-inp',
                default => null,
            };
        }
    ],
];