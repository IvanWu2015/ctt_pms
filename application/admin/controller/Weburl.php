<?php

namespace app\admin\controller;

use \traits\controller\Jump;
use \think\Db;
use Page;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Weburl extends Common {

    public function index() {
        return $this->fetch($this->templatePath);
    }

    public function lists() {
        $weburl = DB::name('weburl');
        $weburl_list = DB::name('weburl')
                ->alias('w')
                ->join('chinatt_pms_project p', 'w.project = p.id', 'left')
                ->field('w.*,p.name')
                ->where([ 'w.status' => 0])
                ->select();
        $deleted = input('param.deleted', 0, 'intval');
        $weburl_id = input('param.id', 0, 'intval');
        if ($deleted > 0) {
            $weburl_detail = $weburl->where([ 'status' => 0])->find();
            if ($this->_G['is_admin']) {
                $weburl->where(['id' => $weburl_id])->update(['status' => -1]);
                $this->success('删除成功', 'admin/weburl/lists');
            }  
        }
$navtitle = '收藏管理';
        $this->assign('navtitle', $navtitle);
        $this->assign('weburl_list', $weburl_list);


        return $this->fetch($this->templatePath);
    }

}
