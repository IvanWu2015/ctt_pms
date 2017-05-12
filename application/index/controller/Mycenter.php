<?php

namespace app\index\controller;

load_trait('controller/Jump');  // 引入traits\controller\Jump

use \traits\controller\Jump;
use \think\Db;

class Mycenter extends Common {

    public function _initialize() {
        parent::_initialize();
        //用户列表
        $user_list = DB::name('user')->column('uid,username,realname', 'username');
        $this->assign('user_list', $user_list);
    }

    //首页
    public function index() {
        //总预计工时
        $estimate_count = DB::name('Task')->where(['assignedTo' => $this->_G['username'], 'deleted' => 0])->sum('estimate');
        //总消耗工时
        $consumed_count = DB::name('taskestimate')->where(['username' => $this->_G['username']])->sum('consumed');
        //我的任务总数
        $my_task_count = DB::name('Task')->where(['assignedTo' => $this->_G['username'], 'deleted' => 0])->count();
        //我的动态总数
        $my_action_count = DB::name('Action')->where(['actor' => $this->_G['username']])->count();
        //我的收藏网址总数
        $my_weburl_count = DB::name('Weburl')->where(['uid' => $this->_G['uid'], 'status' => 0])->count();
        //我的文章总数
        $my_article_count = DB::name('Article')->where(['uid' => $this->_G['uid'], 'status' => 0])->count();
        $not_status_data['status'] = array('in', 'wait,doing');
        $not_status_data['assignedTo'] = array('eq', $this->_G['username']);
        $not_status_data['deleted'] = array('eq', '0');
        $not_status_count = DB::name('Task')->where($not_status_data)->count();
        $headDate = date('Y-m-01 00:00:00', strtotime(date("Y-m-d")));
        $estimate_map['deleted'] = array('eq', 0);
        $estimate_map['finishedBy'] = array('eq', $this->_G['username']);
        $estimate_map['finishedDate'] = array('GT', $headDate);
        $same_month_estimate_count = DB::name('Task')->where($estimate_map)->sum('estimate'); //当月预计工时
        $user_count = get_count($this->_G['username']);
        $consumed_map['username'] = array('eq', $this->_G['username']);
        $consumed_map['date'] = array('GT', $headDate);
        $same_month_consumed_count = DB::name('Taskestimate')->where($consumed_map)->sum('consumed'); //当月完成工时
        $user = db('User')->alias('u')->join('chinatt_pms_dept d', 'u.dept = d.id', 'left')->join('chinatt_pms_group g', 'u.groupid = g.id')->field('u.*,d.name as depe_name,g.name as group_name')->where(['uid' => $this->_G['uid']])->find();
        //未完成任务
        $task_list = db('Task')->where(['assignedTo' => $this->_G['username'], 'status' => 'wait'])->order('id DESC')->select();
        $task_list = format_task($task_list);
        //动态
        $action_list = DB('Action')
                ->alias('a')
                ->join('chinatt_pms_taskestimate b', 'a.extra =b.id', 'left')
                ->join('chinatt_pms_task t', 'a.objectType = \'task\' AND a.objectID =t.id', 'left')
                ->join('chinatt_pms_task p', 'a.objectType = \'project\' AND a.objectID =p.id', 'left')
                ->field('a.*,b.left,b.consumed,b.username,t.name as tname, p.name as pname')
                ->order('id DESC')
                ->paginate(10);
        $action_list = analysis_all($action_list);
        //网址列表
        $weburl_list = DB::name('weburl')->alias('w')->join('chinatt_pms_project p', 'w.project = p.id', 'left')->field('w.*,p.name')->where(['w.uid' => $this->_G['uid']])->order('id DESC')->paginate(10);
        //文档列表
        $article_list = DB::name('Article')
                ->alias('a')
                ->join('chinatt_pms_class c', 'a.class = c.id', 'left')
                ->join('chinatt_pms_project p ', 'a.project = p.id', 'left')
                ->join('chinatt_pms_user u ', 'a.uid = u.uid', 'left')
                ->field('a.*,c.name as class_name,p.name as project_name,u.username')
                ->where(['a.status' => 0, 'a.uid' => $this->_G['uid']])
                ->paginate(10);
        $navtitle = '个人中心' . $this->navtitle;
        $this->assign('same_month_consumed_count', $same_month_consumed_count);
        $this->assign('same_month_estimate_count', $same_month_estimate_count);
        $this->assign('today_count', $today_count);
        $this->assign('toweek_count', $toweek_count);
        $this->assign('not_status_count', $not_status_count);
        $this->assign('my_article_count', $my_article_count);
        $this->assign('my_weburl_count', $my_weburl_count);
        $this->assign('my_action_count', $my_action_count);
        $this->assign('my_task_count', $my_task_count);
        $this->assign('estimate_count', $estimate_count);
        $this->assign('consumed_count', $consumed_count);
        $this->assign('article_list', $article_list);
        $this->assign('weburl_list', $weburl_list);
        $this->assign('user_count', $user_count);
        $this->assign('action_list', $action_list);
        $this->assign('task_list', $task_list);
        $this->assign('user', $user);
        $this->assign('navtitle', $navtitle);
        return $this->fetch($this->templatePath);
    }

