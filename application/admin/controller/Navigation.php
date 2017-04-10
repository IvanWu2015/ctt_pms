<?php

namespace app\admin\controller;

use \traits\controller\Jump;
use \think\Db;
use Page;
use tree;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Navigation extends Common {

    public function index() {
        return $this->fetch($this->templatePath);
    }

    public function lists() {
        
        $navigation_list = DB('Navigation')->where(['status' => 1])->select();
        

        $tree = new Tree($navigation_list);
        $navigation_list = $tree->getArray();
        
        
        $this->assign('navigation_list',$navigation_list);
        
        return $this->fetch($this->templatePath);
    }

}
