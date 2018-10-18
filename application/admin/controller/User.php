<?php

namespace app\admin\controller;

use \traits\controller\Jump;
use \think\Db;
use Page;
use tree;

load_trait('controller/Jump');  // 引入traits\controller\Jump
    
class User extends Common {

    public function index() {
        return $this->fetch($this->templatePath);
    }

    public function lists() {   //用户列表
        if (request()->isPost()) {
            foreach ($_POST['is_admin'] as $key => $value) {
                if ($value == 1) {
                    $ids[] = $key;
                }
            }
            $data['uid'] = array('in', $ids);
            DB('User')->where($data)->update(['isadmin' => 1]);
        }

        $user_list = db('User')
                ->alias('u')
                ->join('chinatt_pms_dept d', 'u.dept = d.id', 'left')
                ->join('chinatt_pms_group g', 'u.groupid = g.id', 'left')
                ->field('u.*,d.name as depe_name,g.name as group_name')
                ->where(['deleted' => 0])
                ->order('uid DESC')
                ->paginate(10);
        $user_list = get_user_count($user_list);
        $show = $user_list->render(); // 分页显示输出
        $user_list = user_count($user_list);
        $navtitle = '用户列表' . $this->navtitle;
        $this->assign('navtitle', $navtitle);
        $this->assign('page', $show);
        $this->assign('user_list', $user_list);
        return $this->fetch($this->templatePath);
    }

