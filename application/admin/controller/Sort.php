<?php

namespace app\admin\controller;

use \traits\controller\Jump;
use \think\Db;
use Page;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Sort extends Common {

    public function index() {
        return $this->fetch($this->templatePath);
    }

    public function add() {
        $class_id = input('get.id', '0', 'intval');
        $sort_list = DB('Class')->where(['status' => 1])->select();
        if ($class_id > 0) {
            $class_detail = DB('Class')->where(['status' => 1, 'id' => $class_id])->find();
            if (empty($class_detail)) {
                $this->error('不存在该分类信息');
            }
        }

        if (request()->isPost()) {
            $class_data = [
                'name' => input('param.name'),
                'parentid' => input('param.parentid'),
                'status' => 1,
                'type' => 'weburl',
            ];

            if ($class_id > 0) {
                DB('Class')->where(['id' => $class_id])->update($class_data);
                //操作记录
                write_action($this->_G['username'], 0, 'class', $class_id, 'update', input('param.name'));
                $message = array('result' => 'success', 'error' => '');
                $data = json_encode($message);
                echo $data;
                exit();
            } else {
                $class_id = DB('Class')->insertGetId($class_data);
                //操作记录
                write_action($this->_G['username'], 0, 'class', $class_id, 'add', input('param.name'));
                $message = array('result' => 'success', 'error' => '');
                $data = json_encode($message);
                echo $data;
                exit();
            }
        }

        $this->assign('class_id', $class_id);
        $this->assign('class_detail', $class_detail);
        $this->assign('sort_list', $sort_list);
        return $this->fetch($this->templatePath);
    }

    public function lists() {
        $class = db('Class');
        $class_list = DB::name('Class')
                ->alias('c')
                ->join('chinatt_pms_class p', 'c.parentid = p.id', 'left')
                ->field('c.*,p.name as parent_name,p.status')
                ->where(['c.status' => 1])
                ->paginate(10);
        $page = $class_list->render(); // 分页显示输出
//        if (request()->isPost()) {
//            $delete_ids = array();
//            foreach ($_POST['delete'] as $key => $value) {
//                $delete_ids[] = $key;
//            }
//            $class_data['id']  = array('in',$delete_ids);
//            $class->where($class_data)->update(['status' => 0]);//删除之前的记录
//            $this->success('成功删除');
//        }
        $deleted = input('get.deleted', '0', 'intval');
        $class_id = input('get.id', '0', 'intval');
        if ($deleted > 0) {
            $class = DB::name('Class')->where(['id' => $class_id, 'status' => 1])->find();
            if (empty($class)) {
                $this->error('不存在该分类');
            } else {
                DB::name('Class')->where(['id' => $class_id, 'status' => 1])->update(['status' => 0]);
                $this->success('删除成功');
            }
        }$this->assign('page', $page);
        $this->assign('class_list', $class_list);
        return $this->fetch($this->templatePath);
    }

}
