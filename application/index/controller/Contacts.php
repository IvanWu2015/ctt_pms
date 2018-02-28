<?php

//联系人管理

namespace app\index\controller;

use \traits\controller\Jump;
use \think\Db;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Contacts extends Common {

    //首页
    public function index() {
        $this->redirect(url('lists')); //跳转列表页
        exit;
    }

    //联系人列表
    public function lists() {
        $username = input('get.name', '', 'addslashes');
        if (!empty($username)) {
            $map['name'] = array('eq', $username);
        }
        $contacts_list = DB::name('Contact')
                ->where($map)
                ->paginate(20);
        $navtitle = '联系人列表' . $this->navtitle;
        $this->assign('contacts_list', $contacts_list);
        return $this->fetch($this->templatePath);
    }

    //添加联系人
    public function add() {
        $companyList = DB::name('company')->field('company_id,name')->select(); //公司列表 
        //todo:将来公司增加 此处不能一并读取 而另加一查询功能
        $contact = db('contact');
        //读取编辑数据n
        $id = input('id', '0', 'intval');
        if ($id > 0) {
            $contactData = $contact->where("id = $id")->find();
        }
        if (request()->isPost()) {
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
            );
            $file = request()->file('photo');
            if ($file) {
                $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
                $contactData['photo'] = './uploads/' . $info->getSaveName();
            }


            if ($id > 0) {
                $contactData['last_uid'] = $this->_G['uid']; //'最后修改人',
                $contactData['last_time'] = date("Y-m-d H:i:s"); //'最后修改时间',
                $contact->where(['id' => $contact_id])->save($contactData);
                $this->success("修改成功", 'Contacts/detail?id=' . $contactData['id']);
            } else {
                $contactData['add_uid'] = $this->_G['uid']; //'最后修改人',
                $contactData['add_time'] = date("Y-m-d H:i:s"); //'最后修改时间',
                $contact->data($contactData)->insert();
                $this->success("添加成功", 'Contacts/detail?id=' . $contactData['id']);
            }
        }
        $this->assign('contactData', $contactData);
        $this->assign('companyList', $companyList);
        return $this->fetch($this->templatePath);
    }

    //联系人详情
    public function detail() {
        $contact_id = input('get.id', '0', 'intval');
        $contact = db('contact');
        if ($contact_id > 0) {
            $contactDetail = $contact->where("id = {$contact_id}")->find();
        } else {
            $this->error('请输入ID');
        }
        if (empty($contactDetail)) {
            $this->error('不存在该数据');
        }
        $navtitle = '联系人详情页';
        $this->assign('navtitle', $navtitle);
        $this->assign('contactDetail', $contactDetail);
        return $this->fetch($this->templatePath);
    }

}
