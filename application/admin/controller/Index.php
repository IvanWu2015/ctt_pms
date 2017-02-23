<?php

namespace app\admin\controller;
use \traits\controller\Jump;
use \think\Db;
use Page;


class Index extends Common {
    
    public function index() {
        $project_count = DB::name('Project')->where(['deleted' => 0])->count();
        $task_count = DB::name('Task')->where(['deleted' => 0])->count();
         $user_count = DB::name('User')->where(['deleted' => 0])->count();
         $weburl_count = DB::name('Weburl')->where(['status' => 0])->count();
         $article_count = DB::name('Article')->where(['status' => 0])->count();
        
        
        
        
        
        
        
         $this->assign('project_count',$project_count);
        $this->assign('task_count',$task_count);
        $this->assign('user_count',$user_count);
        $this->assign('weburl_count',$weburl_count);
        $this->assign('article_count',$article_count);
        
        
        
        return $this->fetch($this->templatePath);
    }
    
}



