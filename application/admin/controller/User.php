<?php

namespace app\admin\controller;

use \traits\controller\Jump;
use \think\Db;
use Page;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class User extends Common {
    public function index() {
        return $this->fetch($this->templatePath);
    }
    public function lists() {   //用户列表
       $user_list = db('User')
                ->alias('u')
                ->join('chinatt_pms_dept d', 'u.dept = d.id', 'left')
                ->join('chinatt_pms_group g', 'u.groupid = g.id')
                ->field('u.*,d.name as depe_name,g.name as group_name')
                ->where(['deleted' => 0])
                ->order('uid DESC')
               ->paginate(10);
       
       
       
       $navtitle = '用户列表' . $this->navtitle;
       $this->assign('navtitle', $navtitle);
        $this->assign('user_list',$user_list); 
        return $this->fetch($this->templatePath);
    }
    
    public function detail() {   //用户详情
        $uid = input('get.uid', 0, 'intval');
        $username = input('get.username', '', 'addslashes');
        $estimate_count = DB::name('Task')->where(['assignedTo' => $username, 'deleted' => 0])->sum('estimate');
        $consumed_count = DB::name('Task')->where(['finishedBy' => $username, 'deleted' => 0])->sum('consumed');
        $my_task_count = DB::name('Task')->where(['assignedTo' => $username, 'deleted' => 0])->count();
        $my_action_count = DB::name('Action')->where(['actor' => $username])->count();
        $my_weburl_count = DB::name('Weburl')->where(['uid' => $uid, 'status' => 0])->count();
        $my_article_count = DB::name('Article')->where(['uid' => $uid, 'status' => 0])->count();

        $not_status_data['status'] = array('in', 'wait,doing');
        $not_status_data['assignedTo'] = array('eq', $username);
        $not_status_data['deleted'] = array('eq', '0');
        $not_status_count = DB::name('Task')->where($not_status_data)->count();

        $headDate = date('Y-m-01 00:00:00', strtotime(date("Y-m-d")));
        $estimate_map['deleted'] = array('eq', 0);
        $estimate_map['finishedBy'] = array('eq', $username);
        $estimate_map['finishedDate'] = array('GT', $headDate);
        $same_month_estimate_count = DB::name('Task')->where($estimate_map)->sum('estimate'); //当月预计工时
        $consumed_map['deleted'] = array('eq', 0);
        $consumed_map['finishedBy'] = array('eq', $username);
        $consumed_map['finishedDate'] = array('GT', $headDate);
        $same_month_consumed_count = DB::name('Task')->where($consumed_map)->sum('consumed'); //当月完成工时


        $user = db('User')
                ->alias('u')
                ->join('chinatt_pms_dept d', 'u.dept = d.id', 'left')
                ->join('chinatt_pms_group g', 'u.groupid = g.id')
                ->field('u.*,d.name as depe_name,g.name as group_name')
                ->where(['u.uid' => $uid])
                ->find();
        //未完成任务
        $task_list = db('Task')->where(['assignedTo' => $username, 'status' => 'wait'])->order('id DESC')->select();
        $task_list = format_task($task_list);
        //动态
        $action_list = DB('Action')
                ->alias('a')
                ->join('chinatt_pms_taskestimate b', 'a.extra =b.id', 'left')
                ->join('chinatt_pms_task t', 'a.objectType = \'task\' AND a.objectID =t.id', 'left')
                ->join('chinatt_pms_task p', 'a.objectType = \'project\' AND a.objectID =p.id', 'left')
                ->where(['a.actor' => $username])
                ->field('a.*,b.left,b.consumed,b.username,t.name as tname, p.name as pname')
                ->order('id DESC')
                ->paginate(10);
        $action_list = analysis_all($action_list);
        //网址列表
        $weburl_list = DB::name('weburl')->alias('w')->join('chinatt_pms_project p', 'w.project = p.id', 'left')->field('w.*,p.name')->where(['w.uid' => $uid])->order('id DESC')->paginate(10);
        //文档列表
        $article_list = DB::name('Article')
                ->alias('a')
                ->join('chinatt_pms_class c', 'a.class = c.id', 'left')
                ->join('chinatt_pms_project p ', 'a.project = p.id', 'left')
                ->join('chinatt_pms_user u ', 'a.uid = u.uid', 'left')
                ->field('a.*,c.name as class_name,p.name as project_name,u.username')
                ->where(['a.status' => 0, 'a.uid' => $uid])
                ->paginate(10);

        $navtitle = '个人中心' . $this->navtitle;
        $this->assign('same_month_consumed_count', $same_month_consumed_count);
        $this->assign('same_month_estimate_count', $same_month_estimate_count);
        $this->assign('not_status_count',$not_status_count);
        $this->assign('my_article_count', $my_article_count);
        $this->assign('my_weburl_count', $my_weburl_count);
        $this->assign('my_action_count', $my_action_count);
        $this->assign('my_task_count', $my_task_count);
        $this->assign('estimate_count', $estimate_count);
        $this->assign('consumed_count', $consumed_count);
        $this->assign('article_list', $article_list);
        $this->assign('weburl_list', $weburl_list);
        $this->assign('action_list', $action_list);
        $this->assign('task_list', $task_list);
        $this->assign('user', $user);
        $this->assign('navtitle', $navtitle);
        
        return $this->fetch($this->templatePath);
    }
    
}



