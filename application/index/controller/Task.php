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
        $task = DB::table('chinatt_pms_task')
                ->alias('t')
                ->join('chinatt_pms_project p', 't.project = p.id', 'left')
                ->where(['t.id' => $task_id])
                ->field('t.*,p.product')
                ->find();
        $user_list = get_userlist_by_projectid($task['project']);
        $type = input('param.type');
        $user_info = Db::name('User')->where(['uid' => $this->_G['uid'], 'deleted' => 0])->find();
        $consumed = input('param.consumed'); //消耗
        if (request()->isPost()) {
            //分配
            if ($type == 'assign') {
                $assigo_data = [
                    'assignedTo' => input('param.assignedTo'),
                    'estimate' => input('param.estimate'),
                    'desc' => trim(input('param.desc')),
                ];
                if ($assigo_data['estimate'] < 0) {
                    $message = array('result' => 'fails');
                }
                DB::table('chinatt_pms_task')->where(['id' => $task_id])->update($assigo_data);
                write_action($this->_G['username'], $task['project'], 'task', $task_id, 'assignedTo', trim(input('param.desc')), input('param.assignedTo'));
                $message = array('result' => 'success', 'error' => '');
                $data = json_encode($message);
                echo $data;
                exit();
            }
            if ($type == 'commented') {
                write_action($this->_G['username'], $task['project'], 'task', $task_id, 'commented', trim(input('param.work')));
                $this->success("成功添加", url("/Index/Task/detail/?id=$task_id"));
            }
            //记录工时
            if ($type == 'taskestimate') {

                $work_data = [
                    'username' => $this->_G['username'],
                    'task' => $task_id,
                    'work' => trim(input('param.work')),
                    'date' => date('Y-m-d'),
                    'left' => input('param.left', 0, 'intval'), //剩余
                ];
                if ($consumed > 0) {
                    $work_data['consumed'] = $consumed;
                }
                DB::name('Task')->where(['id' => $task_id])->update(['status' => 'doing']);
                DB::name('Task')->where(['id' => $task_id])->setInc('consumed', $consumed);
                $taskestimate_id = DB::table('chinatt_pms_taskestimate')->insertGetId($work_data);
                //记录产品、用户、项目当天总工时
                working_count('user', $this->_G['uid'], $this->_G['username'], $consumed);
                working_count('product', $task['product'], $this->_G['username'], $consumed);
                working_count('project', $task['project'], $this->_G['username'], $consumed);
                //动态信息
                write_action($this->_G['username'], $task['project'], 'task', $task_id, 'recordestimate', $work_data['work'], $taskestimate_id);
                $message = array('result' => 'success', 'error' => '');
                $data = json_encode($message);
                echo $data;
                exit();
                //任务状态更新
            } elseif ($type == 'task') {
                //关闭任务
                if ($ac == 'closed') {
                    $task_data = [
                        'status' => 'closed',
                        'closedBy' => $this->_G['username'],
                        'closedDate' => date('Y-m-d H:i:s'),
                    ];
                    if ($consumed > 0) {
                        $task_data['consumed'] = $consumed;
                    }
                    DB::table('chinatt_pms_task')->where(['id' => $task_id])->update($task_data);
                    write_action($this->_G['username'], $task['project'], 'task', $task_id, 'closed');

                    //开始任务
                } elseif ($ac == 'start') {
                    $task_data = [
                        'status' => 'doing',
                    ];
                    DB::table('chinatt_pms_task')->where(['id' => $task_id])->update($task_data);
                    //工时信息写入
                    if ($consumed > 0) {
                        $work_data = [
                            'username' => $this->_G['username'],
                            'task' => $task_id,
                            'work' => trim(input('param.work')),
                            'date' => date('Y-m-d'),
                            'consumed' => $consumed,
                            'left' => input('left', 0, 'intval'), //剩余
                        ];
                        $taskestimate_id = DB::table('chinatt_pms_taskestimate')->insertGetId($work_data);
                    }
                    write_action($this->_G['username'], $task['project'], 'task', $task_id, 'started', input('work'), $taskestimate_id);
                    //记录产品、用户、项目当天总工时
                    working_count('user', $this->_G['uid'], $this->_G['username'], $consumed);
                    working_count('product', $task['product'], $this->_G['username'], $consumed);
                    working_count('project', $task['project'], $this->_G['username'], $consumed);
                    //完成任务
                } elseif ($ac == 'done') {
                    $task_data = [
                        'status' => 'done',
                        'finishedBy' => $this->_G['username'],
                        'finishedDate' => date('Y-m-d H:i:s'),
                    ];
                    //如果已输入工时
                    if ($consumed > 0) {
                        DB::name('Task')->where(['id' => $task_id])->setInc('consumed', $consumed);
                        //工时信息写入
                        $work_data = [
                            'username' => $this->_G['username'],
                            'task' => $task_id,
                            'work' => trim(input('param.work')),
                            'date' => date('Y-m-d'),
                            'consumed' => $consumed,
                            'left' => input('left', 0, 'intval'), //剩余
                        ];
                        $taskestimate_id = DB::table('chinatt_pms_taskestimate')->insertGetId($work_data);
                        //如果未输入工时
                    } else {
                        $estimate_list = DB::name('taskestimate')->where(['task' => $task_id])->select();
                        //总工时为0 不允许提交
                        if (empty($estimate_list)) {
                            $message = array('error' => '参数错误');
                            $data = json_encode($message);
                            echo $data;
                            exit();
                        }
                    }
                    //记录产品、用户、项目当天总工时
                    working_count('user', $this->_G['uid'], $this->_G['username'], $consumed);
                    working_count('product', $task['product'], $this->_G['username'], $consumed);
                    working_count('project', $task['project'], $this->_G['username'], $consumed);
                    DB::table('chinatt_pms_task')->where(['id' => $task_id])->update($task_data);
                    write_action($this->_G['username'], $task['project'], 'task', $task_id, 'done', input('work'));
                    //指派任务
                } elseif ($action == 'assign') {
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
        $this->assign('user_info', $user_info);
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
        //访问权限判断
        if ($project_detail['acl'] == 'private' && !isProjectUser($this->_G['username'], $user_list)) {
            $this->error('您无该项目访问权限。');
        }

        if (request()->isPost()) {
            $work_data = [
                'username' => $this->_G['username'],
                'task' => $task_id,
                'work' => trim(input('param.work')),
                'consumed' => input('post.consumed', '0', 'intval'), //消耗
                'date' => date('Y-m-d'),
                'left' => input('param.left', 0, 'intval')//剩余
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
       
         $predecessor_data['status'] = array('in',"wait,doing");
         $predecessor_data['predecessor'] = array('gt',"0");
        $predecessor_list = DB('Task')->where($predecessor_data)->select();
        if (request()->isPost()) {
            $task_data = [
                'project' => $project_id,
                'name' => input('name', '', 'addslashes'),
                'estimate' => input('param.estimate'),
                'desc' => trim(input('param.desc')),
                'openedBy' => $this->_G['username'],
                'type' => input('param.type'),
                'assignedTo' => input('param.assignedTo'),
                'predecessor' => input('post.predecessor', '0', 'intval'), //前置任务ID
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
                write_action($this->_G['username'], $project_id, 'task', $task_id, 'updata');
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
        $this->assign('predecessor_list',$predecessor_list);
        $this->assign('project_list', $project_list);
        $this->assign('task_details', $task_details);
        $this->assign('user_list', $user_list);
        $this->assign('task_id', $task_id);
        return $this->fetch($this->templatePath);
    }

}
