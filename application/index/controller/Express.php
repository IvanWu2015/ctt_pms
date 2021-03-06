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
        $name = input('name', '', 'addslashes'); //关键字
        $start_time = input('start_time', '', 'addslashes'); //开始时间
        $end_time = input('end_time', '', 'addslashes'); //结束时间
        
         //删除功能
        $deleted = input('deleted', 0, 'intval');
        $express_id = input('id', 0, 'intval');
        if ($deleted > 0) {
            $express_detail = DB::name('express')->where(['id' => $express_id])->find();
            //管理员或本人可删除
            if ($this->_G['is_admin'] == 1 || $express_detail['add_uid'] == $this->_G['uid']) {
                DB::name('express')->where(['id' => $express_id])->update(['status' => -1]);
                save_log($this->_G['uid'], $this->_G['username']);
                //$this->success('删除成功', 'index/express/lists');
            } else {
                $this->error('没有删除权限');
            }
        }
        
        $map['status'] = array('egt', 0);
        if (!empty($name)) {
            $map['express_name|from_name|from_tel|from_address|to_name|to_tel|to_address|express_number'] = array('like', "%{$name}%");
        }
        if (!empty($start_time)) {
            $map['add_time'] = array('gt', "$start_time");
        }
        if (!empty($end_time)) {
            $map['add_time'] = array('lt', "$end_time");
        }
        $express_list = DB::name('express')
                ->where($map)
                ->order('id DESC')
                ->paginate(50);
        $page = $express_list->render(); // 分页显示输出
        $navtitle = '快递列表';
        $this->assign('navtitle', $navtitle);
        $this->assign('express_list', $express_list);
        $this->assign('page', $page);
        $this->assign('name', $name); //搜索快递名关键字
        $this->assign('number', $number); //搜索联系人关键字
        $this->assign('start_time', $start_time); //搜索快递名关键字
        $this->assign('end_time', $end_time); //搜索联系人关键字
        return $this->fetch($this->templatePath);
    }

    //添加快递
    public function add() {
        $express = db('Express');
        //$express_id = intval($this->_GET['dept_id']);
        $express_id = input('id', '0', 'intval');
        if ($express_id > 0) {
            $navtitle = '编辑快递';
            $expressDetail = $express->where("id = {$express_id}")->find();
        } else {
            $navtitle = '添加快递';
        }

        $company_list = DB::name('company')->field('company_id,name')->select(); //公司列表
        $expressTypeList = getCommonConfigList('express_type'); //快递类型列表
        $employeeTypeList = getCommonConfigList('employee_type'); //快递规模列表
        //表单提交处理
        if (request()->isPost()) {
            switch (input('ac')) {
                //部分匹配查询联系人
                case 'get_contact':
                    $keyword = input('keyword', '', 'addslashes');
                    if (!empty($keyword)) {
                        if (!empty($keyword)) {
                            $map['name|tel'] = array('like', "%{$keyword}%");
                            $contacts_list = DB::name('Contact')
                                    ->where($map)
                                    ->field('id,name,tel,address')
                                    ->select();
                            return $contacts_list; //直接返回 可自动输出JSON数据
                        } else {
                            exit('no keyword');
                        }
                    }
                    break;

                //添加到联系人
                case 'add_contact':
                    $contactData = array(
                        'company_id' => input('company_id', 0, 'intval'), // '报名序号 可用于查询',
                        'name' => input('name', 0, 'addslashes'), //'姓名',
                        'worktitle' => input('worktitle', 0, 'addslashes'), // '职务',
                        'age' => input('age', 0, 'intval'), // '年龄',
                        'sex' => input('sex', 0, 'intval'), // '性别',
                        'native_place' => input('native_place', 0, 'addslashes'), //'籍贯',
                        'introduce' => input('introduce', 0, 'addslashes'), // '简介',
                        'tel' => input('tel', 0, 'addslashes'), //'联系电话',
                        'mail' => input('mail', 0, 'addslashes'), //'电子邮箱',
                        'status' => input('status', 0, 'addslashes'), // '状态 0无 1在职 2离职',
                        'address' => input('address', 0, 'addslashes'), //'详细地址',
                        'remarks' => input('remarks', 0, 'addslashes'), //'备注信息',
                    );

                    $contactData['add_uid'] = $this->_G['uid']; //'最后修改人',
                    $contactData['add_time'] = date("Y-m-d H:i:s"); //'最后修改时间',
                    db('contact')->insert($contactData);
                    $result = ['result' => 'success'];
                    return $result;
                    break;

                //添加快递信息
                case 'add_express':
                default:
                    $expressData = array(
                        'express_name' => input('express_name', '', 'strip_tags'), //'快递公司名称',
                        'express_number' => input('express_number', '', 'strip_tags'), //'快递单号',
                        'express_desc' => input('express_desc', '', 'strip_tags'), // '物品说明',
                        'from_name' => input('from_name', '', 'strip_tags'), // '发件人名称',
                        'from_tel' => input('from_tel', '', 'strip_tags'), // '发件人电话',
                        'from_address' => input('from_address', '', 'strip_tags'), // '发件人地址
                        'to_name' => input('to_name', '', 'strip_tags'), //发件人名称
                        'to_address' => input('to_address', '', 'strip_tags'), //收件人地址
                        'to_tel' => input('to_tel', '', 'strip_tags'), //'发件人电话',
                        'company_id' => input('company_id', '', 'strip_tags'), //'公司ID',
                        'contact_id' => input('contact_id', '', 'strip_tags'), // '联系人ID',
                        'payment' => input('payment', '', 'strip_tags'), // '付款方式 1寄付 2到付',
                        'price' => input('price', '', 'strip_tags'), // '运费',
                        'weight' => input('weight', '', 'strip_tags'), // '重量 单位KG',
                        'remarks' => input('remarks', '', 'strip_tags'), //'其它说明',
                        'add_uid' => input('add_uid', '', 'strip_tags'), // '添加人uid',
                        'add_name' => input('add_name', 0, 'addslashes'), //'添加人名称',
                    );

                    if ($express_id > 0) {
                        $express->where(['id' => $express_id])->update($expressData);
                        $this->success("修改成功", 'Express/lists');
                    } else {
                        $expressData['add_time'] = date("Y-m-d H:i:s"); //添加时间',
                        $express->insert($expressData);
                        $this->success("成功添加", 'Express/lists');
                    }
                    break;
            }
        }
        $this->assign('navtitle', $navtitle);
        $this->assign('company_list', $company_list);
        $this->assign('expressDetail', $expressDetail);
        $this->assign('expressTypeList', $expressTypeList);
        $this->assign('employeeTypeList', $employeeTypeList);
        return $this->fetch($this->templatePath);
    }

    //快递详情页
    public function detail() {
        $id = input('get.id', '0', 'intval');
        $express = db('express');
        if ($id > 0) {
            $expressDetail = $express->where("id = {$id}")->find();
        } else {
            $this->error('请输入ID');
        }
        if (empty($expressDetail)) {
            $this->error('不存在该数据');
        }
        $navtitle = '快递详情页';
        $map = ['id' => $id];
        $contacts_list = DB::name('Contact')
                ->where($map)
                ->select();
        $this->assign('contacts_list', $contacts_list);
        $this->assign('navtitle', $navtitle);
        $this->assign('expressDetail', $expressDetail);
        return $this->fetch($this->templatePath);
    }

}
