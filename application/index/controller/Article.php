<?php

/**
 * 网址收藏
 */

namespace app\index\controller;

load_trait('controller/Jump');  // 引入traits\controller\Jump

use \traits\controller\Jump;
use \think\Db;

class article extends Common {

    //首页
    public function index() {
        
    }

    //列表页
    public function lists() {
        $acl = input('param.acl', 'all', 'addslashes');
        if($acl == 'all'){
            
        }  else {
            $data['a.acl'] = array('eq',$acl);
        }
        $data['a.uid'] = array('eq',$this->_G['uid']);
        
        $article_list = DB::name('Article')
                ->alias('a')
                ->join('chinatt_pms_class c', 'a.class = c.id', 'left')
                ->join('chinatt_pms_project p ', 'a.project = p.id', 'left')
                ->join('chinatt_pms_user u ', 'a.uid = u.uid', 'left')
                ->field('a.*,c.name as class_name,p.name as project_name,u.username')
                ->where($data)
                ->order('id desc')
                ->paginate(10);
        $page = $article_list->render(); // 分页显示输出
        
        $deleted = input('param.deleted', 0, 'intval');
        $article_id = input('param.id', 0, 'intval');
        if($deleted > 0){
            $article_detail = DB::name('Article')->where(['uid' => $this->_G['uid'],'status' => 0])->find();
            if($this->_G['uid'] == $article_detail['uid']){
                DB::name('Article')->where(['id' => $article_id])->update(['status' => -1]);
                $this->success('删除成功','index/article/lists');
            }
        }   
        $navtitle = '文档列表' . $project_detail['name'];
        $this->assign('navtitle', $navtitle);
        
        $this->assign('page', $page);
        $this->assign('article_list', $article_list);
        return $this->fetch($this->templatePath);
    }

    //详情页
    public function detail() {

        $article_id = input('get.id', '0', 'intval');
        if ($article_id > 0) {
            $article_detail = DB::name('Article')
                ->alias('a')
                ->join('chinatt_pms_class c', 'a.class = c.id', 'left')
                ->join('chinatt_pms_project p ', 'a.project = p.id', 'left')
                ->join('chinatt_pms_user u ', 'a.uid = u.uid', 'left')
                ->field('a.*,c.name as class_name,p.name as project_name,u.username')
                ->where(['a.status' => 0,'a.id' => $article_id])
                ->find();
            if (empty($article_detail)) {
                $this->error('不存在该文章');
            }
        }
        $navtitle = $article_detail['title'];
        $this->assign('navtitle', $navtitle);
        $this->assign('article_id',$article_id);
        $this->assign('article_detail',$article_detail);
        return $this->fetch($this->templatePath);
    }

    /**
     * 添加编辑网址
     * @return type
     */
    public function add() {

        $article_id = input('get.id', '0', 'intval');
        $sort_list = DB('Class')->where(['status' => 1])->select();
        $project_list = Db::name('Project')->where($data)->order("id DESC")->paginate(15);
        if ($article_id > 0) {
            $article_detail = DB('Article')->where(['status' => 0, 'id' => $article_id])->find();
            if (empty($article_detail)) {
                $this->error('不存在该文章');
            }
        }
        if (request()->isPost()) {
            $data = [
                'uid' => $this->_G['uid'],
                'project' => input('post.project_id', '0', 'intval'),
                'class' => input('post.class_id', '0', 'intval'),
                'title' => input('post.title', '', 'addslashes'),
                'contents' => input('post.contents', '', 'addslashes'),
                'acl' => input('post.acl', 'open', 'addslashes'),
                'time' => date('Y-m-d H:i:s'),
                'status' => 0,
            ];
            if ($article_id > 0) {
                DB('Article')->where(['id' => $article_id])->update($data);
                $this->success('修改成功','index/article/lists');
            } else {
                DB('Article')->insert($data);
                $this->success('添加成功','index/article/lists');
            }
        }
        if(empty($article_detail)){
            $navtitle = '添加文档';
        }  else {
            $navtitle = '修改文档';
        }
        
        $this->assign('navtitle', $navtitle);
        $this->assign('article_detail', $article_detail);
        $this->assign('project_list', $project_list);
        $this->assign('sort_list', $sort_list);
        return $this->fetch($this->templatePath);
    }

}
