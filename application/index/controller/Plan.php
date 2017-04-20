<?php

namespace app\index\controller;

load_trait('controller/Jump');  // 引入traits\controller\Jump 5.5以上版本无须引用

use \traits\controller\Jump;
use \think\Db;
use Page;

class Plan extends Common {

    //项目详情页
    public function detail() {

        return $this->fetch($this->templatePath);
    }

    //列表
    public function lists() {
        
        $username = input('get.username', '', 'addslashes');
        if(!empty($username)){
            $data['n.assignedTo'] = array('eq',$username);
        }
        $data['n.deleted'] = array('eq','0');
        
        $code = input('get.code', '', 'addslashes');
        if(!empty($code)){
            $data['d.code'] = array('eq',$code);
        }
        $plan_list = DB::name('Plan')
                ->alias('n')
                ->join('chinatt_pms_product d', 'n.product = d.id', 'left')
                ->join('chinatt_pms_project j', 'n.project = j.id', 'left')
                ->join('chinatt_pms_task t', 'n.task = t.id', 'left')
                ->field('n.*,d.name as product_name,j.name as project_name,t.name as task_name,d.code')
                ->where($data)
                ->paginate(10);
        $user_list = DB::table('chinatt_pms_user')->select();
        
        $page = $plan_list->render(); // 分页显示输出
        
        $product_list = DB('Product')->where(['deleted' => '0'])->select();
        
        $navtitle = '需求列表' . $this->navtitle;
        $this->assign('status', $status);
        $this->assign('user_list',$user_list);
        $this->assign('page', $page);
        $this->assign('username',$username);
        $this->assign('code',$code);
        $this->assign('product_list',$product_list);
        $this->assign('plan_list', $plan_list);
        $this->assign('navtitle', $navtitle);
        return $this->fetch($this->templatePath);
    }

    //添加
    public function add() {
        $product_id = input('get.product_id', '0', 'intval');
        $plan_id = input('get.id', '0', 'intval');
        if ($product_id > 0){
            $product_detail = DB('Product')
                    ->where(['deleted' => 0,'id' => $product_id])
                    ->field('name,code,PO')
                    ->find();
        }
        
        if ($plan_id > 0){
            $plan_detail = DB('Plan')->where(['deleted' => 0,'id' => $plan_id])->find();
        }
        
        
        $user_list = DB::table('chinatt_pms_user')->select();

        if (request()->isPost()) {
            $data = [
                'title' => input('post.title', '', 'addslashes'),
                'product' => input('post.product_id', '0', 'intval'),
                'project' => input('post.project_id', '0', 'intval'),
                'task' => input('post.task_id', '0', 'intval'),
                'content' => input('post.content', '', 'addslashes'),
                'estimate' => input('post.estimate', '0', 'intval'),
                'assignedTo' => $product_detail['PO'],
                'assignedDate' => date('Y-m-d H:i:s'),
                'openedDate' => date('Y-m-d H:i:s'),
                'openedBy' => $this->_G['username'],
                'status' => 'wait',
            ];
            if ($plan_id > 0) {
                DB::name('Plan')->where(['id' => $product_id])->update($data);
                $this->success("修改成功", 'Plan/lists');
            } else {
                DB::name('Plan')->insertGetId($data);
                $this->success("成功添加", 'Plan/lists');
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
