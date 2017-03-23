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
        $project_id = input('get.project_id', 0, 'intval');
        $status = input('get.status', 'all', 'addslashes');
        if ($status == 'all') {
            
        } elseif ($status == 'noclosed') {
            $data['t.status'] = array('neq', 'closed');
        } else {
            $data['t.status'] = array('eq', $status);
        }
        $task = db('Task');
        $data['t.deleted'] = array('EQ', '0');        
        if ($project_id > 0) {
            $data['t.project'] = array('eq', $project_id);
        }
        $project_list = db('Project')->where(['deleted' => 0])->select();
        $task_list = $task
                ->alias('t')
                ->join('chinatt_pms_task p', 't.predecessor = p.id', 'left')
                ->where($data)
                ->field('t.*,p.status as p_status,p.name as p_name')
                ->order("id DESC")
                ->paginate(15, $task_count, ['path' => url('/admin/task/lists/'), 'query' => ['project_id' => $project_id]]);
        $page = $task_list->render(); // 分页显示输出
        if (request()->isPost()) {
            $delete_ids = array();
            foreach ($_POST['delete'] as $key => $value) {
                $delete_ids[] = $key;
            }
            $task_data['id'] = array('in', $delete_ids);
            $task->where($task_data)->save(array('deleted' => '1')); //删除之前的记录
        }$navtitle = '任务管理';
        $this->assign('navtitle', $navtitle);
        $this->assign('project_list', $project_list);
        $this->assign('project_id', $project_id);
        $this->assign('page', $page);
        $this->assign('task_list', $task_list);
        return $this->fetch($this->templatePath);
    }

}
