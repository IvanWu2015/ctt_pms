<?php

/**
 * 文档
 */

namespace app\index\controller;

load_trait('controller/Jump');  // 引入traits\controller\Jump 5.5以上版本无须引用

use \traits\controller\Jump;
use \think\Db;
use Page;

class article extends Common {

    //首页
    public function index() {
        
    }

    //列表页
    public function lists() {
        $acl = input('param.acl', 'all', 'addslashes');
        $username = input('get.username', '', 'addslashes');
        $class_id = input('get.class_id', '0', 'intval');
        $data['username'] = array('eq', 1);
        //列表的筛选条件
        if ($acl == 'all') {
            
        } elseif ($acl == 'open' || $acl = 'private') {
            
        } else {
            $this->error('不存在类型');
        }
        $article = db('Article');
        $article_count = $article
                        ->where(function ($query) {
                            if (input('get.class_id', '0', 'intval') > 0) {
                                $data['id'] = array('eq', input('get.class_id', '0', 'intval')); //分类ID
                            }
                            $data['acl'] = array('eq', 'private');
                            $data['username'] = array('eq', $this->_G['username']);
                            $data['status'] = array('eq', 0);
                            $query->where($data);
                        })->whereOr(function ($query) {
                    if (input('get.class_id', '0', 'intval') > 0) {
                        $data['id'] = array('eq', input('get.class_id', '0', 'intval')); //分类ID
                    }
                    if (!empty(input('get.username', '', 'addslashes'))) {
                        $data['username'] = array('eq', input('get.username', '', 'addslashes'));
                    }
                    $data['status'] = array('eq', 0);
                    $data['acl'] = array('eq', 'open');
                    $query->where($data);
                })->count();
        $article_list = $article
                        ->where(function ($query) {
                            //搜索内容与筛选
                            if (input('get.class_id', '0', 'intval') > 0) {
                                $data['id'] = array('eq', input('get.class_id', '0', 'intval')); //分类ID
                            }
                            $data['acl'] = array('eq', 'private');
                            $data['username'] = array('eq', $this->_G['username']);
                            $data['status'] = array('eq', 0);
                            $query->where($data);
                        })->whereOr(function ($query) {
                    if (input('get.class_id', '0', 'intval') > 0) {
                        $data['id'] = array('eq', input('get.class_id', '0', 'intval')); //分类ID
                    }
                    if (!empty(input('get.username', '', 'addslashes'))) {
                        $data['username'] = array('eq', input('get.username', '', 'addslashes'));
                    }
                    $data['status'] = array('eq', 0);
                    $data['acl'] = array('eq', 'open');
                    $query->where($data);
                })->paginate(20, $article_count, ['path' => url('/index/article/lists/'), 'query' => ['keyword' => $keyword, 'type' => $type]]);


        $page = $article_list->render(); // 分页显示输出
        $deleted = input('param.deleted', 0, 'intval');
        $article_id = input('param.id', 0, 'intval');
        $sort_list = DB('Class')->where(['status' => 0])->select(); //分类列表
        $product_list = DB('Product')->where(['deleted' => '0'])->field('name,code')->select(); //产品列表
        $user_list = DB('User')->where(['deleted' => '0'])->select(); //用户列表
        //删除功能
        if ($deleted > 0) {
            $article_detail = DB::name('Article')->where(['uid' => $this->_G['uid'], 'status' => 0])->find();
            if ($this->_G['uid'] == $article_detail['uid']) {
                DB::name('Article')->where(['id' => $article_id])->update(['status' => -1]);
                $this->success('删除成功', 'index/article/lists');
            }
        }

        $navtitle = '文档列表' . $project_detail['name'];
        $this->assign('navtitle', $navtitle);
        $this->assign('page', $page);
        $this->assign('sort_list', $sort_list);
        $this->assign('class_id', $class_id);
        $this->assign('username', $username);
        $this->assign('product_list', $product_list);
        $this->assign('user_list', $user_list);
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
                    ->field('a.*,c.name as class_name,p.name as project_name')
                    ->where(['a.status' => 0, 'a.id' => $article_id])
                    ->find();
            $article_detail['contents'] = stripslashes($article_detail['contents']);
            if (empty($article_detail)) {
                $this->error('不存在该文章');
            }
        }

        $class = db('Class');
        $class_list = $class->where()->column('id,name', 'id');

        $article_list = DB::name('Article')->column('class,title,contents', 'id');
        foreach ($article_list as $value) {
            $class = $value['class'];
            $new_class_list[$class][] = $value;
            $new_class_list['title'][$class] = $class_list[$class];
        }
        $class_name_list = $new_class_list['title'];
        $navtitle = $article_detail['title'];
        $this->assign('navtitle', $navtitle);
        $this->assign('class_name_list', $class_name_list);
        $this->assign('new_class_list', $new_class_list);
        $this->assign('article_id', $article_id);
        $this->assign('article_detail', $article_detail);
        return $this->fetch($this->templatePath);
    }

    /**
     * 添加编辑网址
     * @return type
     */
    public function add() {
        $article_id = input('get.id', '0', 'intval');
        $sort_list = DB('Class')->where(['status' => 1])->select(); //分类列表
        $project_list = Db::name('Project')->where($data)->order("id DESC")->paginate(15); //项目列表
        if ($article_id > 0) {
            $article_detail = DB('Article')->where(['status' => 0, 'id' => $article_id])->find();
            if (empty($article_detail)) {
                $this->error('不存在该文章');
            }
        }
        if (request()->isPost()) {
            $data = [
                'username' => $this->_G['username'],
                'project' => input('post.project_id', '0', 'intval'),
                'class' => input('post.class_id', '0', 'intval'),
                'title' => input('post.title', '', 'addslashes'),
                'contents' => input('post.contents', '', 'addslashes'),
                'acl' => input('post.acl', 'open', 'addslashes'),
                'time' => date('Y-m-d H:i:s'),
                'status' => 0,
            ];
            //修改
            if ($article_id > 0) {
                DB('Article')->where(['id' => $article_id])->update($data);
                $this->success('修改成功', 'index/article/lists');
                //添加
            } else {
                DB('Article')->insert($data);
                $this->success('添加成功', 'index/article/lists');
            }
        }
        if (empty($article_detail)) {
            $navtitle = '添加文档';
        } else {
            $navtitle = '修改文档';
        }
        $this->assign('navtitle', $navtitle);
        $this->assign('article_detail', $article_detail);
        $this->assign('project_list', $project_list);
        $this->assign('sort_list', $sort_list);
        return $this->fetch($this->templatePath);
    }

}
