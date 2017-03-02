<?php

namespace app\index\controller;

load_trait('controller/Jump');  // 引入traits\controller\Jump 5.5以上版本无须引用

use \traits\controller\Jump;
use \think\Db;
use Page;

class Product extends Common {

    //项目详情页
    public function detail() {

        return $this->fetch($this->templatePath);
    }

    //列表
    public function lists() {
        $product_list = DB::name('Product')->where(['deleted' => 0])->paginate(10);
        $page = $product_list->render(); // 分页显示输出
        
        
        
        
        
        
        
        $navtitle = '产品列表' . $this->navtitle;
        $this->assign('status', $status);
        $this->assign('page', $page);
        $this->assign('product_list', $product_list);
        $this->assign('navtitle', $navtitle);
        return $this->fetch($this->templatePath);
    }

    //添加
    public function add() {
        $product_id = input('get.name', '0', 'intval');
        $user_list = DB::table('chinatt_pms_user')->select();
        if ($product_id > 0) {
            $product_detail = DB::name('Product')->where(['id' => $product_id, 'deleted' => 0])->find();
        }
        if (request()->isPost()) {
            $data = [
                'name' => input('post.name', '', 'addslashes'),
                'code' => input('post.code', '', 'addslashes'),
                'createdBy' => $this->_G['username'],
                'createdDate' => date('Y-m-d H:i:s'),
                'PO' => input('post.po', '', 'addslashes'),
                'createdVersion' => input('post.createdVersion', '', 'addslashes'),
                'deleted' => 0,
            ];

            if ($product_id > 0) {
                DB::name('Product')->where(['id' => $product_id])->update($data);
                $this->success("修改成功", 'Product/lists');
            } else {
                DB::name('Product')->insertGetId($data);
                $this->success("成功添加", 'Product/lists');
            }
        }







        $navtitle = '添加/修改产品' . $this->navtitle;
        $this->assign('project_id', $project_id);
        $this->assign('product_detail', $product_detail);
        $this->assign('team_list', $team_list);
        $this->assign('user_list', $user_list);
        $this->assign('navtitle', $navtitle);
        return $this->fetch($this->templatePath);
    }

}
