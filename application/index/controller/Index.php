<?php

namespace app\index\controller;
use \traits\controller\Jump;
use \think\Db;
load_trait('controller/Jump');  // 引入traits\controller\Jump

class Index extends Common {

    //首页
    public function index() {
        $this->redirect(url('/index/Mycenter'));
        exit;
        $this->assign('navtitle', $navtitle);
        return $this->fetch($this->templatePath);
    }
    

}
