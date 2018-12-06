<?php

namespace app\admin\zero\controller;

/**
* 商品订单
* Created by Beacon AI Tool 2.0.
* User: wj008
* Web: www.wj008.net
* Date: 2018/12/06
* Time: 21:32:18
* 注意：该代码由代码工具生成，不要在此处修改任何代码，将会被覆盖，如要修改请在应用 controller目录中创建同名类并继承该生成类进行调整
*/

use app\admin\controller\ZeroController;
use beacon\SqlSelector;
use app\admin\zero\form\ZeroCommodityOrderSearch;

class ZeroCommodityOrder extends ZeroController
{
    public function indexAction(){
        return parent::indexAction();
    }

    //为ZeroController所需的配置信息
    protected function zeroLoad(){ 
        return [
            'actionForm' => 'app\\admin\\zero\\form\\ZeroCommodityOrderForm',
            'tbName' => '@pf_commodity_order',
            'pageSize' => 20,
            'template' => 'ZeroCommodityOrder.tpl',
            'templateHook' => 'ZeroCommodityOrder.hook.tpl'
        ];
    }

    //为ZeroController所需的条件查询
    protected function zeroWhere(SqlSelector $selector){ 
        $allow  =  $this->param('allow');
        $selector->search('`allow` = ?', $allow , 2);

        $lock  =  $this->param('lock');
        $selector->search('`lock` = ?', $lock , 2);

        $dropDownBox  =  $this->param('dropDownBox');
        $selector->search('`dropDownBox` like concat(\'%\',?,\'%\')', $dropDownBox , 2);

        return $selector;
    }

    //为ZeroController所需的自动查询器
    protected function zeroSelector(){ 
        $selector = new SqlSelector('@pf_commodity_order');
        $param = $this->param();
        $selector->setTemplate('select * from @pf_commodity_order {if !empty($where)}{$where|raw} and 1=1{else} where 1=1{/if}', $param);
        $this->zeroWhere($selector);
        return $selector;
    }
    //注册搜索表单数据
    protected function zeroForSearch(){ 
        $search = new ZeroCommodityOrderSearch();
        $this->assign('search',$search);
        return $search;
    }

    //公开 add 方法
    public function addAction(){
        return parent::addAction();
    }

    //公开 sort 方法
    public function sortAction(){
        return parent::sortAction();
    }

    //公开 toggleAllow 方法
    public function toggleAllowAction(){
        return parent::toggleAllowAction();
    }

    //公开 edit 方法
    public function editAction(){
        return parent::editAction();
    }

    //公开 delete 方法
    public function deleteAction(){
        return parent::deleteAction();
    }

    //公开 deleteChoice 方法
    public function deleteChoiceAction(){
        return parent::deleteChoiceAction();
    }

    //公开 allowChoice 方法
    public function allowChoiceAction(){
        return parent::allowChoiceAction();
    }

    //公开 revokeChoice 方法
    public function revokeChoiceAction(){
        return parent::revokeChoiceAction();
    }
}