    public function detail() {   //用户详情
        $username = input('get.username', '', 'addslashes');
        $estimate_count = DB::name('Task')->where(['assignedTo' => $username, 'deleted' => 0])->sum('estimate');
        $consumed_count = DB::name('Task')->where(['finishedBy' => $username, 'deleted' => 0])->sum('consumed');
        $my_task_count = DB::name('Task')->where(['assignedTo' => $username, 'deleted' => 0])->count();
        $my_action_count = DB::name('Action')->where(['actor' => $username])->count();
        $my_weburl_count = DB::name('Weburl')->where(['username' => $username, 'status' => 0])->count();
        $my_article_count = DB::name('Article')->where(['username' => $username, 'status' => 0])->count();
        $time = date('Y-m-d');
        $open_task_data['openedDate'] = array('like',"%$time%");
        $today_open_task_count = DB::name('Task')->where(['openedBy' => $username, 'deleted' => 0])->where($open_task_data)->count();//当天创建任务数
        $done_task_data['finishedDate'] = array('like',"%$time%");
        $today_done_task_count = DB::name('Task')->where(['finishedBy' => $username, 'deleted' => 0])->where($done_task_data)->count();//当天完成任务数
        
        
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
        //今天总工时
        $today_count = DB::name('Workcount')->where(['username' => $this->_G['username'], 'date' => date('Y-m-d'), 'objectType' => 'user'])->field('consumed')->find();

        //获取当周的第一天与最后一天
        $sdefaultDate = date("Y-m-d");
        $first = 1;
        $w = date('w', strtotime($sdefaultDate));
        $week_start = date('Y-m-d', strtotime("$sdefaultDate -" . ($w ? $w - $first : 6) . ' days'));
        $week_end = date('Y-m-d', strtotime("$week_start +6 days"));
        $toweek_data['date'] = array('between time', "$week_start,$week_end");
        $toweek_data['username'] = array('eq', $this->_G['username']);
        $toweek_data['objectType'] = array('eq', 'user');
        //当周总工时
        $toweek_count = DB::name('Workcount')->where($toweek_data)->field('consumed')->sum('consumed');

        $user = db('User')
                ->alias('u')
                ->join('chinatt_pms_dept d', 'u.dept = d.id', 'left')
                ->join('chinatt_pms_group g', 'u.groupid = g.id')
                ->field('u.*,d.name as depe_name,g.name as group_name')
                ->where(['u.username' => $this->_G['username']])
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
        $weburl_list = DB::name('weburl')->alias('w')->join('chinatt_pms_project p', 'w.project = p.id', 'left')->field('w.*,p.name')->where(['w.username' => $username])->order('id DESC')->paginate(10);
        //文档列表
        $article_list = DB::name('Article')
                ->alias('a')
                ->join('chinatt_pms_class c', 'a.class = c.id', 'left')
                ->join('chinatt_pms_project p ', 'a.project = p.id', 'left')
                ->field('a.*,c.name as class_name,p.name as project_name')
                ->where(['a.status' => 0, 'a.username' => $username])
                ->paginate(10);
        
        $project_list = db('project')
                        ->alias('p')
                        ->join('chinatt_pms_team t', "t.project = p.id ", 'left')
                        ->where(function ($query) {
                            $data['status'] = array('in','wait,doing');
                            $query->where(['p.acl' => 'private', 't.username' => 'chen'])->where($data);
                        })->whereOr(function ($query) {
                            $data['status'] = array('in','wait,doing');
                            $query->where(['p.acl' => 'open'])->where($data);
                        })
                        ->group('p.id')->select();






        $navtitle = '个人中心' . $this->navtitle;
        $this->assign('same_month_consumed_count', $same_month_consumed_count);
        $this->assign('same_month_estimate_count', $same_month_estimate_count);
        $this->assign('not_status_count', $not_status_count);
        $this->assign('today_count', $today_count);
        $this->assign('toweek_count', $toweek_count);
        $this->assign('my_article_count', $my_article_count);
        $this->assign('my_weburl_count', $my_weburl_count);
        $this->assign('my_action_count', $my_action_count);
        $this->assign('today_open_task_count',$today_open_task_count);
        $this->assign('today_done_task_count',$today_done_task_count);
        $this->assign('my_task_count', $my_task_count);
        $this->assign('project_list',$project_list);
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

    /**
     * [add_user 添加用户和编辑用户]
     * @author 逝水流 2018-10-18
     */
    public function add_user() {
        $uid    = input('param.uid', '0', 'intval');
        // 定义请求方法
        $type   = !empty($uid) ? 'edit' : 'add';
        $userTb = db('user');
        
        if (request()->isPost()) {
            $data = [
                'realname' => input('post.realname'),
                'username' => input('post.username'),
                'password' => input('post.password'),
                'dept'     => input('post.dept', '0', 'intval'),
                'groupid'  => input('post.group', '0', 'intval'),
                'email'    => input('post.email'),
                'mobile'   => input('post.mobile'),
                'birthday' => input('post.birthday'),
                'isadmin'  => input('post.isadmin', '0', 'intval')
            ];
            
            $data['gender'] = input('post.gender') == '1' ? 'm' : 'f';
            $data['email']  = filter_var($data['email'], FILTER_VALIDATE_EMAIL) ? $data['email'] : '';
            $data['mobile'] = preg_match('/^1[345678]{1}\d{9}$/', $data['mobile']) ? $data['mobile'] : '';

            // 验证必填字段合法性
            empty($data['realname']) && $this->error('姓名不能为空');
            empty($data['username']) && $this->error('账号不能为空');
            // 账号是否已经存在
            $old_name = input('post.old_name');
            if ($data['username'] != $old_name) {
                $isExsists = $userTb
                    ->field('uid')
                    ->where(['username' => $data['username']])
                    ->find();
                !empty($isExsists) && $this->error('账号已经存在');
            }

            if ($type == 'add') {
                empty($data['password']) && $this->error('密码不能为空');

                $data['salt']     = random(6);
                $data['password'] = md5(md5($data['password']) . $data['salt']);
                $data['ip']       = request()->ip();
                $data['join']     = date('Y-m-d', time());
                // 插入记录
                $res = $userTb->insert($data);
                if ($res) {
                    $this->success('添加成功', url('User/lists'));
                } else {
                    $this->error('未知错误');
                }
            }

            if ($type == 'edit') {
                // 密码处理
                if (empty($data['password'])) {
                    unset($data['password']);
                } else {
                    $data['salt']     = random(6);
                    $data['password'] = md5(md5($data['password']) . $data['salt']);
                }
                // 更新记录
                $res = $userTb->where(['uid' => $uid])->update($data);
                if ($res) {
                    $this->success('修改成功', url('User/lists'));
                } else {
                    $this->error('修改失败或者未做修改');
                }
            }
        } else {
            $depList = db('dept')
                ->field('id, name, parent')
                ->select();
            $tree = new Tree($depList);

            $groupList = db('group')
                ->field('id, name')
                ->select();

            if ($type == 'add') {
                $this->assign('title', '添加用户');
            }

            if ($type == 'edit') {
                $userInfo = $userTb
                    ->alias('u')
                    ->join('chinatt_pms_dept d', 'd.id = u.dept', 'left')
                    ->join('chinatt_pms_group g', 'g.id = u.groupid')
                    ->field('dept, groupid, username, realname, gender, email, mobile, birthday, isadmin')
                    ->where(['uid' => $uid])
                    ->find();
                $userInfo['uid'] = $uid;

                $this->assign('user_info', $userInfo);
                $this->assign('title', '修改用户');
            }

            $this->assign('dep_list', $tree->getArray());
            $this->assign('group_list', $groupList);
            return $this->fetch($this->templatePath);
        }
    }

    public function add_user_1() {

        if (request()->isPost()) {
            $data = [
                'dept'     => input('post.dept', '0', 'intval'),
                'groupid'  => input('post.group', '0', 'intval'),
                'username' => input('post.username'),
                'realname' => input('post.realname'),
                'password' => input('post.password'),
                'isadmin'  => input('post.isadmin', '0', 'intval'),
                'salt'     => random(6)
            ];
            $data['password'] = md5(md5($data['password']) . $data['salt']);
            if (input('post.gender') == 1) {
                $data['gender'] = 'm';
            } elseif (input('post.gender') == 2) {
                $data['gender'] = 'f';
            }
            $data['ip']     = \think\Request::instance()->ip();
            $data['email']  = filter_var(input('post.email'), FILTER_VALIDATE_EMAIL) ? input('post.email') : '';
            $data['mobile'] = preg_match('/^1[345678]{1}\d{9}$/', input('post.mobile')) ? input('post.mobile') : '';
            $data['join']   = date('Y-m-d', time());

            if (isset($data['email']) && !$data['email']) {
                $this->error('邮箱格式错误');
            }
            if (isset($data['mobile']) && !$data['mobile']) {
                $this->error('手机格式错误');
            }

            // 验证必填字段合法性
            if (empty($data['realname'])) {
                $this->error('姓名不能为空');
            }
            if (empty($data['username'])) {
                $this->error('账号不能为空');
            }
            if (empty($data['password'])) {
                $this->error('密码不能为空');
            }

            // 账号是否存在
            $uid = db('user')
                ->field('uid')
                ->where(['username' => $data['username']])
                ->find();

            if ($uid) {
                $this->error('账号已经存在');
            } else {
                $res = db('user')->insert($data);
                if ($res) {
                    $this->success('添加成功', url('User/lists'));
                } else {
                    $this->error('未知错误');
                }
            }
        } else {
            $depList = db('dept')
                ->field('id, name, parent')
                ->select();

            $tree = new Tree($depList);

            $groupList = db('group')
                ->field('id, name')
                ->select();

            $this->assign('title', '添加用户');
            $this->assign('dep_list', $tree->getArray());
            $this->assign('group_list', $groupList);
            return $this->fetch($this->templatePath);
        }
    }

    public function edit($uid = '') {
        if (request()->isPost()) {
            $data = [
                'dept'     => input('post.dept', '0', 'intval'),
                'groupid'  => input('post.group', '0', 'intval'),
                'username' => input('post.username'),
                'realname' => input('post.realname'),
                'isadmin'  => input('post.isadmin', '0', 'intval')
            ];
            if (null != input('post.password')) {
                $data['salt']     = random(6);
                $data['password'] = md5(md5(input('post.password')) . $data['salt']);
            }
            if (input('post.gender') == 1) {
                $data['gender'] = 'm';
            } elseif (input('post.gender') == 2) {
                $data['gender'] = 'f';
            }
            $data['email']  = filter_var(input('post.email'), FILTER_VALIDATE_EMAIL) ? input('post.email') : '';
            $data['mobile'] = preg_match('/^1[345678]{1}\d{9}$/', input('post.mobile')) ? input('post.mobile') : '';

            if (isset($data['email']) && !$data['email']) {
                $this->error('邮箱格式错误');
            }
            if (isset($data['mobile']) && !$data['mobile']) {
                $this->error('手机格式错误');
            }

            // 验证必填字段合法性
            if (empty($data['realname'])) {
                $this->error('姓名不能为空');
            }
            if (empty($data['username'])) {
                $this->error('账号不能为空');
            }
            $res = db('user')->where(['uid' => input('post.uid', 0, 'intval')])->update($data);
            if ($res) {
                $this->success('修改成功');
            } else {
                $this->error('修改失败或者未做修改');
            }
        } else {
            $userInfo = db('user')
            ->alias('u')
            ->join('chinatt_pms_dept d', 'd.id = u.dept', 'left')
            ->join('chinatt_pms_group g', 'g.id = u.groupid')
            ->field('dept, groupid, username, realname, gender, email, mobile, isadmin')
            ->where(['uid' => $uid])
            ->find();

            $userInfo['uid'] = $uid;

            $depList = db('dept')
                ->field('id, name, parent')
                ->select();

            $tree = new Tree($depList);

            $groupList = db('group')
                ->field('id, name')
                ->select();

            $this->assign('dep_list', $tree->getArray());
            $this->assign('group_list', $groupList);
            $this->assign('user_info', $userInfo);

            return $this->fetch($this->templatePath);
        }
        
    }

    public function delete($uid) {
        $res = db('user')->where(['uid' => $uid])->update(['deleted' => 1]);
        if ($res) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    public function add() {
        $uid = input('get.uid', 0, 'intval');
        $user_detail = DB('User')->where(['uid' => $uid])->find();
        if (empty($user_detail)) {
            $this->error("不存在该用户");
        }
        if ($user_detail['uid'] != $this->_G['uid'] && $this->_G['is_admin'] != 1) {
            $this->error("权限不足");
        }
    }

}
