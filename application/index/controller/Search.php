<?php

namespace app\index\controller;

load_trait('controller/Jump');  // 引入traits\controller\Jump 5.5以上版本无须引用

use \traits\controller\Jump;
use \think\Db;
use Page;

class Search extends Common {

    //项目详情页
    public function detail() {

        return $this->fetch($this->templatePath);
    }

    //列表
    public function lists() {
        $task = db('Task');
        $action = db('Action');
        $project = db('Project');
        $team = db('Team');
        $username = $this->_G['username'];

        $type = input('type', '', 'addslashes');
        $keyword = input('keyword', '', 'addslashes');

        //$project_username_list = db('Project')->where()->select();

        $project_list = DB::name('Project')
                ->alias('p')
                ->join('chinatt_pms_team t', "p.acl = 'private' AND t.project = p.id AND t.username = '$username'", 'left')
                ->where(['t.username' => $username])
                ->select();
        foreach ($project_list as $key => $value) {
            $project_username_list[] = $value['project'];
            if ($key == 0) {
                $ids .= $value['id'];
            } else {
                $ids .= ',' . $value['id'];
            }
        }
        //搜索部分的处理
        if (!empty($keyword)) {
            $actiondata['a.comment'] = array('like', "%$keyword%");
            $actiondata['a.project'] = array('in', $ids);
            
            $namedata['t.deleted'] = array('EQ', '0');
            $namedata['t.name'] = array('like', "%$keyword%");
            $namedata['t.project'] = array('in',$ids);

            $descdata['t.deleted'] = array('EQ', '0');
            $descdata['t.desc'] = array('like', "%$keyword%");
            $descdata['t.project'] = array('in',$ids);
            //以动态为搜索对象
            if ($type == 'action') {
                $action_count = $action
                        ->alias('a')
                        ->join('chinatt_pms_project p',"a.project = p.id", 'left')
                         ->join('chinatt_pms_task t',"t.project = p.id", 'left')
                        ->where($actiondata)->group('t.id')
                        ->count();
                //任务动态
                $action_task_list = $action
                        ->alias('a')
                        ->join('chinatt_pms_project p',"a.project = p.id", 'left')
                         ->join('chinatt_pms_task t',"t.project = p.id", 'left')
                        ->where($actiondata)
                        ->group('t.id')
                        ->paginate(20, $action_count, ['path' => url('/index/search/lists/'), 'query' => ['keyword' => $keyword, 'type' => $type]]);
                $task_list = $action_task_list;
            } elseif ($type == 'name') {
                //用户名
                $name_count = $task
                        ->alias('t')
                        ->join('chinatt_pms_project p',"t.project = p.id", 'left')
                        ->where($namedata)->group('t.id')
                        ->count();
                $name_task_list = $task
                        ->alias('t')
                        ->join('chinatt_pms_project p',"t.project = p.id", 'left')
                        ->field('t.*,t.id as tid')
                        ->where($namedata)
                        ->group('t.id')
                        ->paginate(20, $name_count, ['path' => url('/index/search/lists/'), 'query' => ['keyword' => $keyword, 'type' => $type]]);
                
                $task_list = $name_task_list;
            } elseif ($type == 'desc') {
                //内容
                $desc_count = $task
                        ->alias('t')
                        ->join('chinatt_pms_project p',"t.project = p.id", 'left')
                        ->where($descdata)->group('t.id')
                        ->count();
                $desc_task_list = $task
                        ->alias('t')
                        ->join('chinatt_pms_project p',"t.project = p.id", 'left')
                        ->field('t.*,t.id as tid')
                        ->where($descdata)
                        ->group('t.id')
                        ->paginate(20, $desc_count, ['path' => url('/index/search/lists/'), 'query' => ['keyword' => $keyword, 'type' => $type]]);
                $task_list = $desc_task_list;
            }
            $page = $task_list->render(); // 分页显示输出
        }
         $this->assign('page', $page);
        $this->assign('task_list', $task_list);
        $navtitle = '需求列表' . $this->navtitle;
        $this->assign('navtitle', $navtitle);
        return $this->fetch($this->templatePath);
    }

}
