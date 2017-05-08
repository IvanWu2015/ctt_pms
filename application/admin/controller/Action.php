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
        $username = input('get.username', '', 'addslashes');
        $keyword = input('post.keyword', '', 'addslashes');
        if($project_id > 0){
            $map['a.project'] = array('eq',$project_id);
        }
        if(!empty($username)){
            $map['a.actor'] = array('eq',$username);
        }
        if(!empty($keyword)){
            $map['action'] = array('like',"%$keyword%");
        }
        $user_list = db('User')->where(['deleted' => 0])->select();//用户列表
        //动态
        $action_list = DB('Action')
                ->alias('a')
                ->join('chinatt_pms_taskestimate b', 'a.extra =b.id', 'left')
                ->join('chinatt_pms_task t', 'a.objectType = \'task\' AND a.objectID =t.id', 'left')
                ->join('chinatt_pms_task p', 'a.objectType = \'project\' AND a.objectID =p.id', 'left')
                ->where($map)
                ->field('a.*,b.left,b.consumed,b.username,t.name as tname, p.name as pname')
                ->order('id DESC')
                ->paginate(30, $action_count, ['path' => url('/admin/action/lists/'), 'query' => ['username' => $username,'keyword' => $keyword]]);
        
        $action_list = analysis_all($action_list);
        $page = $action_list->render(); // 分页显示输出
        $project_list = db('Project')->where(['deleted' => 0])->select();
        $navtitle = '动态管理';
        $this->assign('navtitle', $navtitle);
        $this->assign('keyword',$keyword);
        $this->assign('user_list',$user_list);
        $this->assign('username',$username);
        $this->assign('project_id',$project_id);
        $this->assign('project_list',$project_list);
        $this->assign('action_list',$action_list);
        $this->assign('page',$page);
        return $this->fetch($this->templatePath);
    }
}