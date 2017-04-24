<?php

namespace app\admin\controller;

use \traits\controller\Jump;
use \think\Db;
use Page;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Plan extends Common {

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
        
        $deleted = input('get.deleted', '0', 'intval');
        $plan_id = input('get.id', '0', 'intval');
        
        $plan_list = DB::name('Plan')
                ->alias('n')
                ->join('chinatt_pms_product d', 'n.product = d.id', 'left')
                ->join('chinatt_pms_project j', 'n.project = j.id', 'left')
                ->join('chinatt_pms_task t', 'n.task = t.id', 'left')
                ->field('n.*,d.name as product_name,j.name as project_name,t.name as task_name')
                ->where(['n.deleted' => '0'])
                ->paginate(10);
        $page = $plan_list->render(); // 分页显示输出
        
        
        
        
            if($deleted == 1){
                 db('Plan')->where(['id' => $plan_id])->update(array('deleted' => '1')); //删除
                 $this->success("删除成功");
            }
        
        
        
        $navtitle = '需求列表' . $this->navtitle;
        $this->assign('status', $status);
        $this->assign('page', $page);
        $this->assign('plan_list', $plan_list);
        $this->assign('navtitle', $navtitle);
        return $this->fetch($this->templatePath);
    }

}
