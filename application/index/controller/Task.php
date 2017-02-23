<?php

namespace app\index\controller;

load_trait('controller/Jump');  // 引入traits\controller\Jump

use \traits\controller\Jump;
use \think\Db;

class Task extends Common {

    //首页
    public function index() {
        $this->redirect(url('/index/project/lists'));
    }

    //任务状态更新
    public function action() {
        $task_id = input('id', '0', 'intval');
        $ac = input('ac', '', 'addslashes');
        $task = DB::table('chinatt_pms_task')->where(['id' => $task_id])->find();
        $user_list = get_userlist_by_projectid($task['project']);
        $type = input('param.type');
        $user_info = Db::name('User')->where(['uid' => $this->_G['uid'],'deleted' => 0])->find();
        $consumed = input('param.consumed'); //消耗
        if (request()->isPost()) {
            //分配
            if ($type == 'assign') {
                $assigo_data = [
                    'assignedTo' => input('param.assignedTo'),
                    'estimate' => input('param.estimate'),
                    'desc' => trim(input('param.desc')),
                ];
                if($assigo_data['estimate'] < 0){
                    $message = array('result' => 'fails');
                }
                DB::table('chinatt_pms_task')->where(['id' => $task_id])->update($assigo_data);
                write_action($this->_G['username'],$task['project'], 'task', $task_id, 'assignedTo',trim(input('param.desc')),input('param.assignedTo'));
                $message = array('result' => 'success', 'error' => '');
                $data = json_encode($message);
                echo $data;
                exit();
            }
            if($type == 'commented'){
                write_action($this->_G['username'],$task['project'], 'task', $task_id, 'commented',trim(input('param.work')));
               $this->success("成功添加", url("/Index/Task/detail/?id=$task_id"));
            }
            //工时
            if ($type == 'taskestimate') {
                $work_data = [
                    'username' => $this->_G['username'],
                    'task' => $task_id,
                    'work' => trim(input('param.work')),
                    
                    'date' => date('Y-m-d'),
                    'left' => input('param.left'), //剩余
                ];
                if($consumed > 0){
                        $work_data['consumed'] = $consumed;
                    }
                $action_id = DB::table('chinatt_pms_taskestimate')->insertGetId($work_data);
                write_action($this->_G['username'], $task['project'] , 'task', $task_id, 'recordestimate','',$action_id);
                $message = array('result' => 'success', 'error' => '');
                $data = json_encode($message);
                echo $data;
                exit();
            }
            if ($type == 'task') {
                //关闭
                if ($ac == 'closed') {
                    
                    $task_data = [
                        'status' => 'closed',
                        'closedBy' => $this->_G['username'],
                        'closedDate' => date('Y-m-d H:i:s'),
                    ];
                    if($consumed > 0){
                        $task_data['consumed'] = $consumed;
                    }
                    DB::table('chinatt_pms_task')->where(['id' => $task_id])->update($task_data);
                    write_action($this->_G['username'], 0, 'task', $task_id, 'closed');
                }
                //完成
                if ($ac == 'done') {
                    $task_data = [
                        'status' => 'done',
                        'finishedBy' => $this->_G['username'],
                        'finishedDate' => date('Y-m-d H:i:s'),
                    ];
                    if($consumed > 0){
                        $task_data['consumed'] = $consumed;
                    }
                    DB::table('chinatt_pms_task')->where(['id' => $task_id])->update($task_data);
                    write_action($this->_G['username'], 0, 'task', $task_id, 'done');
                }
                if ($action == 'assign') {
                    $task_data = [
                        'finishedBy' => $this->_G['username'],
                        'finishedDate' => date('Y-m-d H:i:s'),
                        'desc' => input('param.desc'),
                    ];
                    DB::table('chinatt_pms_task')->where(['id' => $task_id])->update($task_data);
                    write_action($this->_G['username'], $task['project'], 'task', $task_id, 'assign');
                }
                $message = array('result' => 'success', 'error' => '');
                $data = json_encode($message);
                echo $data;
                exit();
            }
        }
        $this->assign('user_info',$user_info);
        $this->assign('ac', $ac);
        $this->assign('task_detail', $task_detail);
        $this->assign('work_list', $work_list);
        $this->assign('task_id', $task_id);
        $this->assign('project_id', $project_id);
        $this->assign('user_list', $user_list);
        $this->assign('navtitle', $navtitle);
        return $this->fetch($this->templatePath);
    }

