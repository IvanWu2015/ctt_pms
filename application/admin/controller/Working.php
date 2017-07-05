<?php

namespace app\admin\controller;
use \traits\controller\Jump;
use \think\Db;
use Page;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Working extends Common {
    
    public function index() {
        
        return $this->fetch($this->templatePath);
    }
    
    public function lists() {
        $project_id = input('get.project_id', '0', 'intval');
        $username = input('get.username', '', 'addslashes');
        $name = input('get.name', '', 'addslashes');
        $user_list = db('User')->where(['deleted' => 0])->select();//用户列表
        $project_list = db('Project')->where(['deleted' => 0])->select();//项目列表
        $product_list = db('Product')->where(['deleted' => 0])->select();//产品列表
        if($project_id > 0){
                    $data['p.id'] = array('eq',$project_id);
        }
        if(!empty($username)){
                    $data['e.username'] = array('eq',$username);
        }
        if(!empty($name)){
                    $data['d.code'] = array('eq',$name);
        }
        //搜索
        $keyword = input('post.keyword', '', 'addslashes');
        if(!empty($keyword)){
            $data['t.name'] = array('like',"%$keyword%");
        }
        //工时列表
        $working_list = DB('Taskestimate')
                ->alias('e')
                ->join('chinatt_pms_task t', 'e.task = t.id', 'left')
                ->join('chinatt_pms_project p','t.project = p.id','left')
                ->join('chinatt_pms_product d','p.code = d.code','left')
                ->field('e.*,t.name,t.project,p.name as project_name,d.name as product_name')
                ->where($data)
                ->order('id DESC')
                ->paginate(20, $task_count, ['path' => url('/admin/working/lists/'), 'query' => ['project_id' => $project_id ,'username' => $username,'name' => $name]]);
        $page = $working_list->render(); // 分页显示输出
        $navtitle = '工时管理';
        $this->assign('navtitle', $navtitle);
        $this->assign('keyword',$keyword);
        $this->assign('user_list',$user_list);
        $this->assign('project_list',$project_list);
        $this->assign('product_list',$product_list);
        $this->assign('username',$username);
        $this->assign('name',$name);
        $this->assign('project_id',$project_id);
        $this->assign('working_list',$working_list);
        $this->assign('page',$page);
        
        return $this->fetch($this->templatePath);
    }
    
    public function delete() {
        if (request()->isPost()) {
            save_log($_POST,$this->_G['uid'],$this->_G['username']);
            //删除时任务的消耗时间相对应的操作减去删除部分的工时
            foreach ($_POST['deleted'] as $key => $value){
                if($_POST['deleted'][$key] == 1){
                    $ids[] = $key;
                    $estimate = db('Taskestimate')->where(['id' => $key])->find();
                    DB::name('Task')->where(['id' => $estimate['task']])->setDec('consumed', $estimate['consumed']);
                    $new_data['username'] = array('eq',$estimate['username']);
                    $new_data['date'] = array('eq',$estimate['date']);
                    DB::name('Workcount')->where($new_data)->setDec('consumed', $estimate['consumed']);
                }
            }
            $data['id'] = array('in',$ids);
            db('Taskestimate')->where($data)->delete();//删除
            $this->success("删除成功",'admin/working/lists');
        }
    }
}