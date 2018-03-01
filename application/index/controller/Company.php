<?php

//公司管理

namespace app\index\controller;

use \traits\controller\Jump;
use \think\Db;
use Page;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Company extends Common {

    //首页
    public function index() {
        $this->redirect(url('lists')); //跳转列表页
        exit;
    }

    //公司列表
    public function lists() {
        $name = input('name', '', 'addslashes');
        $contact_name = input('contact_name', '', 'addslashes');
        if (!empty($name)) {
            $map['name'] = array('like', "%{$name}%");
        }
        if (!empty($contact_name)) {
            $map['contact_name'] = array('like', "%{$contact_name}%");
        }
        $company_list = DB::name('company')
                ->where($map)
                ->paginate(10);
        $page = $company_list->render(); // 分页显示输出
        $this->assign('company_list', $company_list);
        $this->assign('page', $page);
        $this->assign('name', $name); //搜索公司名关键字
        $this->assign('contact_name', $contact_name); //搜索联系人关键字
        return $this->fetch($this->templatePath);
    }

    //添加公司
    public function add() {
        $company = db('Company');
        //$company_id = intval($this->_GET['dept_id']);
        $company_id = input('id', '0', 'intval');
        if ($company_id > 0) {
            $companyDetail = $company->where("company_id = {$company_id}")->find();
        }
        
        $companyTypeList = getCommonConfigList('company_type');//公司类型列表
        $employeeTypeList = getCommonConfigList('employee_type');//公司规模列表
        if (request()->isPost()) {
            $companyData = array(
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
                'tax_number' => input('post.tax_number', '', 'strip_tags'), // '税号',
                'bank_deposit' => input('post.bank_deposit', '', 'strip_tags'), //'开户行',
                'bank_account' => input('post.bank_account', '', 'strip_tags'), // '银行账号',
            );

            if ($company_id > 0) {
                $companyData['last_uid'] = $this->_G['uid']; //'最后修改人',
                $companyData['last_time'] = date("Y-m-d H:i:s"); //'最后修改时间',
                $company->where(['company_id' => $company_id])->update($companyData);
                $this->success("修改成功", 'Company/lists');
            } else {
                $companyData['add_uid'] = $this->_G['uid']; //'最后修改人',
                $companyData['add_time'] = date("Y-m-d H:i:s"); //'最后修改时间',
                $company->data($companyData)->insert();
                $this->success("成功添加", 'Company/lists');
            }
        }
        $this->assign('companyDetail', $companyDetail);
        $this->assign('companyTypeList', $companyTypeList);
        $this->assign('employeeTypeList', $employeeTypeList);
        return $this->fetch($this->templatePath);
    }

    //公司详情页
    public function detail() {
        $company_id = input('get.id', '0', 'intval');
        $company = db('company');
        if ($company_id > 0) {
            $companyDetail = $company->where("company_id = {$company_id}")->find();
        } else {
            $this->error('请输入ID');
        }
        if (empty($companyDetail)) {
            $this->error('不存在该数据');
        }
        $navtitle = '公司详情页';
        $map = ['company_id' => $company_id];
        $contacts_list = DB::name('Contact')
                ->where($map)
                ->select();
        $this->assign('contacts_list', $contacts_list);
        $this->assign('navtitle', $navtitle);
        $this->assign('companyDetail', $companyDetail);
        return $this->fetch($this->templatePath);
    }

}
