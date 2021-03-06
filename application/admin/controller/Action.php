<?php

namespace app\admin\controller;

use \traits\controller\Jump;
use \think\Db;
use Page;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Action extends Common {

    public function index() {

        return $this->fetch($this->templatePath);
    }

    public function lists() {
        $project_id = input('get.project_id', '0', 'intval');
        $product_id = input('get.product_id', '0', 'intval');
        $username = input('get.username', '', 'addslashes');
        $keyword = input('post.keyword', '', 'addslashes');
        if ($project_id > 0) {
            $map['a.project'] = array('eq', $project_id);
        }
        if (!empty($username)) {
            $map['a.actor'] = array('eq', $username);
        }
        if (!empty($keyword)) {
            $map['action'] = array('like', "%$keyword%");
        }
        if($product_id > 0){
            $map['a.product'] = array('eq', $product_id);
        }
        $starttime = input('starttime', '', 'addslashes');
        $endtime = input('endtime', '', 'addslashes');
        if(!empty($starttime)){
            if(empty($endtime)){
                $endtime = date('Y-m-d');
            }
            $map['a.date'] = array('BETWEEN',"$starttime,$endtime");
        }
        
        $product_list = db('Product')->where(['deleted' => 0])->select();
        $user_list = db('User')->where(['deleted' => 0])->select(); //用户列表
        //动态
        $action_list = DB('Action')
                ->alias('a')
                ->join('chinatt_pms_taskestimate b', 'a.extra =b.id', 'left')
                ->join('chinatt_pms_task t', 'a.objectType = \'task\' AND a.objectID =t.id', 'left')
                ->join('chinatt_pms_task p', 'a.objectType = \'project\' AND a.objectID =p.id', 'left')
                ->join('chinatt_pms_navigation n', 'a.objectType = \'navigation\' AND a.objectID =p.id', 'left')
                ->where($map)
                ->field('a.*,b.left,b.consumed,b.username,t.name as tname, p.name as pname')
                ->order('id DESC')
                ->paginate(10, $action_count, ['path' => url('/admin/action/lists/'), 'query' => ['username' => $username, 'keyword' => $keyword]]);
        $action_list = analysis_all($action_list);
        
        
        
        $page = $action_list->render(); // 分页显示输出
        $project_list = db('Project')->where(['deleted' => 0])->select();
        $navtitle = '动态管理';
        $this->assign('navtitle', $navtitle);
        $this->assign('product_list',$product_list);
        $this->assign('keyword', $keyword);
        $this->assign('user_list', $user_list);
        $this->assign('username', $username);
        $this->assign('product_id',$product_id);
        $this->assign('project_id', $project_id);
        $this->assign('project_list', $project_list);
        $this->assign('action_list', $action_list);
        $this->assign('page', $page);
        return $this->fetch($this->templatePath);
    }

}