    /**
     * 任务详情页
     */
    public function detail() {
        $task_id = input('id', '0', 'intval');
        $task_detail = DB::table('chinatt_pms_task')->where(['id' => $task_id])->find();
        if (empty($task_detail)) {
            $this->error('任务不存在。');
        }
        $user_list = DB::table('chinatt_pms_user')->select();
        $data['task'] = array('EQ', $task_id);
        $work_list = DB::table('chinatt_pms_taskestimate')->where($data)->order('id')->select();
        $project_id = $task_detail['project'];
        $project_detail = DB::name('project')->where(['id' => $project_id])->find();
        $user_list = get_userlist_by_projectid($project_id);
        if (request()->isPost()) {
            $work_data = [
                'username' => $this->_G['username'],
                'task' => $task_id,
                'work' => trim(input('param.work')),
                'consumed' => input('post.consumed', '0', 'intval'), //消耗
                'date' => date('Y-m-d'),
                'left' => input('param.left'), //剩余
            ];

            $task_data = [
                'finishedBy' => $this->_G['username'],
                'finishedDate' => date('Y-m-d H:i:s'),
                'status' => input('param.status'),
                'consumed' => input('post.consumed', '0', 'intval'),
            ];

            DB::table('chinatt_pms_taskestimate')->insert($work_data);
            DB::table('chinatt_pms_task')->where(['id' => $task_id])->update($task_data);
            //操作记录
            write_action($this->_G['username'], $project_id, 'task', $task_id, 'updata', input('param.work'));
            $this->success("操作成功");
            exit;
        }
        $action_list = analysis($task_id);
        $navtitle = $task_detail['name'] . ' - ' . $project_detail['name'];
        $this->assign('project_detail', $project_detail);
        $this->assign('task_detail', $task_detail);
        $this->assign('action_list', $action_list);
        $this->assign('work_list', $work_list);
        $this->assign('task_id', $task_id);
        $this->assign('project_id', $project_id);
        $this->assign('user_list', $user_list);
        $this->assign('navtitle', $navtitle);
        return $this->fetch($this->templatePath);
    }

    /**
     * 添加/编辑 任务处理
     * @return type
     */
    public function add() {
        $task = Db::name('task');
        $project_id = input('project_id', '0', 'intval');
        $task_id = input('task_id', '0', 'intval');
        if ($task_id > 0) {
            $task_details = $task->where(['id' => $task_id])->find();
            $project_id = $task_details['project'];
        }
        if ($project_id > 0) {
            $user_list = get_userlist_by_projectid($project_id);
        }
        $map['status'] = array('not in', 'closed,done');
        if (!$this->_G['is_admin']) {
            $map['acl'] = array('eq', 'open');
        }
        $project_list = Db::name('Project')->where($map)->column('id,name,code', 'id');
        if (request()->isPost()) {
            $task_data = [
                'project' => $project_id,
                'name' => input('name', '', 'addslashes'),
                'estimate' => input('param.estimate'),
                'desc' => trim(input('param.desc')),
                'openedBy' => $this->_G['username'],
                'type' => input('param.type'),
                'assignedTo' => input('param.assignedTo'),
                'assignedDate' => date('Y-m-d H:i:s'), //分配时间
                'openedDate' => date('Y-m-d H:i:s'), //开放时间
//                'comment' =>input('param.comment'),
                'pri' => input('param.pri'),
                'deadline' => input('param.deadline'), //最后期限
                'realStarted' => input('param.realStarted'), //开始时间
                'status' => input('param.status'),
            ];
            if ($task_id > 0) {
                DB::table('chinatt_pms_task')->where(['id' => $task_id])->update($task_data);
                //操作记录
                write_action($this->_G['username'], $project_id, 'task', $task_id, 'updata', trim(input('param.desc')));
                $this->success("修改成功", url("/Index/Project/detail/?id=$project_id"));
                exit;
            } else {

                $task_id = DB::table('chinatt_pms_task')->insertGetId($task_data);
                //操作记录
                write_action($this->_G['username'], $project_id, 'task', $task_id, 'opened');
                $this->success("成功添加", url("/Index/Project/detail/?id=$project_id"));
                exit;
            }
        }
        $navtitle = '任务添加/编辑' . $project_detail['name'];
        $this->assign('navtitle', $navtitle);
        $this->assign('project_id', $project_id);
        $this->assign('project_list', $project_list);
        $this->assign('task_details', $task_details);
        $this->assign('user_list', $user_list);
        $this->assign('task_id', $task_id);
        return $this->fetch($this->templatePath);
    }
    

   
    
}