    //我的动态
    public function action_list() {
        $map['a.actor'] = array('eq', $this->_G['username']);
        $action_list = DB('Action')
                ->alias('a')
                ->join('chinatt_pms_taskestimate b', 'a.extra =b.id', 'left')
                ->join('chinatt_pms_task t', 'a.objectType = \'task\' AND a.objectID =t.id', 'left')
                ->join('chinatt_pms_task p', 'a.objectType = \'project\' AND a.objectID =p.id', 'left')
                ->where($map)
                ->field('a.*,b.left,b.consumed,b.username,t.name as tname, p.name as pname')
                ->order('id DESC')
                ->paginate(30, $action_count, ['path' => url('/index/mycenter/action_list/')]);

        $page = $action_list->render(); // 分页显示输出
        $navtitle = '我的动态';
        $this->assign('navtitle', $navtitle);
        $action_list = analysis_all($action_list);
        $this->assign('page', $page);
        $this->assign('action_list', $action_list);
        return $this->fetch($this->templatePath);
    }

    //我的收藏
    public function weburl_list() {
        $map['w.uid'] = array('eq', $this->_G['uid']);
        $weburl_list = DB::name('weburl')->alias('w')->join('chinatt_pms_project p', 'w.project = p.id', 'left')->field('w.*,p.name')->where($map)->paginate(10);
        $page = $weburl_list->render(); // 分页显示输出
        $navtitle = '我的收藏';
        $this->assign('navtitle', $navtitle);
        $this->assign('page', $page);
        $this->assign('weburl_list', $weburl_list);
        return $this->fetch($this->templatePath);
    }

    //我的文章
    public function article_list() {
        $article_list = DB::name('Article')
                ->alias('a')
                ->join('chinatt_pms_class c', 'a.class = c.id', 'left')
                ->join('chinatt_pms_project p ', 'a.project = p.id', 'left')
                ->join('chinatt_pms_user u ', 'a.uid = u.uid', 'left')
                ->field('a.*,c.name as class_name,p.name as project_name,u.username')
                ->where(['a.status' => 0, 'a.uid' => $this->_G['uid']])
                ->order('id desc')
                ->paginate(10);
        $page = $article_list->render(); // 分页显示输出
        $navtitle = '我的文章';
        $this->assign('navtitle', $navtitle);
        $this->assign('page', $page);
        $this->assign('article_list', $article_list);
        return $this->fetch($this->templatePath);
    }

