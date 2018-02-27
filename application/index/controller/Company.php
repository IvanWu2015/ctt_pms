<?php

//公司管理

namespace app\index\controller;

use \traits\controller\Jump;
use \think\Db;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Company extends Common {

    //首页
    public function index() {
        $this->redirect(url('lists')); //跳转列表页
        exit;
    }

    //公司列表
    public function lists() {
        $username = input('get.name', '', 'addslashes');
        if (!empty($username)) {
            $map['name'] = array('eq', $username);
        }
        $company_list = DB::name('company')
                ->where($map)
                ->paginate(10);
        $this->assign('company_list', $company_list);
        return $this->fetch($this->templatePath);
    }

    //添加公司
    public function add() {
        $company = db('Company');
        //$company_id = intval($this->_GET['dept_id']);
        $company_id = input('company_id', '0', 'intval');
        if (request()->isPost()) {
            $company_data = array(
                'name' => input('name', '', 'strip_tags'), //'公司名称',
                'corporation' => input('corporation', '', 'strip_tags'), //'法人',
                'type' => input('type', '', 'strip_tags'), // '1国企 2私营 3合资 4外资',
                'founding_time' => input('founding_time', '', 'strip_tags'), // '成立时间',
                'employee_numbers' => input('employee_numbers', '', 'strip_tags'), //1 10人以内  2 10-50  3 50-100 4 100-500 6 500-1000 7 1000以上',
                'resister_address' => input('resister_address', '', 'strip_tags'), //'注册地址',
                'work_address' => input('post.work_address', '', 'strip_tags'), //'办公地址',
                'contact_name' => input('post.contact_name', '', 'strip_tags'), // '联系人',
                'contact_number' => input('post.contact_number', '', 'strip_tags'), // '联系电话',
                'fax_number' => input('post.fax_number', '', 'strip_tags'), // '传真电话',
                'tax_number' => input('post.name', '', 'strip_tags'), // '税号',
                'bank_deposit' => input('post.name', '', 'strip_tags'), //'开户行',
                'bank_account' => input('post.name', '', 'strip_tags'), // '银行账号',
            );

            if ($company_id > 0) {
                $company_data['last_uid'] = $this->_G['uid']; //'最后修改人',
                $company_data['last_time'] = date("Y-m-d H:i:s"); //'最后修改时间',
                $company->where(['company_id' => $company_id])->save($company_data);
                $this->success("修改成功", 'Company/detail?id=' . $product_detail['id']);
            } else {
                $company_data['add_uid'] = $this->_G['uid']; //'最后修改人',
                $company_data['add_time'] = date("Y-m-d H:i:s"); //'最后修改时间',
                $company->data($company_data)->insert();
                $this->success("成功添加", 'Company/detail?id=' . $product_detail['id']);
            }
        }
        return $this->fetch($this->templatePath);
    }

    //公司详情页
    public function detail() {
        return $this->fetch($this->templatePath);
    }

}
