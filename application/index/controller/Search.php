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

        $type = input('type', 'task', 'addslashes');
        $keyword = input('keyword', '', 'addslashes');

        //$project_username_list = db('Project')->where()->select();
        $ids = getUserprojectids($username);
        //搜索部分的处理
        if (!empty($keyword)) {
            $actiondata['a.comment'] = array('like', "%$keyword%");
            $actiondata['a.project'] = array('in', $ids);
            $actiondata['a.objectType'] = array('eq', 'task');

            //以动态为搜索对象
            if ($type == 'action') {
                $action_count = $task
                        ->alias('t')
                        ->join('chinatt_pms_project p', "t.project = p.id", 'left')
                        ->join('chinatt_pms_action a', " a.id = p.id", 'left')
                        ->where($actiondata)
                        ->count();
                //任务动态
                $action_task_list = $task
                        ->alias('t')
                        ->join('chinatt_pms_project p', "t.project = p.id", 'left')
                        ->join('chinatt_pms_action a', " a.objectID = t.id", 'left')
                        ->where($actiondata)
                        ->group('t.id')
                        ->field('t.id,t.desc,t.name,t.openedBy,t.status,t.assignedTo,t.finishedBy,t.openedDate,t.assignedDate')
                        ->paginate(20, $action_count, ['path' => url('/index/search/lists/'), 'query' => ['keyword' => $keyword, 'type' => $type]]);
                $count = $action_count;

                $task_list = $action_task_list;
            } elseif ($type == 'task' || empty($type)) {
                
                $name_task_count = $task
                        ->where(function ($query) {
                            $query->where("name|desc", 'like', "%" . input('keyword', '', 'addslashes') . '%');
                        })->where($namedata)
                        ->count();
                
                
                
                $name_task_list = $task
                        ->where(function ($query) {
                            $query->where("name|desc", 'like', "%" . input('keyword', '', 'addslashes') . '%');
                        })->where($namedata)
                        ->paginate(20, $name_task_count, ['path' => url('/index/search/lists/'), 'query' => ['keyword' => $keyword, 'type' => $type]]);
                        $count = $name_task_count;
                        $task_list = $name_task_list;
            } elseif ($type == 'article') {

                $articledata['acl'] = array('eq', 'private');
                $articledata['username'] = array('eq', $this->_G['username']);

                $article = db('Article');
                $article_count = $article
                                ->where(function ($query) {
                                    $query->where("contents|title", 'like', "%" . input('keyword', '', 'addslashes') . '%')->where(['acl' => 'private', 'username' => $this->_G['username']]);
                                })->whereOr(function ($query) {
                            $query->where("contents|title", 'like', "%" . input('keyword', '', 'addslashes') . '%')->where(['acl' => 'open']);
                        })->count();
                $article_list = $article
                                ->where(function ($query) {
                                    $query->where("contents|title", 'like', "%" . input('keyword', '', 'addslashes') . '%')->where(['acl' => 'private', 'username' => $this->_G['username']]);
                                })->whereOr(function ($query) {
                            $query->where("contents|title", 'like', "%" . input('keyword', '', 'addslashes') . '%')->where(['acl' => 'open']);
                        })->paginate(20, $article_count, ['path' => url('/index/search/lists/'), 'query' => ['keyword' => $keyword, 'type' => $type]]);

                $task_list = $article_list;
            }
            $page = $task_list->render(); // 分页显示输出
        }
        $this->assign('page', $page);
        $this->assign('keyword', $keyword);
        $this->assign('type', $type);
        $this->assign('count', $count);
        $this->assign('task_list', $task_list);
        $navtitle = '搜索列表' . $this->navtitle;
        $this->assign('navtitle', $navtitle);
        return $this->fetch($this->templatePath);
    }

}