    //任务详情页
    public function task_list() {
        $username = get_username();
        $status = input('get.status', 'unclose', 'addslashes');
        $project_id = input('get.project_id', '0', 'intval');
        $map_count['assignedTo'] = $username;
        $map['t.assignedTo'] = $username;
        if ($status == 'unclose') {
            $map_count['status'] = array('in', 'wait,doing');
            $map['t.status'] = array('in', 'wait,doing');
        } elseif ($status == 'all') {
            
        } elseif (!empty($status)) {
            $map_count['status'] = $status;
            $map['t.status'] = $status;
        }
        if ($project_id > 0) {
            $map_count['project'] = array('eq', $project_id);
            $map['t.project'] = array('eq', $project_id);
        }
        $project_list = DB::name('Team')
                ->alias('t')
                ->join('chinatt_pms_project p', 't.project = p.id', 'left')
                ->field('t.*,p.name,p.id as project_id,p.deleted')
                ->where([ 't.username' => $this->_G['username']])
                ->order('id desc')
                ->paginate(10);
        $task_count = DB::table('chinatt_pms_task')->where($map_count)->count();
        $task_list = DB::table('chinatt_pms_task')
                ->alias('t')
                ->join('chinatt_pms_task p', 't.predecessor = p.id', 'left')
                ->join('chinatt_pms_config c',"t.type = c.c_key AND c.status = 1",'left')
                ->join('chinatt_pms_user u',"t.openedBy = u.username",'left')
                ->join('chinatt_pms_user s',"t.assignedTo = s.username",'left')
                ->where($map)
                ->field('t.*,p.status as p_status,p.name as p_name,u.realname,s.realname as assignedTo_name,c.c_value as type_name')
                ->order("id DESC")
                ->paginate(20, $task_count, ['path' => url('/index/mycenter/task_list/'), 'query' => ['username' => $username, 'status' => $status, 'project_id' => $project_id]]);
        $navtitle = '个人中心';
        $show = $task_list->render(); // 分页显示输出

        $this->assign('project_id', $project_id);
        $this->assign('project_list', $project_list);
        $this->assign('page', $show);
        $this->assign('task_list', $task_list);
        $this->assign('status', $status);
        $this->assign('navtitle', $navtitle);
        return $this->fetch($this->templatePath);
    }

    public function project() {
        $project_list = DB::name('Team')
                ->alias('t')
                ->join('chinatt_pms_project p', 't.project = p.id', 'left')
                ->field('t.*,p.name,p.id as project_id,p.deleted')
                ->where([ 't.username' => $this->_G['username']])
                ->order('id desc')
                ->paginate(10);
        $navtitle = '我的项目' . $navtitle;
        $this->assign('navtitle', $navtitle);
        $this->assign('project_list', $project_list);
        return $this->fetch($this->templatePath);
    }

    public function info() {
        $type = input('param.type');
        if ($type == 'user_info') {
            if (request()->isPost()) {
                $data = [
                    'realname' => input('post.realname', '', 'addslashes'),
                    'nickname' => input('post.nickname', '', 'addslashes'),
                    'birthday' => input('param.birthday'),
                    'gender' => input('post.gender', '', 'addslashes'),
                    'qq' => input('post.qq', '', 'intval'),
                    'email' => input('post.email', '', 'addslashes'),
                    'mobile' => input('post.mobile', '', 'intval'),
                ];
                DB::name('User')->where(['uid' => $this->_G['uid'], 'deleted' => 0])->update($data);
                write_action($this->_G['username'], 0, 'user', $this->_G['uid'], 'update', $data);
                $message = array('result' => 'success', 'error' => '');
                $data = json_encode($message);
                echo $data;
                exit();
            }
        }
        if ($type == 'file') {
            $image = M('Image');
            $data = $image->order('create_time desc')->find();    //获取最后上传图片
            $this->assign('data', $data);
            $this->display();
        }
    }

    public function upload() {
        // 获取表单上传文件
        var_dump($_FILES);
        exit;
        $files = request()->file('avatar');
        foreach ($files as $file) {
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');

            if ($info) {
                // 成功上传后 获取上传信息
                // 输出 jpg
                echo $info->getExtension();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getFilename();
            } else {
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }

}
