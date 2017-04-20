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
        $data['w.status'] = array('eq', 0);

        if (!empty($username)) {
            $data['u.username'] = array('eq', $username);
        }
        if ($product_id > 0) {
            $data['d.id'] = array('eq', $product_id);
        }
        if ($class_id > 0) {
            $data['c.id'] = array('eq', $class_id);
        }
        $weburl_list = DB::name('weburl')
                ->alias('w')
                ->join('chinatt_pms_project p', 'w.project = p.id', 'left')
                ->join('chinatt_pms_user u', 'u.uid = w.uid', 'left')
                ->join('chinatt_pms_class c', 'c.id = w.class', 'left')
                ->join('chinatt_pms_product d', 'd.id = w.product', 'left')
                ->field('w.*,p.name,u.username,u.realname,d.name as product_name,c.name as class_name')
                ->where($data)
                ->paginate(10);
        $page = $weburl_list->render(); // 分页显示输出
        $deleted = input('param.deleted', 0, 'intval');
        $weburl_id = input('param.id', 0, 'intval');
        if ($deleted > 0) {
            $weburl_detail = $weburl->where(['uid' => $this->_G['uid'], 'status' => 0])->find();
            if ($this->_G['uid'] == $weburl_detail['uid']) {
                $weburl->where(['id' => $weburl_id])->update(['status' => -1]);
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
                'uid' => $this->_G['uid'],
                'project' => input('param.project_id', 0, 'intval'),
                'product' => input('param.product_id', 0, 'intval'),
                'class' => input('param.class_id', 0, 'intval'),
                'title' => input('param.title', '', 'addslashes'),
                'url' => input('param.url', '', 'addslashes'),
                'explain' => input('param.explain', '', 'addslashes'),
                'acl' => input('param.acl', 'open', 'addslashes'),
                'time' => date('Y-m-d H:i:s'),
            );
            if ($weburl_id > 0) {
                $weburl->where(['id' => $weburl_id])->update($data);
                $this->success('修改成功', url('index/weburl/lists'));
            } else {
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
