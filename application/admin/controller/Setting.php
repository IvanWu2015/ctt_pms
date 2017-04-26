<?php

namespace app\admin\controller;

use \traits\controller\Jump;
use \think\Db;
use Page;
use tree;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Setting extends Common {

    public function index() {
        return $this->fetch($this->templatePath);
    }

    public function add() {
        
        
        
        
        $navtitle = '分类列表' . $class_detail['name'];
        $this->assign('type',$type);
        $this->assign('navtitle', $navtitle);
        $this->assign('parentid', $parentid);
        $this->assign('class_id', $class_id);
        $this->assign('class_detail', $class_detail);
        $this->assign('paren_detail', $paren_detail);
        $this->assign('sort_list', $sort_list);
        return $this->fetch($this->templatePath);
    }

    public function lists() {
        $setting_list = DB('Config')->where(['status' => 1])->paginate();
        
        if (request()->isPost()) {
            foreach ($_POST['key'] as $key => $value){
                $value = intval($value);
                DB('Config')->where(['id' => $key])->update(['c_key' => $value, 'c_value' => $_POST['value'][$key]]);
            }
            
            
            
            
            
        }
        
        
        
        
        $navtitle = '分类列表';
        $this->assign('type',$type);
        $this->assign('navtitle', $navtitle);
        $this->assign('setting_list', $setting_list);
        return $this->fetch($this->templatePath);
    }

}
