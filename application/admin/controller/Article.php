<?php

namespace app\admin\controller;
use \traits\controller\Jump;
use \think\Db;
use Page;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Article extends Common {
    public function index() {
        return $this->fetch($this->templatePath);
    }
    public function lists() {
        $article_list = DB::name('Article')
                ->alias('a')
                ->join('chinatt_pms_class c', 'a.class = c.id', 'left')
                ->join('chinatt_pms_project p ', 'a.project = p.id', 'left')
                ->join('chinatt_pms_user u ', 'a.uid = u.uid', 'left')
                ->field('a.*,c.name as class_name,p.name as project_name,u.username')
                ->where(['a.status' => 0])
                ->paginate(10);
        $page = $article_list->render(); // 分页显示输出
        
        $deleted = input('param.deleted', 0, 'intval');
        $article_id = input('param.id', 0, 'intval');
        if($deleted > 0){
            $article_detail = DB::name('Article')->where(['status' => 0])->find();
            if($this->_G['is_admin']){
                DB::name('Article')->where(['id' => $article_id])->update(['status' => -1]);
                $this->success('删除成功','admin/article/lists');
            }
        }   
        $navtitle = '文档管理';
        $this->assign('navtitle', $navtitle);
        $this->assign('page', $page);
        $this->assign('article_list', $article_list);
        
        
        
        return $this->fetch($this->templatePath);
    }
    
}



