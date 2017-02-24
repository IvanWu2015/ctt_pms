<?php

namespace app\admin\controller;

use \traits\controller\Jump;
use \think\Db;
use Page;

class Index extends Common {

    public function index() {
        
        $project_count = DB::name('Project')->where(['deleted' => 0])->count();//项目总数
        $task_count = DB::name('Task')->where(['deleted' => 0])->count();//任务总数
        $user_count = DB::name('User')->where(['deleted' => 0])->count();//用户总数
        $weburl_count = DB::name('Weburl')->where(['status' => 0])->count();//收藏总数
        $article_count = DB::name('Article')->where(['status' => 0])->count();//文章总数
        $project_list = Db::name('Project')->where(['deleted' => 0])->order("id DESC")->paginate(5);
        $page = $project_list->render(); // 分页显示输出
        //将对象转为数组
        $project_list = $project_list->toArray();
        $project_list = get_project_consume($project_list['data']);//带有进度的项目列表
        
        $task_data['status'] = array('in','doing,wait');
        $task_data['deleted'] = array('eq',0);
        $task_list = DB::name('Task')->where($task_data)->order('openedDate DESC')->paginate(7);
        $task_list = format_task($task_list);
        
        $headDate = date('Y-m-d 00:00:00', strtotime(date("Y-m-d")));
        $bomttomDate =  date('Y-m-d 23:59:59', strtotime(date("Y-m-d")));
        $today_data['finishedDate'] = array('in','$headDate,$bomttomDate');
        $today_data['deleted'] = array('eq',0);
        $today_task = DB::name('Task')->where($today_data)->order('finishedBy DESC')->paginate(7);
        
        $article_list = DB::name('Article')->where(['status' => 0])->order('time DESC')->paginate(7);
        $navtitle = '后台首页';
        $this->assign('navtitle', $navtitle);
        $this->assign('article_list',$article_list);
        $this->assign('task_list',$task_list);
        $this->assign('project_list', $project_list);
        $this->assign('project_count', $project_count);
        $this->assign('task_count', $task_count);
        $this->assign('user_count', $user_count);
        $this->assign('weburl_count', $weburl_count);
        $this->assign('article_count', $article_count);



        return $this->fetch($this->templatePath);
    }

}
