<?php

namespace app\admin\zero\form;

/**
* 商品订单
* Created by Beacon AI Tool2.0.
* User: wj008
* Web: www.wj008.net
* Date: 2018/12/06
* Time: 21:32:18
* 注意：该代码由工具生成，不要在此处修改任何代码，将会被覆盖，如要修改请在应用 form目录中创建同名类并继承该生成类进行调整
*/

use beacon\Form;
use beacon\Request;
use beacon\DB;

class ZeroCommodityOrderForm extends Form
{
    public $title='商品订单';
    public $tbName='@pf_commodity_order';
    public $template='ZeroCommodityOrder.form.tpl';

    public function __construct(string $type = ''){
        parent::__construct($type);
        if($this->isEdit()){
            $this->addHideBox('id', Request::get('id:i', 0));
        }
    }

    protected function load(){
        return [
                'allow' => [
                    'label' => '允许',
                    'type' => 'check',
                    'after-text' => '个',
                    'default' => true,
                    'var-type' => 'bool',
                ],
                'lock' => [
                    'label' => '锁定',
                    'type' => 'check',
                    'default' => false,
                    'var-type' => 'bool',
                ],
                'name' => [
                    'label' => '名称',
                    'type' => 'text',
                    'view-merge' => -1,
                    'var-type' => 'string',
                ],
                'dropDownBox' => [
                    'label' => '下拉框',
                    'type' => 'select-dialog',
                    'data-btn-text' => '选择',
                    'text-func' => function($value=0){
                        return  DB::getOne('select `name` form `@pf_table` where id=?',$value);
                    },
                    'var-type' => 'string',
                ],
                'cover' => [
                    'label' => '封面',
                    'type' => 'up-image',
                    'data-type' => 'image',
                    'data-extensions' => 'txt,doc,docx,zip,rar,jpg,jpeg,bmp,gif,xls,xlsx,pdf,png',
                    'data-url' => '/service/upload',
                    'var-type' => 'string',
                ],
                'plugin' => [
                    'label' => '插件',
                    'type' => 'container',
                    'plug-name' => 'app\\admin\\zero\\form\\ZeroTestPlugin',
                    'mode' => 'multiple',
                    'view-remove-btn' => true,
                    'var-type' => 'string',
                ],
                'content' => [
                    'label' => '内容',
                    'type' => 'xh-editor',
                    'default' => '1',
                    'data-up-link-url' => '/service/xh_upfile?immediate=1',
                    'data-up-img-url' => '/service/xh_upfile?immediate=1',
                    'data-up-link-ext' => 'txt,doc,docx,zip,rar,xls,xlsx,pdf',
                    'data-up-img-ext' => 'jpg,jpeg,bmp,gif,png',
                    'data-skin' => 'default',
                    'data-tools' => 'full',
                    'var-type' => 'string',
                ],
        ];
    }
}