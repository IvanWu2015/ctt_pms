<?php

namespace app\admin\controller;

use \traits\controller\Jump;
use \think\Db;
use Page;
use tree;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Setting extends Common {

    public function index() {
        return $this->fetch($this->templatePath);
    }

    public function add() {
        if (request()->isPost()) {
            $data = array(
                'group' => input('post.type', '', 'addslashes'),
                'c_key' => input('post.key', '', 'addslashes'),
                'c_value' => input('post.value', '', 'addslashes'),
                'status' => 1,
                'lasttime' => date('Y-m-d H:i:s'),
            );
            DB('Config')->insert($data);
            
            $message = array('result' => 'success');
            $data = json_encode($message);
            echo $data;
            exit();
        }
        
        $navtitle = '分类列表' . $class_detail['name'];
        $this->assign('type', $type);
        $this->assign('navtitle', $navtitle);
        $this->assign('parentid', $parentid);
        $this->assign('class_id', $class_id);
        $this->assign('class_detail', $class_detail);
        $this->assign('paren_detail', $paren_detail);
        $this->assign('sort_list', $sort_list);
        return $this->fetch($this->templatePath);
    }

    public function lists() {
        $type = input('get.type', '', 'addslashes');
        $setting_list = DB('Config')->where(['status' => '1'])->paginate(20);
         $page = $setting_list->render(); // 分页显示输出
        
        if (request()->isPost()) {
            foreach ($_POST['key'] as $key => $value) {
                $value = addslashes($value);
                DB('Config')->where(['id' => $key])->update(['c_key' => $value, 'c_value' => addslashes($_POST['value'][$key]),'group' => $type]);
            }
        }
        $config_id = input('get.id', '0', 'intval');
        $deleted = input('get.deleted', '0', 'intval');
        if ($deleted == 1) {
            db('Config')->where(['id' => $config_id])->update(array('status' => '0')); //删除
            $this->success("删除成功");
        }

        $navtitle = '分类列表';
        $this->assign('type', $type);
        $this->assign('page',$page);
        $this->assign('navtitle', $navtitle);
        $this->assign('setting_list', $setting_list);
        return $this->fetch($this->templatePath);
    }

}
