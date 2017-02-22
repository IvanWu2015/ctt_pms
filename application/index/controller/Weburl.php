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
        $weburl_list = DB::name('weburl')
                ->alias('w')
                ->join('chinatt_pms_project p', 'w.project = p.id', 'left')
                ->join('chinatt_pms_user u', 'u.uid = w.uid', 'left')
                ->field('w.*,p.name,u.username,u.realname')
                ->where(['w.acl' => 'open','w.status' => 0])
                ->paginate(10);

        $page = $weburl_list->render(); // 分页显示输出
        $deleted = input('param.deleted', 0, 'intval');
        $weburl_id = input('param.id', 0, 'intval');
        if($deleted > 0){
            $weburl_detail = $weburl->where(['uid' => $this->_G['uid'],'status' => 0])->find();
            if($this->_G['uid'] == $weburl_detail['uid']){
                $weburl->where(['id' => $weburl_id])->update(['status' => -1]);
                $this->success('删除成功','index/weburl/lists');
            }else {
                $this->error('权限不足');
            }
        }
        $navtitle = '网址列表' . $project_detail['name'];
        $this->assign('navtitle', $navtitle);
        $this->assign('weburl_list', $weburl_list);
        $this->assign('page',$page);
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
        $project_list = Db::name('Project')->where($map)->column('id,name,code', 'id');
        $weburl_id = input('param.id', 0, 'intval');
        $weburl_detail = $weburl->where(['id' => $weburl_id, 'status' => 0])->find();
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
                'time' => time(),
            );
            if ($weburl_id > 0) {
                $weburl->where(['id' => $weburl_id])->update($data);
                $this->success('修改成功', url('index/weburl/lists'));
            } else {
                $weburl->insert($data);
                $this->success('添加成功', url('index/weburl/lists'));
            }
        }
        $this->assign('weburl_detail', $weburl_detail);
        $this->assign('project_list', $project_list);
        return $this->fetch($this->templatePath);
    }

}
