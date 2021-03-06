<?php

/**
 * 网址收藏
 */

namespace app\index\controller;

load_trait('controller/Jump');  // 引入traits\controller\Jump

use \traits\controller\Jump;
use \think\Db;

class weburl extends Common {

    //首页
    public function index() {
        
    }

    //列表页
    public function lists() {
        $weburl = DB::name('weburl');
        $username = input('get.username', '', 'addslashes');
        $product_id = input('get.product_id', '0', 'intval');
        $class_id = input('get.class_id', '0', 'intval');
        $acl = input('param.acl', 'all', 'addslashes');
        if ($acl == 'all') {
            
        } else {
            $data['w.acl'] = array('eq', $acl);
        }

        $weburl = db('Weburl');
        $weburl_list = $weburl
                        ->where(function ($query) {
                            //搜索内容与筛选
                            if (input('get.class_id', '0', 'intval') > 0) {
                                $data['class'] = array('eq', input('get.class_id', '0', 'intval')); //分类ID
                            }
                            if (input('get.product_id', '0', 'intval') > 0) {
                                $data['product'] = array('eq', input('get.product_id', '0', 'intval')); //分类ID
                            }
                            $data['acl'] = array('eq', 'private');
                            if (input('get.acl', 'all', 'addslashes') == 'all') {
                            } else {
                                $data['acl'] = array('eq', input('get.acl', 'all', 'addslashes'));
                            }
                            $data['username'] = array('eq', $this->_G['username']);
                            $data['status'] = array('eq', 0);
                            $query->where($data)->where(['acl' => 'private']);
                        })->whereOr(function ($query) {
                    if (input('get.class_id', '0', 'intval') > 0) {
                        $data['class'] = array('eq', input('get.class_id', '0', 'intval')); //分类ID
                    }
                    if (input('get.product_id', '0', 'intval') > 0) {
                        $data['product'] = array('eq', input('get.product_id', '0', 'intval')); //分类ID
                    }
                    if (!empty(input('get.username', '', 'addslashes'))) {
                        $data['username'] = array('eq', input('get.username', '', 'addslashes'));
                    }
                    if (input('get.acl', 'all', 'addslashes') == 'all') {
                        
                    } else {
                        $data['acl'] = array('eq', input('get.acl', 'all', 'addslashes'));
                    }
                    $data['status'] = array('eq', 0);
                    $query->where($data)->where(['acl' => 'open']);
                })->paginate(20, $article_count, ['path' => url('/index/article/lists/'), 'query' => ['keyword' => $keyword, 'type' => $type]]);

        $page = $weburl_list->render(); // 分页显示输出
        $deleted = input('param.deleted', 0, 'intval');
        $weburl_id = input('param.id', 0, 'intval');
        if ($deleted > 0) {
            $weburl_detail = $weburl->where(['username' => $this->_G['username'], 'status' => 0])->find();
            if ($this->_G['username'] == $weburl_detail['username'] || $this->_G['is_admin'] == 1) {
                $weburl->where(['id' => $weburl_id])->update(['status' => -1]);
                save_log($this->_G['uid'], $this->_G['username']);
                $this->success('删除成功', 'index/weburl/lists');
            } else {
                $this->error('权限不足');
            }
        }
        //分类列表产品列表用户列表
        $sort_list = DB('Class')->where(['status' => 1])->select();
        $product_list = DB('Product')->where(['deleted' => '0'])->field('name,code,id')->select();
        $user_list = DB('User')->where(['deleted' => '0'])->select();


        $this->assign('sort_list', $sort_list);
        $this->assign('product_list', $product_list);
        $this->assign('user_list', $user_list);
        $this->assign('product_id', $product_id);
        $this->assign('class_id', $class_id);
        $this->assign('username', $username);
        $navtitle = '收藏列表' . $project_detail['name'];
        $this->assign('navtitle', $navtitle);
        $this->assign('weburl_list', $weburl_list);
        $this->assign('page', $page);
        return $this->fetch($this->templatePath);
    }

    //详情页
    public function details() {
        return $this->fetch($this->templatePath);
    }

    /**
     * 添加编辑网址
     * @return type
     */
    public function add() {
        $weburl = db('weburl');

        $weburl_id = input('param.id', 0, 'intval');
        $project_id = input('param.project_id', 0, 'intval');
        $weburl_detail = $weburl->where(['id' => $weburl_id, 'status' => 0])->find();

        $project_list = Db::name('Project')->where($map)->column('id,name,code', 'id');
        $product_list = DB('Product')->where(['deleted' => '0'])->field('name,code,id')->select();
        $sort_list = DB('Class')->where(['status' => 1])->select();

        if ($weburl_id > 0) {
            if (empty($weburl_detail)) {
                $this->error('不存在该收藏', url('index/weburl/lists'));
            }
        }
        if (request()->isPost()) {
            $data = array(
                'project' => input('param.project_id', 0, 'intval'),
                'product' => input('param.product_id', 0, 'intval'),
                'class' => input('param.class_id', 0, 'intval'),
                'title' => input('param.title', '', 'addslashes'),
                'url' => input('param.url', '', 'addslashes'),
                'explain' => input('param.explain', '', 'addslashes'),
                'acl' => input('param.acl', 'open', 'addslashes'),
                'time' => date('Y-m-d H:i:s'),
            );
            save_log($this->_G['uid'], $this->_G['username']);
            if ($weburl_id > 0) {
                $weburl->where(['id' => $weburl_id])->update($data);
                $this->success('修改成功', url('index/weburl/lists'));
            } else {
                $data['username'] = array('eq', $this->_G['username']);
                $weburl->insert($data);
                $this->success('添加成功', url('index/weburl/lists'));
            }
        }
        if (empty($weburl_detail)) {
            $navtitle = '添加收藏';
        } else {
            $navtitle = '修改收藏';
        }
        $this->assign('project_id', $project_id);
        $this->assign('navtitle', $navtitle);
        $this->assign('sort_list', $sort_list);
        $this->assign('product_list', $product_list);
        $this->assign('weburl_detail', $weburl_detail);
        $this->assign('project_list', $project_list);
        return $this->fetch($this->templatePath);
    }

}
