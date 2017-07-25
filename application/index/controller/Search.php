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

        $longtime = input('longtime', '', 'intval'); //固定时间查询条件
        $project_id = input('project_id', '', 'intval'); //项目id
        $addusername = input('addusername', '', 'addslashes'); //添加人
        //筛选时间查询
        $starttime = input('starttime', '', 'addslashes');
        $endtime = input('endtime', '', 'addslashes');


        $project_list = getUserProjectList($this->_G['username']);

        $user_list = db('User')->where(['deleted' => 0])->field('realname,username')->select();

        //$project_username_list = db('Project')->where()->select();
        $ids = getUserprojectids($username);
        //搜索部分的处理
        if (!empty($keyword)) {
            $actiondata['a.project'] = array('in', $ids);
            $actiondata['a.objectType'] = array('eq', 'task');

            if ($project_id > 0) {
                $actiondata['p.id'] = array('EQ', $project_id);
                $namedata['project'] = array('EQ', $project_id);
            }
            if (!empty($addusername)) {
                $actiondata['a.actor'] = array('EQ', $addusername);
                $namedata['openedBy'] = array('EQ', $addusername);
            }
//            if ($longtime > 0) {
//                $time = date('Y-m-d H:i:s');
//                $start_time = date('Y-m-d H:i:s', strtotime("$time - $longtime days"));
//                $actiondata['a.date'] = array('between time', "$starttime,$time");
//                $namedata['openedDate'] = array('gt', "$start_time");
//                $_SESSION['start_time'] = $start_time;
//            }
            if (!empty($starttime) || !empty($endtime)) {
                if (empty($endtime)) {
                    $endtime = date('Y-m-d');
                }
                $actiondata['a.date'] = array('between time', "$starttime,$endtime");
                $namedata['openedDate'] = array('between time', "$starttime,$endtime");
                $_SESSION['starttime'] = $starttime;
                $_SESSION['endtime'] = $endtime;
            }

            //以动态为搜索对象
            if ($type == 'action') {
                $action_count = $task
                        ->alias('t')
                        ->join('chinatt_pms_action a', "a.objectID = t.id", 'left')
                        ->where(function ($query) {
                            $query->where("comment", 'like', "%" . input('keyword', '', 'addslashes') . '%');
                        })->where($namedata)->where($actiondata)
                        ->count();
                $action_task_list = $task
                        ->alias('t')
                        ->join('chinatt_pms_action a', "a.objectID = t.id", 'left')
                        ->where(function ($query) {
                            $query->where("comment", 'like', "%" . input('keyword', '', 'addslashes') . '%');
                        })->where($namedata)->where($actiondata)
                        ->paginate(20, $name_task_count, ['path' => url('/index/search/lists/'), 'query' => ['keyword' => $keyword, 'type' => $type]]);


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
                                    if (!empty($_SESSION['starttime']) || !empty($_SESSION['endtime'])) {
                                        $starttime = $_SESSION['starttime'];
                                        $endtime = $_SESSION['endtime'];
                                        $data['time'] = array('between time', "$starttime,$endtime");
                                    }
                                    $query->where("contents|title", 'like', "%" . input('keyword', '', 'addslashes') . '%')->where(['acl' => 'private', 'username' => $this->_G['username']])->where($data);
                                })->whereOr(function ($query) {
                            if (!empty($_SESSION['starttime']) || !empty($_SESSION['endtime'])) {
                                $starttime = $_SESSION['starttime'];
                                $endtime = $_SESSION['endtime'];
                                $data['time'] = array('between time', "$starttime,$endtime");
                            }
                            $query->where("contents|title", 'like', "%" . input('keyword', '', 'addslashes') . '%')->where(['acl' => 'open'])->where($data);
                        })->count();
                $article_list = $article
                                ->where(function ($query) {
                                    if (!empty($_SESSION['starttime']) || !empty($_SESSION['endtime'])) {
                                        $starttime = $_SESSION['starttime'];
                                        $endtime = $_SESSION['endtime'];
                                        $data['time'] = array('between time', "$starttime,$endtime");
                                    }
                                    $query->where("contents|title", 'like', "%" . input('keyword', '', 'addslashes') . '%')->where(['acl' => 'private', 'username' => $this->_G['username']])->where($data);
                                })->whereOr(function ($query) {
                            if (!empty($_SESSION['starttime']) || !empty($_SESSION['endtime'])) {
                                $starttime = $_SESSION['starttime'];
                                $endtime = $_SESSION['endtime'];
                                $data['time'] = array('between time', "$starttime,$endtime");
                            }
                            $query->where("contents|title", 'like', "%" . input('keyword', '', 'addslashes') . '%')->where(['acl' => 'open'])->where($data);
                        })->paginate(20, $article_count, ['path' => url('/index/search/lists/'), 'query' => ['keyword' => $keyword, 'type' => $type]]);

                $task_list = $article_list;
            }
            $page = $task_list->render(); // 分页显示输出
        }
        $this->assign('page', $page);
        $this->assign('keyword', $keyword);
        $this->assign('user_list', $user_list);
        $this->assign('addusername', $addusername);
        $this->assign('longtime', $longtime);
        $this->assign('starttime', $starttime);
        $this->assign('endtime', $endtime);
        $this->assign('project_id', $project_id);
        $this->assign('project_list', $project_list);
        $this->assign('type', $type);
        $this->assign('count', $count);
        $this->assign('task_list', $task_list);
        $navtitle = '搜索列表' . $this->navtitle;
        $this->assign('navtitle', $navtitle);
        return $this->fetch($this->templatePath);
    }

}
