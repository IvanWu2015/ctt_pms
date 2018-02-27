<?php

//联系人管理

namespace app\index\controller;

use \traits\controller\Jump;
use \think\Db;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Contacts extends Common {
    
    //首页
    public function index() {
        $this->redirect(url('lists'));//跳转列表页
        exit;
    }

    //联系人列表
    public function lists() {
        $username = input('get.name', '', 'addslashes');
        if (!empty($username)) {
            $map['name'] = array('eq', $username);
        }
        $contact_list = DB::name('Contact')
                ->where($map)
                ->paginate(10);
        $this->assign('company_list', $contact_list);
        return $this->fetch($this->templatePath);
    }

    //添加联系人
    public function add() {
        $contact = db('contact');
        //读取编辑数据n
        $id = input('id', '0', 'intval');
        if($id > 0) {
            $contact_data = $contact->where("id = $id");
        }
        if (request()->isPost()) {
            $contact_data = array(
                'company_id' => input('company_id', 0, 'intval'), // '报名序号 可用于查询',
                'name' => input('name', 0, 'addslashes'), //'姓名',
                'worktitle' => input('name', 0, 'addslashes'), // '职务',
                'age' => input('name', 0, 'intval'), // '年龄',
                'sex' => input('name', 0, 'intval'), // '性别',
                'native_place' => input('name', 0, 'addslashes'), //'籍贯',
                'photo' => input('name', 0, 'addslashes'), // '照片',
                'introduce' => input('name', 0, 'addslashes'), // '简介',
                'tel' => input('name', 0, 'addslashes'), //'联系电话',
                'mail' => input('name', 0, 'addslashes'), //'电子邮箱',
                'status' => input('name', 0, 'addslashes'), // '结果 0无 1在职 2离职',
                'address' => input('name', 0, 'addslashes'), //'详细地址',
            );

            if ($id > 0) {
                $contact_data['last_uid'] = $this->_G['uid']; //'最后修改人',
                $contact_data['last_time'] = date("Y-m-d H:i:s"); //'最后修改时间',
                $contact->where(['id' => $contact_id])->save($contact_data);
                $this->success("修改成功", 'Contacts/detail?id=' . $product_detail['id']);
            } else {
                $contact_data['add_uid'] = $this->_G['uid']; //'最后修改人',
                $contact_data['add_time'] = date("Y-m-d H:i:s"); //'最后修改时间',
                $contact->data($contact_data)->insert();
                $this->success("添加成功", 'Contacts/detail?id=' . $product_detail['id']);
            }
        }
        return $this->fetch($this->templatePath);
    }

    //联系人详情
    public function detail() {
        return $this->fetch($this->templatePath);
    }

}
