<?php

//联系人管理

namespace app\index\controller;

use \traits\controller\Jump;
use \think\Db;
use Page;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Contacts extends Common {

    //首页
    public function index() {
        $this->redirect(url('lists')); //跳转列表页
        exit;
    }

    //联系人列表
    public function lists() {
        //删除联系人
        $deleted = input('deleted', 0, 'intval');
        $contact_id = input('id', 0, 'intval');
        if ($deleted > 0) {
            $contact_detail = DB::name('contact')->where(['id' => $contact_id])->find();
            //管理员或本人可删除
            if ($this->_G['is_admin'] == 1 || $contact_detail['add_uid'] == $this->_G['uid']) {
                DB::name('contact')->where(['id' => $contact_id])->update(['status' => -1]);
                save_log($this->_G['uid'], $this->_G['username']);
                //$this->success('删除成功', 'index/contact/lists');
            } else {
                $this->error('没有删除权限');
            }
        }

        $name = input('name', '', 'addslashes');
        $map['status'] = array('egt', 0);
        if (!empty($name)) {
            $map['name'] = array('like', "%{$name}%");
        }
        $tel = input('tel', '', 'addslashes');
        if (!empty($tel)) {
            $map['tel'] = array('like', "%{$tel}%");
        }
        $contacts_list = DB::name('Contact')
                ->where($map)
                ->order('id DESC')
                ->paginate(20);
        $page = $contacts_list->render(); // 分页显示输出
        $navtitle = '联系人列表' . $this->navtitle;
        $this->assign('navtitle', $navtitle);
        $this->assign('page', $page);
        $this->assign('name', $name);
        $this->assign('tel', $tel);
        $this->assign('contacts_list', $contacts_list);
        return $this->fetch($this->templatePath);
    }

    //添加联系人
    public function add() {
        $contact_id = input('id', '0', 'intval');
        $companyList = DB::name('company')->field('company_id,name')->select(); //公司列表 
        //todo:将来公司增加 此处不能一并读取 而另加一查询功能
        $contact = db('contact');
        //读取编辑数据n
        $id = input('id', '0', 'intval');
        if ($id > 0) {
            $navtitle = '编辑联系人';
            $contactData = $contact->where("id = $id")->find();
        } else {
            $navtitle = '添加联系人';
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
                'remarks' => input('remarks', 0, 'addslashes'), //'备注信息',
            );
            $file = request()->file('photo');
            if ($file) {
                $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
                $contactData['photo'] = DS . 'upload' . DS . $info->getSaveName();
            }
            //编辑
            if ($id > 0) {
                $contactData['last_uid'] = $this->_G['uid']; //'最后修改人',
                $contactData['last_time'] = date("Y-m-d H:i:s"); //'最后修改时间',
                $contact->where(['id' => $contact_id])->update($contactData);
                $this->success("修改成功", 'Contacts/lists');
                //添加
            } else {
                $contactData['add_uid'] = $this->_G['uid']; //'最后修改人',
                $contactData['add_time'] = date("Y-m-d H:i:s"); //'最后修改时间',
                $contact->insert($contactData);
                $contact_id = $contact->getLastInsID();
                $this->success("添加成功", 'Contacts/lists');
            }
        }

        $this->assign('navtitle', $navtitle);
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
