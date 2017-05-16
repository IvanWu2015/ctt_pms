<?php

namespace app\admin\controller;
use \traits\controller\Jump;
use \think\Db;
use Page;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Log extends Common {
    
    public function index() {
        
        return $this->fetch($this->templatePath);
    }
    
    public function lists() {
        $log_list = DB('Log')->where()->order('id DESC')->paginate(15);
        $page = $log_list->render(); // 分页显示输出
        $navtitle = '操作列表';
        $this->assign('navtitle',$navtitle);
        $this->assign('log_list',$log_list);
        $this->assign('page',$page);
        return $this->fetch($this->templatePath);
    }
}
