<?php

//联系人管理

namespace app\index\controller;

use \traits\controller\Jump;
use \think\Db;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Contacts extends Common {

    //联系人列表
    public function lists() {
        $username = input('get.name', '', 'addslashes');
        if (!empty($username)) {
            $map['name'] = array('eq', $username);
        }
        $company_list = DB::name('Contact')
                ->where($map)
                ->paginate(10);
        $this->assign('company_list', $company_list);
        return $this->fetch($this->templatePath);
    }

    //添加联系人
    public function add() {
        $company = db('contact');
        //$company_id = intval($_GET['dept_id']);
        $company_id = input('company_id', '0', 'intval');
        if (IS_POST) {
            $company_data = array(
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

            if ($company_id > 0) {
                $company_data['last_uid'] = $_G['uid']; //'最后修改人',
                $company_data['last_time'] = date("Y-m-d H:i:s"); //'最后修改时间',
                $company->where(['company_id' => $company_id])->save($company_data);
                $this->success("成功添加", 'Product/detail?id=' . $product_detail['id']);
            } else {
                $company_data['add_uid'] = $_G['uid']; //'最后修改人',
                $company_data['add_time'] = date("Y-m-d H:i:s"); //'最后修改时间',
                $company->data($company_data)->insert();
            }
        }
        return $this->fetch($this->templatePath);
    }

    //联系人详情
    public function detail() {
        return $this->fetch($this->templatePath);
    }

}
