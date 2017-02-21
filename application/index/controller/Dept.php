<?php

namespace app\index\controller;

load_trait('controller/Jump');  // 引入traits\controller\Jump

use \traits\controller\Jump;
use \think\Db;

class Dept extends Common {

    public function lists() {
        $dept = db('Dept');
        // $pro_id = intval($_GET['pro_id']);
        $pro_id = input('get.pro_id', '0', 'intval');
        $dept_list = $dept->select();
        $team = db('Team');
        $team_user = $team->where(['project' => $pro_id])->select();


        $this->assign('dept_list', $dept_list);
        return $this->fetch($this->templatePath);
    }

    public function add() {

        $dept = db('Dept');
        $user = db('User');
        $user_list = $user->select();
        //$dept_id = intval($_GET['dept_id']);
        $dept_id = input('get.dept_id', '0', 'intval');
        if (IS_POST) {
            $dept_data = array(
                'name' => input('post.name', '', 'strip_tags'),
                'manager' => input('post.manager', '', 'strip_tags'),
                'function' => input('post.function', '', 'strip_tags'),
            );

            if ($dept_id > 0) {
                $dept->where(['id' => $dept_id])->save($dept_data);
            } else {
                $dept->data($dept_data)->insert();
            }
        }

        $this->assign('user_list', $user_list);
        return $this->fetch($this->templatePath);
    }

}
