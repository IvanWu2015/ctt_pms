<?php

namespace app\admin\controller;

use \traits\controller\Jump;
use \think\Db;
use Page;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Product extends Common {

    public function index() {
        return $this->fetch($this->templatePath);
    }

    public function add() {
        $navtitle = '产品列表';
        $this->assign('navtitle', $navtitle);
        $this->assign('class_id', $class_id);
        $this->assign('class_detail', $class_detail);
        $this->assign('sort_list', $sort_list);
        return $this->fetch($this->templatePath);
    }

    public function lists() {
        //产品列表
        $product_list = DB::name('Product')
                ->alias('p')
                ->field('p.*')
                ->where(['p.deleted' => '0'])
                ->paginate(10);
        $page = $product_list->render(); // 分页显示输出
        $product_id = input('get.id', '0', 'intval');
        $deleted = input('get.deleted', '0', 'intval');
        if ($deleted == 1) {
            save_log($this->_G['uid'], $this->_G['username']);
            DB::name('Product')->where(['id' => $product_id])->update(['deleted' => 1]);
            $this->success("删除成功", 'product/lists');
        }
        $navtitle = '分类列表';
        $this->assign('navtitle', $navtitle);
        $this->assign('page', $page);
        $this->assign('product_list', $product_list);
        return $this->fetch($this->templatePath);
    }
}