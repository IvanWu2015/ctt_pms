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
                ->join('chinatt_pms_task k', 't.predecessor = k.id', 'left')
                ->where(['t.id' => $task_id])
                ->field('t.*,p.product,k.status as predecessor_name')
                ->find();
        if ($task['predecessor_name'] == 'doing' || $task['predecessor_name'] == 'wait') {
            $json_data = array('result' => 'failed', 'error' => '请先完成该任务的前置任务。');
            //$data = json_encode($message);
            return $json_data;
        }

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
                $work = input('work', '', 'addslashes');
                if (empty($work)) {
                    $message = array('message' => '');
                    $data = json_encode($message);
                    echo $data;
                    exit();
                }
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
                } elseif ($ac == 'cancel') {
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
                    }
                    $task_data = [
                        'canceledBy' => $this->_G['username'],
                        'canceledDate' => date('Y-m-d H:i:s'),
                        'desc' => input('param.desc'),
                        'status' => 'cancel',
                    ];
                    DB::table('chinatt_pms_task')->where(['id' => $task_id])->update($task_data);
                    write_action($this->_G['username'], $task['project'], 'task', $task_id, 'cancel');
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
        //任务详情
        $task_detail = DB::table('chinatt_pms_task')
                ->alias('t')
                ->join('chinatt_pms_task p', 't.predecessor = p.id', 'left')
                ->join('chinatt_pms_plan a', 't.plan = a.id', 'left')
                ->where(['t.id' => $task_id])
                ->field('t.*,p.name as predecessor_name,a.title as plan_title')
                ->find();
        if (empty($task_detail)) {
            $this->error('任务不存在。');
        }
        $user = $this->_G;
        $user_list = DB::table('chinatt_pms_user')->select();//用户列表
        $data['task'] = array('EQ', $task_id);
        $work_list = DB::table('chinatt_pms_taskestimate')->where($data)->order('id')->select();//该任务的工时列表
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
        $this->assign('user', $user);
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
        $username = $this->_G['username'];
        //修改状态判断任务是否超时
        if ($task_id > 0) {
            $task_details = $task->where(['id' => $task_id])->find();//任务详情
            $project_detail = DB('Project')->where(['id' => $task_details['project']])->find();//项目详情
            $project_id = $task_details['project'];
            $big_time_ten = strtotime($task_details['openedDate']) + 10 * 60;//任务创建后10分钟
            if (time() > $big_time_ten && $this->_G['is_admin'] != 1) {
                $this->error("超时，无法修改");
            }
        }
        //如果有所属项目则获取成员列表
        if ($project_id > 0) {
            $user_list = get_userlist_by_projectid($project_id);
        }
        $project_detail = DB('Project')->where(['id' => $project_id])->find();
        //该用户是否为任务分配成员、是否为管理员、是否为项目管理员、是否为项目成员
        if ($this->_G['username'] != $task_details['assignedTo'] && $this->_G['is_admin'] != 1 && $this->_G['username'] != $project_detail['project_admin'] && in_array($this->_G['username'], $user_list)) {
            $this->error("权限不足");
        }
        $map['status'] = array('not in', 'closed,done');
        //管理员读取公开与私有
        if (!$this->_G['is_admin']) {
            $map['acl'] = array('eq', 'open');
        }
        $project_list = Db::name('Project')->where($map)->column('id,name,code', 'id');//项目列表
        $plan_list = Db::name('Plan')->where(['deleted' => 0, 'project' => $project_id])->select();//项目的需求列表
        $predecessor_data['status'] = array('in', "wait,doing");
        $predecessor_data['project'] = array('eq', $project_id);
        $predecessor_list = DB('Task')->where($predecessor_data)->select();//该项目的前置任务列表
        $config_list = DB('Config')->where(['status' => 1,'group' => 'task_type'])->select();
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
                'status' => input('param.status', 'wait', 'addslashes'),
                'plan' => input('param.plan', '0', 'intval'),
            ];
            //编辑
            if ($task_id > 0) {
                DB::table('chinatt_pms_task')->where(['id' => $task_id])->update($task_data);
                //关联需求的内容
                if (input('param.plan', '0', 'intval') > 0) {
                    DB('Plan')->where(['id' => input('param.plan', '0', 'intval')])->update(['task' => $task_id, 'project' => $project_id, 'product' => $project_detail['product']]);
                }
                //操作记录
                write_action($this->_G['username'], $project_id, 'task', $task_id, 'updata');
                $this->success("修改成功", url("/Index/Project/detail/?id=$project_id"));
                exit;
            //添加
            } else {
                $task_id = DB::table('chinatt_pms_task')->insertGetId($task_data);
                //关联需求的内容
                if (input('param.plan', '0', 'intval') > 0) {
                    DB('Plan')->where(['id' => input('param.plan', '0', 'intval')])->update(['task' => $task_id, 'project' => $project_id, 'product' => $project_detail['product']]);
                }
                //操作记录
                write_action($this->_G['username'], $project_id, 'task', $task_id, 'opened');
                $this->success("成功添加", url("/Index/Project/detail/?id=$project_id"));
                exit;
            }
        }
        $navtitle = '任务添加/编辑' . $project_detail['name'];
        $this->assign('navtitle', $navtitle);
        $this->assign('config_list',$config_list);
        $this->assign('project_id', $project_id);
        $this->assign('predecessor_list', $predecessor_list);
        $this->assign('project_list', $project_list);
        $this->assign('plan_list', $plan_list);
        $this->assign('username', $username);
        $this->assign('task_details', $task_details);
        $this->assign('user_list', $user_list);
        $this->assign('task_id', $task_id);
        return $this->fetch($this->templatePath);
    }
}