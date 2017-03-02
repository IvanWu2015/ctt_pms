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
        $task = db('Task');
        $data['deleted'] = array('EQ', '0');
        if ($project_id > 0) {
            $data['project'] = array('eq', $project_id);
        }
        $project_list = db('Project')->where(['deleted' => 0])->select();
        $this->assign('project_list', $project_list);
        $task_list = $task->where($data)->order("id DESC")->paginate(15, $task_count, ['path' => url('/admin/task/lists/'), 'query' => ['project_id' => $project_id]]);
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
        $this->assign('project_id', $project_id);
        $this->assign('page', $page);
        $this->assign('task_list', $task_list);
        return $this->fetch($this->templatePath);
    }

}
