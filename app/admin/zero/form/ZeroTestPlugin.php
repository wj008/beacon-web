<?php

namespace app\admin\zero\form;

/**
* 测试
* Created by Beacon AI Tool2.0.
* User: wj008
* Web: www.wj008.net
* Date: 2018/12/06
* Time: 21:32:18
* 注意：该代码由工具生成，不要在此处修改任何代码，将会被覆盖，如要修改请在应用 form目录中创建同名类并继承该生成类进行调整
*/

use beacon\Form;

class ZeroTestPlugin extends Form
{
    public $title='测试';
    public $template='ZeroTest.plugin.tpl';

    protected function load(){
        return [
                'name' => [
                    'label' => '名字',
                    'type' => 'text',
                    'var-type' => 'string',
                ],
                'gender' => [
                    'label' => '性别',
                    'type' => 'text',
                    'var-type' => 'string',
                ],
                'age' => [
                    'label' => '年龄',
                    'type' => 'text',
                    'var-type' => 'string',
                ],
                'date' => [
                    'label' => '日期',
                    'type' => 'date',
                    'var-type' => 'string',
                ],
        ];
    }
}