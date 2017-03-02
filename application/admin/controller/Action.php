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
        $user_list = db('User')->where(['deleted' => 0])->select();
        $action_list = DB::name('Action')
                ->alias('a')
                ->join('chinatt_pms_project p', 'a.project = p.id', 'left')
                ->join('chinatt_pms_user u','a.actor = u.username','left')
                ->field('a.*,p.name as parent_name,p.status,u.realname')
                ->where($map)
                ->order('id DESC')
                ->paginate(10, $action_count, ['path' => url('/admin/action/lists/'), 'query' => ['project_id' => $project_id]]);
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
