<?php

namespace app\admin\zero\form;

/**
* 死得快
* Created by Beacon AI Tool2.0.
* User: wj008
* Web: www.wj008.net
* Date: 2018/12/06
* Time: 21:32:18
* 注意：该代码由工具生成，不要在此处修改任何代码，将会被覆盖，如要修改请在应用 form目录中创建同名类并继承该生成类进行调整
*/

use beacon\Form;

class ZeroDieFastSearch extends Form
{
    public $title='死得快';

    protected function load(){
        return [
                'allow' => [
                    'label' => '允许',
                    'type' => 'select',
                    'names' => '[]',
                    'tab-index' => 'base',
                    'var-type' => 'string',
                    'header' => [
                            '',
                            '全部'
                        ],
                    'options' => [
                            [
                                'value' => '1',
                                'text' => 'a',
                                'tips' => ''
                            ],
                            [
                                'value' => '2',
                                'text' => '2',
                                'tips' => ''
                            ]
                        ],
                ],
                'lock' => [
                    'label' => '锁定',
                    'type' => 'select',
                    'names' => '[]',
                    'tab-index' => 'base',
                    'var-type' => 'string',
                    'header' => [
                            '',
                            '全部'
                        ],
                    'options' => [
                            [
                                'value' => '1',
                                'text' => '锁定',
                                'tips' => ''
                            ],
                            [
                                'value' => '0',
                                'text' => '正常',
                                'tips' => ''
                            ]
                        ],
                ],
                'dropDownBox' => [
                    'label' => '下拉框',
                    'type' => 'select',
                    'tab-index' => 'base',
                    'var-type' => 'string',
                    'options' => [
                            [
                                'value' => '1',
                                'text' => '选项1',
                                'tips' => ''
                            ],
                            [
                                'value' => '2',
                                'text' => '选项2',
                                'tips' => ''
                            ],
                            [
                                'value' => '3',
                                'text' => '选项3',
                                'tips' => ''
                            ],
                            [
                                'value' => '4',
                                'text' => '选项4',
                                'tips' => ''
                            ]
                        ],
                ],
        ];
    }
}