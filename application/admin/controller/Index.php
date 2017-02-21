<?php

namespace app\admin\controller;
use \traits\controller\Jump;
use \think\Db;
use Page;


class Index extends Common {
    
    public function index() {
        return $this->fetch($this->templatePath);
    }
    
}



