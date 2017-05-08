<?php

namespace app\index\controller;
use \traits\controller\Jump;
use \think\Db;
use Page;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Admin extends Common {
    
    public function index() {
        
        return $this->fetch($this->templatePath);
    }
    
    public function user() {
        $user = db('User');
        $user_list = $user->select();
        $this->assign('user_list',$user_list); 
        return $this->fetch($this->templatePath);
    }
    
    public function task() {
        $task = db('Task');
        $data['deleted'] = array('EQ','0');
        //分页相关处理
        $task_count = $task->where($data)->count();
        $Page       = new Page($task_count,10);
        $show       = $Page->show();
        
        $task_list = $task->where($data)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        if (request()->isPost()) {
            $delete_ids = array();
            foreach ($_POST['delete'] as $key => $value) {
                $delete_ids[] = $key;
            }
            $task_data['id']  = array('in',$delete_ids);
            $task->where($task_data)->save(array('deleted' => '1'));//删除之前的记录
        }
        $this->assign('page',$show);
        $this->assign('task_list', $task_list);
        return $this->fetch($this->templatePath);
    }
    
    public function project() {
    $project = db('Project');
    $data['deleted'] = array('EQ','0');
    //分页相关处理
    $project_count = $project->where($data)->count();
    $Page       = new Page($project_count,10);
    $show       = $Page->show();
    $project_list = $project->where($data)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
    if (request()->isPost()) {
            $delete_ids = array();
            foreach ($_POST['delete'] as $key => $value) {
                $delete_ids[] = $key;
            }
            $project_data['id']  = array('in',$delete_ids);
            $project->where($project_data)->save(array('deleted' => '1'));//删除之前的记录
        }
    
    $this->assign('page',$show);
    $this->assign('project_list',$project_list);
    return $this->fetch($this->templatePath);
    }
    
}