<?php

namespace app\admin\controller;

use \traits\controller\Jump;
use \think\Db;
use Page;
use tree;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Navigation extends Common {

    public function index() {
        return $this->fetch($this->templatePath);
    }

    public function add() {

        $navigation_id = input('get.id', '0', 'intval');
        $parentid = input('get.parentid', '0', 'intval');
        $type = input('get.type', '', 'addslashes');
        $navigation_list = DB('Navigation')->where(['status' => 1])->select();
        $tree = new Tree($navigation_list);
        $navigation_list = $tree->getArray();
        if ($navigation_id > 0) {
            $navigation_detail = db('Navigation')->where(['id' => $navigation_id])->find();
        }

        if ($parentid > 0) {
            $paren_detail = DB('Navigation')->where(['status' => 1, 'id' => $parentid])->find();
        }
        if (request()->isPost()) {
            $data = [
                'parentid' => input('post.parentid', '', 'intval'),
                'title' => input('post.title', '', 'addslashes'),
                'create_time' => time(),
                'status' => 1,
                'sort' => input('post.sort', '', 'intval'),
                'url' => input('param.url'),
            ];

            if ($navigation_id > 0) {
                $data['update_time'] = time();

                DB('Navigation')->where(['id' => $navigation_id])->update($data);
                //操作记录
                write_action($this->_G['username'], 0, 'navigation', $navigation_id, 'update', input('param.title'));
                $message = array('result' => 'success', 'error' => '');
                $data = json_encode($message);
                echo $data;
                exit();
            } else {
                $navigation_id = DB('Navigation')->insert($data);
                //操作记录
                write_action($this->_G['username'], 0, 'navigation', $navigation_id, 'add', input('param.title'));
                $message = array('result' => 'success', 'error' => '');
                $data = json_encode($message);
                echo $data;
                exit();
            }
        }
        $this->assign('parentid', $parentid);
        $this->assign('paren_detail', $paren_detail);
        $this->assign('navigation_detail', $navigation_detail);
        $this->assign('navigation_list', $navigation_list);
        return $this->fetch($this->templatePath);
    }

    public function lists() {
        $navtitle = '导航管理';
        $navigation_id = input('get.id', '0', 'intval');
        $navigation_list = DB('Navigation')->where(['status' => 1])->order('sort', 'asc')->select();
        $tree = new Tree($navigation_list);
        $navigation_list = $tree->getArray();
        $deleted = input('get.deleted', '0', 'intval');
        if(request()->isPost()) {
            $sort_arr = $_POST['sort'];
            $name_arr = $_POST['title'];
            $url_arr = $_POST['url'];
            foreach($sort_arr as $key => $sort) {
                $key = intval($key);
                DB::name('Navigation')->where(['id' => $key, 'status' => 1])->update(['sort' => addslashes($sort_arr[$key]), 'title' => addslashes($name_arr[$key]), 'url' => addslashes($url_arr[$key])]);
            }
            $this->success('修改成功');
        }
        //单条删除处理
        if ($deleted > 0) {
            $navigation = DB::name('Navigation')->where(['id' => $navigation_id, 'status' => 1])->find();
            if (empty($navigation)) {
                $this->error('不存在该导航信息');
            } else {
                save_log($this->_G['uid'], $this->_G['username']);
                DB::name('Navigation')->where(['id' => $navigation_id, 'status' => 1])->update(['status' => 0]);
                $this->success('删除成功');
            }
        }
        $this->assign('navtitle', $navtitle);
        $this->assign('navigation_list', $navigation_list);
        return $this->fetch($this->templatePath);
    }

}
