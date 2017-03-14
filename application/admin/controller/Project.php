<?php

namespace app\admin\controller;

use \traits\controller\Jump;
use \think\Db;
use Page;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Project extends Common {

    public function index() {
        return $this->fetch($this->templatePath);
    }

    public function lists() {
        
        $product_id = input('get.product_id', '0', 'intval');
        $status = input('get.status', 'all', 'addslashes');
        if ($status == 'all') {
            
        } elseif ($status == 'unclose') {
            $data['status'] = array('neq', 'close');
        } else {
            $data['status'] = array('eq', $status);
        }
        if($product_id > 0){
            $data['product'] = array('eq',$product_id);
        }
        $project = db('Project');
        $data['deleted'] = array('EQ', '0');
        $project_list = Db::name('Project')->where($data)->order("id DESC")->paginate(15);

        $page = $project_list->render(); // 分页显示输出
        //将对象转为数组
        $project_list = $project_list->toArray();
        $project_list = get_project_consume($project_list['data']);
        $product = DB('Product')->where(['deleted' => 0])->field('id,name')->select();
        $navtitle = '项目列表' . $this->navtitle;
        if (request()->isPost()) {
            $delete_ids = array();
            foreach ($_POST['delete'] as $key => $value) {
                $delete_ids[] = $key;
            }
            $project_data['id'] = array('in', $delete_ids);
            $project->where($project_data)->save(array('deleted' => '1')); //删除之前的记录
        }
        $navtitle = '项目管理';
        $this->assign('product_list',$product);
        $this->assign('product_id',$product_id);
        $this->assign('status',$status);
        $this->assign('navtitle', $navtitle);
        $this->assign('page', $page);
        $this->assign('project_list', $project_list);
        return $this->fetch($this->templatePath);
    }

}
