<?php

namespace app\admin\controller;
use \traits\controller\Jump;
use \think\Db;
use Page;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Task extends Common {
    
    public function index() {
        
        return $this->fetch($this->templatePath);
    }
    
    public function lists() {
        $task = db('Task');
        $data['deleted'] = array('EQ','0');
        //$task_list = $task->where($data)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        $task_list = $task->where($data)->order("id DESC")->paginate(15);
        $page = $task_list->render(); // 分页显示输出
        if (request()->isPost()) {
            $delete_ids = array();
            foreach ($_POST['delete'] as $key => $value) {
                $delete_ids[] = $key;
            }
            $task_data['id']  = array('in',$delete_ids);
            $task->where($task_data)->save(array('deleted' => '1'));//删除之前的记录
        }
        $this->assign('page',$page);
        $this->assign('task_list', $task_list);
        return $this->fetch($this->templatePath);
    }
    
    
    
}
