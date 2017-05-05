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
        $username = input('get.username', '', 'addslashes');
        $openedby = input('get.openedby', '', 'addslashes');
        if ($status == 'all') {
            
        } elseif ($status == 'noclosed') {
            $data['t.status'] = array('neq', 'closed');
        }elseif($status == 'notdone'){
            $data['t.status'] = array('in', 'doing,wait');
        } else {
            $data['t.status'] = array('eq', $status);
        }
        $task = db('Task');
        $data['t.deleted'] = array('EQ', '0');        
        if ($project_id > 0) {
            $data['t.project'] = array('eq', $project_id);
        }
        if(!empty($username)){
            $data['t.assignedTo'] = array('eq', $username);
        }
        if(!empty($openedby)){
            $data['t.openedBy'] = array('eq', $openedby);
        }
        //搜索
        $keyword = input('post.keyword', '', 'addslashes');
        if(!empty($keyword)){
            $data['t.name'] = array('like',"%$keyword%");
        }
        
        $user_list = DB('User')->where(['deleted' => 0])->select();
        $project_list = db('Project')->where(['deleted' => 0])->select();
        $task_list = $task
                ->alias('t')
                ->join('chinatt_pms_task p', 't.predecessor = p.id', 'left')
                ->where($data)
                ->field('t.*,p.status as p_status,p.name as p_name')
                ->order("id DESC")
                ->paginate(20, $task_count, ['path' => url('/admin/task/lists/'), 'query' => ['project_id' => $project_id ,'username' => $username,'openedby' => $openedby,'status' => $status]]);
        $page = $task_list->render(); // 分页显示输出
//        if (request()->isPost()) {
//            $delete_ids = array();
//            foreach ($_POST['delete'] as $key => $value) {
//                $delete_ids[] = $key;
//            }
//            $task_data['id'] = array('in', $delete_ids);
//            $task->where($task_data)->save(array('deleted' => '1')); //删除之前的记录
//        }
        $deleted = input('get.deleted', '0', 'intval');
        if($deleted == '1'){
            save_log($this->_G['uid'],$this->_G['username']);
            $task_id = input('get.id', '0', 'intval');
            db('Task')->where(['id' => $task_id])->update(array('deleted' => '1')); //删除之前的记录
            $this->success("删除成功",'admin/task/lists');
        }
        
        $navtitle = '任务管理';
        $this->assign('keyword',$keyword);
        $this->assign('navtitle', $navtitle);
        $this->assign('project_list', $project_list);
        $this->assign('username',$username);
        $this->assign('user_list',$user_list);
        $this->assign('project_id', $project_id);
        $this->assign('page', $page);
        $this->assign('task_list', $task_list);
        return $this->fetch($this->templatePath);
    }

}
