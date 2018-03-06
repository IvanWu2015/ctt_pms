<?php

//快递记录

namespace app\index\controller;

use \traits\controller\Jump;
use \think\Db;
use Page;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Express extends Common {

    //首页
    public function index() {
        $this->redirect(url('lists')); //跳转列表页
        exit;
    }

    //快递列表
    public function lists() {
        $name = input('name', '', 'addslashes');
        $contact_name = input('contact_name', '', 'addslashes');
        if (!empty($name)) {
            $map['name'] = array('like', "%{$name}%");
        }
        if (!empty($contact_name)) {
            $map['contact_name'] = array('like', "%{$contact_name}%");
        }
        $express_list = DB::name('express')
                ->where($map)
                ->paginate(10);
        $page = $express_list->render(); // 分页显示输出
        $navtitle = '快递列表';
        $this->assign('navtitle', $navtitle);
        $this->assign('express_list', $express_list);
        $this->assign('page', $page);
        $this->assign('name', $name); //搜索快递名关键字
        $this->assign('contact_name', $contact_name); //搜索联系人关键字
        return $this->fetch($this->templatePath);
    }

    //添加快递
    public function add() {
        $express = db('Express');
        //$express_id = intval($this->_GET['dept_id']);
        $express_id = input('id', '0', 'intval');
        if ($express_id > 0) {
            $navtitle = '编辑快递';
            $expressDetail = $express->where("express_id = {$express_id}")->find();
        } else {
            $navtitle = '添加快递';
        }

        $expressTypeList = getCommonConfigList('express_type'); //快递类型列表
        $employeeTypeList = getCommonConfigList('employee_type'); //快递规模列表
        if (request()->isPost()) {
            $expressData = array(
                'name' => input('name', '', 'strip_tags'), //'快递名称',
                'corporation' => input('corporation', '', 'strip_tags'), //'法人',
                'type' => input('type', '', 'strip_tags'), // '1国企 2私营 3合资 4外资',
                'founding_time' => input('founding_time', '', 'strip_tags'), // '成立时间',
                'employee_numbers' => input('employee_numbers', '', 'strip_tags'), //1 10人以内  2 10-50  3 50-100 4 100-500 6 500-1000 7 1000以上',
                'resister_address' => input('resister_address', '', 'strip_tags'), //'注册地址',
                'work_address' => input('post.work_address', '', 'strip_tags'), //'办公地址',
                'contact_name' => input('post.contact_name', '', 'strip_tags'), // '联系人',
                'contact_number' => input('post.contact_number', '', 'strip_tags'), // '联系电话',
                'fax_number' => input('post.fax_number', '', 'strip_tags'), // '传真电话',
                'tax_number' => input('post.tax_number', '', 'strip_tags'), // '税号',
                'bank_deposit' => input('post.bank_deposit', '', 'strip_tags'), //'开户行',
                'bank_account' => input('post.bank_account', '', 'strip_tags'), // '银行账号',
                'scope' => input('scope', 0, 'addslashes'), //'业务范围',
                'remarks' => input('remarks', 0, 'addslashes'), //'备注信息',
            );

            if ($express_id > 0) {
                $expressData['last_uid'] = $this->_G['uid']; //'最后修改人',
                $expressData['last_time'] = date("Y-m-d H:i:s"); //'最后修改时间',
                $express->where(['express_id' => $express_id])->update($expressData);
                $this->success("修改成功", 'Express/lists');
            } else {
                $expressData['add_uid'] = $this->_G['uid']; //'最后修改人',
                $expressData['add_time'] = date("Y-m-d H:i:s"); //'最后修改时间',
                $express->insert($expressData);
                $this->success("成功添加", 'Express/lists');
            }
        }
        $this->assign('navtitle', $navtitle);
        $this->assign('expressDetail', $expressDetail);
        $this->assign('expressTypeList', $expressTypeList);
        $this->assign('employeeTypeList', $employeeTypeList);
        return $this->fetch($this->templatePath);
    }

    //快递详情页
    public function detail() {
        $express_id = input('get.id', '0', 'intval');
        $express = db('express');
        if ($express_id > 0) {
            $expressDetail = $express->where("express_id = {$express_id}")->find();
        } else {
            $this->error('请输入ID');
        }
        if (empty($expressDetail)) {
            $this->error('不存在该数据');
        }
        $navtitle = '快递详情页';
        $map = ['express_id' => $express_id];
        $contacts_list = DB::name('Contact')
                ->where($map)
                ->select();
        $this->assign('contacts_list', $contacts_list);
        $this->assign('navtitle', $navtitle);
        $this->assign('expressDetail', $expressDetail);
        return $this->fetch($this->templatePath);
    }

}
