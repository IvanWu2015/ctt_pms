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

    public function add() {

        $navigation_list = DB('Navigation')->where(['status' => 1])->select();
        if (request()->isPost()) {
            if (preg_match('/(http:\/\/)|(https:\/\/)/i', input('param.url'))) {
                $data['url'] = input('param.url');
            } else {
                $data['url'] = "{:url('" . "input('param.url')" . "')}";
            }
        }
        $this->assign('navigation_list', $navigation_list);
        return $this->fetch($this->templatePath);
    }

    public function lists() {

        $navigation_list = DB('Navigation')->where(['status' => 1])->select();


        $tree = new Tree($navigation_list);
        $navigation_list = $tree->getArray();


        $this->assign('navigation_list', $navigation_list);

        return $this->fetch($this->templatePath);
    }

}
