<?php

namespace app\index\controller;

load_trait('controller/Jump');  // 引入traits\controller\Jump 5.5以上版本无须引用

use \traits\controller\Jump;
use \think\Db;
use Page;

class Project extends Common {

    //项目详情页
    public function detail() {
        $user = DB::name('User');
        $task = DB::name('Task');
        $project = db('Project');
        $project_id = input('get.id', '0', 'intval');
        $keyword = input('keyword', '', 'addslashes');
        $orderby_array = array('pri', 'estimate', 'consumed', 'status');

        $orderby = in_array(input('param.orderby'), $orderby_array) ? input('param.orderby') : 'id';
        $map['t.deleted'] = array('EQ', '0');
        if ($project_id > 0) {
            $map['t.project'] = array('EQ', $project_id);
        } else {
            $this->error('项目ID错误');
        }

        $status = input('get.status', 'all', 'addslashes');
        $username = input('param.username');
        if ($status == 'noclosed') {
            $map['t.status'] = array('not in', 'closed,done,cancel');
        } elseif ($status == 'delayed') {
            $map['t.status'] = array('in', 'wait,doing');
            $map['t.deadline'] = array('between time', ['2000-1-1', gmdate("Y-m-d")]);
        }
        if (!empty($username)) {
            $map['t.assignedTo'] = array('eq', $username);
        }
        if (!empty($keyword)) {
            $map['t.name'] = array('LIKE', '%' . $keyword . '%');
        }
        $task_list = $task
                ->alias('t')
                ->join('chinatt_pms_task k', "t.predecessor = k.id", 'left')
                ->join('chinatt_pms_config c',"t.type = c.c_key",'left')
                ->where($map)
                ->field('t.*,k.name as p_name,c.c_value as type_name')
                ->order("$orderby DESC")
                ->paginate(20, $task_count, ['path' => url('/index/project/detail/'), 'query' => ['id' => $project_id, 'status' => $status]]);

        $show = $task_list->render(); // 分页显示输出
        $user_list = get_userlist_by_projectid($project_id);
        $project_detail = $project->where(['id' => $project_id])->find();
        $project_detail = get_project_consume($project_detail);
        //访问权限判断
        if ($project_detail['acl'] == 'private' && !isProjectUser($this->_G['username'], $user_list) && $this->_G['is_admin'] != 1) {
            $this->error('您无该项目访问权限。');
        }

        $navtitle = $project_detail['name'];
        $this->assign('keyword', $keyword);
        $this->assign('project_detail', $project_detail);
        $this->assign('user_list', $user_list);
        $this->assign('project_id', $project_id);
        $this->assign('status', $status);
        $this->assign('username', $username);
        $this->assign('page', $show);
        $this->assign('task_list', $task_list);
        $this->assign('navtitle', $navtitle);
        return $this->fetch($this->templatePath);
    }

    //列表
    public function lists() {
        $map['p.deleted'] = array('eq', '0');
        $username = $this->_G['username'];
        $status = input('status/s', 'unclose');
        $status = str_replace('.html', '', $status);
        $orderby_array = array('pri');
        $orderby = in_array(input('param.orderby'), $orderby_array) ? input('param.orderby') : 'id';
        if ($status == 'unclose') {
            $map['p.status'] = array('NOT in', 'done,closed');
        } elseif ($status == 'all') {
            
        } elseif ($status == 'delayed') {
            $map['p.status'] = array('in', 'wait,doing');
            $map['p.end'] = array('between time', ['2000-1-1', gmdate("Y-m-d")]);
        } elseif ($status == 'not') {
            $map['p.begin'] = array('GT', date('y-m-d h:i:s'));
        } elseif ($status == 'ing') {
            $map['p.status'] = array('eq', 'doing');
        } elseif ($status == 'closed') {
            $map['p.status'] = array('eq', 'closed');
        } else {
            $map['p.status'] = array('eq', $status);
        }
        if ($this->_G['is_admin'] != 1) {
            $map['p.acl'] = array('eq', 'open');
            $map_or['t.username'] = $username;
        }
        //$map['t.username'] = array('eq',$username);
        
        $project_list = DB::name('Project')
                ->alias('p')
                ->join('chinatt_pms_team t', "p.acl = 'private' AND t.project = p.id AND t.username = '$username'", 'left')
                ->join('chinatt_pms_action a', "p.id = a.project  AND a.id = (select max(id) from chinatt_pms_action where project = p.id )", 'left')
                ->join('chinatt_pms_taskestimate e', "a.extra =  e.id", 'left')
                ->field('p.*,t.username,a.objectType,a.objectID,a.actor,a.action,a.date,a.id as action_id,e.left,e.consumed')
                ->where($map)
                ->whereor($map_or)
                ->order('action_id desc')
                ->paginate(30);
//        $project_list = DB::name('Project')
//                ->alias('p')
//                ->join('chinatt_pms_team t' ," t.project = p.id AND t.username = '$username'",'left')
//                ->join('chinatt_pms_action a',"p.id = a.project AND a.objectType = 'project' AND a.id = (select max(id) from chinatt_pms_action where project = p.id AND  a.objectType = 'project')",'left')
//                ->where(['t.username' => $username,'p.deleted' => '0'])
//                ->field('p.*,t.username,a.objectType,a.objectID,a.actor,a.action,a.date')
//                ->paginate(30);
        $project_list = analysis_all($project_list);
        $page = $project_list->render(); // 分页显示输出
        //将对象转为数组
        $project_list = $project_list->toArray();
        $project_list = get_project_consume($project_list['data']);

        $navtitle = '项目列表' . $this->navtitle;
        $this->assign('status', $status);
        $this->assign('page', $page);
        $this->assign('project_list', $project_list);
        $this->assign('navtitle', $navtitle);
        return $this->fetch($this->templatePath);
    }

    //添加
    public function add() {
        $project = db('Project');
        //$project_id = intval($_GET['project_id']);
        $project_id = input('get.project_id', '0', 'intval');
        $user_list = Db::name('user')->column('uid,username,realname', 'username');
        $product_list = DB::name('Product')->where(['deleted' => 0])->select();
        $team = db('Team');
        //已经在该项目的人员
        if ($project_id > 0) {
            $project_detail = $project->where(['id' => $project_id])->find();
            if ($project_detail['project_admin'] != $this->_G['username'] && $this->_G['is_admin'] != 1) {
                $this->error("权限不足");
            }
            //该项目所有成员
            $old_team_list = $team->where(['project' => $project_id])->select();
            foreach ($old_team_list as $key => $value) {
                $team_list[$key] = $value['username'];
            }
        } else {
            $project_detail = [
                'name' => input('param.name'),
                'desc' => input('param.desc'),
                'begin' => input('param.begin'),
                'product' => input('param.product'),
                'end' => input('param.end'),
                'code' => input('param.code'),
                'project_admin' => input('param.project_admin'),
                'status' => input('param.status'),
            ];
            $team_list = [
            ];
        }

        if (request()->isPost()) {
            $pro_data = [
                'name' => input('param.name'),
                'begin' => input('param.begin'),
                'end' => input('param.end'),
                'code' => input('param.code'),
                'product' => input('param.product'),
                'desc' => input('param.desc'),
                'project_admin' => input('param.project_admin'),
                'acl' => input('acl', 'open', 'addslashes'),
                'status' => input('param.status'),
            ];

            //编辑
            if ($project_id > 0) {
                if ($_POST['ids']) {
                    if (empty($_POST['username'])) {
                        $this->error('请勾选该项目成员。');
                    }
                    foreach ($_POST['username'] as $key => $value) {
                        $a[] = $value;
                        //团队表的数据
                        $team_data = array(
                            'username' => $value,
                            'project' => $project_id,
                            'join' => date('Y-m-d H:i:s'),
                        );
                        if (!in_array($value, $team_list)) {
                            Db::table('chinatt_pms_team')->insert($team_data);
                        }
                    }
                    $delete_data['username'] = array('not in', $a);
                    $delete_data['project'] = array('eq', $project_id);
                    Db::table('chinatt_pms_team')->where($delete_data)->delete();
                }
                Db::table('chinatt_pms_project')->where(['id' => $project_id])->update($pro_data);
                //操作记录
                write_action($this->_G['username'], $project_id, 'project', $project_id, 'updata', input('param.desc'));
                //$project->where(['id' => $project_id])->save($pro_data);
                $this->success("修改成功", url('index/Project/lists'));
                //新添加
            } else {
                $add_pro_id = Db::table('chinatt_pms_project')->insertGetId($pro_data);
                //操作记录
                write_action($this->_G['username'], $add_pro_id, 'project', $add_pro_id, 'opened', input('param.desc'));
                if ($_POST['ids']) {
                    foreach ($_POST['username'] as $key => $value) {
                        //精品
                        if ($_POST['username'][$key] != 1) {
                            $a[] = $value;
                            $team_data = [
                                'username' => $value,
                                'project' => $add_pro_id,
                            ];
                        }
                        Db::table('chinatt_pms_team')->insert($team_data);
                    }
                }
                $this->success("成功添加", url('index/Project/lists'));
            }
        }

        $navtitle = '添加/修改项目' . $this->navtitle;
        $this->assign('product_list', $product_list);
        $this->assign('project_id', $project_id);
        $this->assign('project_details', $project_detail);
        $this->assign('team_list', $team_list);
        $this->assign('user_list', $user_list);
        $this->assign('navtitle', $navtitle);
        return $this->fetch($this->templatePath);
    }

    //概况
    public function info() {
        $project = db('Project');
        $project_id = input('get.project_id', '0', 'intval');
        //任务详情
        $project_detail = $project->where(['id' => $project_id, 'deleted' => 0])->find();
        
        //管理员名称
        $project_name = $project_detail['project_admin'];
        //通过英文名称找中文名
        $project_details = Db::table('chinatt_pms_user')->where('username', $project_name)->find();
        //将中文名赋值给admin_username
        $project_detail['admin_username'] = $project_details['realname'];
        
        $project_user_list = get_userlist_by_projectid($project_id);
        foreach ($project_user_list as $key => $value){
            $users[] = $key;
        }
        if ($this->_G['username'] != $project_detail['project_admin'] && $this->_G['is_admin'] != 1 && !in_array($this->_G['username'], $users)) {
            $this->error("权限不足");
        }
        
        //详情页面的快速操作
        $ac = input('ac', '', 'addslashes');
        if ($ac == 'doing' || $ac == 'closed' || $ac == 'done') {
            DB('Project')->where(['id' => $project_id])->update(['status' => $ac]);
            //操作记录
            write_action($this->_G['username'], $project_id, 'project', $project_id,$ac, input('comment', '', 'addslashes'));
            $message = array('result' => 'success');
            $data = json_encode($message);
            echo $data;
            exit();
        }

        $project_detail = get_project_consume($project_detail);
        if (empty($project_detail)) {
            $this->error("不存在该任务");
        }
        $navtitle = '概况' . ' - ' . $project_detail['name'];
        $this->assign('project_id', $project_id);
        $this->assign('project_detail', $project_detail);
        $this->assign('navtitle', $navtitle);
        return $this->fetch($this->templatePath);
    }

    //团队
    public function group() {
        $project_id = input('param.project_id');
        $project_detail = DB::table('chinatt_pms_project')->where(['id' => $project_id])->find();
        $team_list = Db::table('chinatt_pms_team team, chinatt_pms_user user,chinatt_pms_project project')->where("team.username = user.username AND team.project = $project_id AND project.id = $project_id")->field('team.*,user.realname,project.name as project_name')->select();

        //$team_list = DB::table('chinatt_pms_team')->where(['project' => $project_id])->select();
        foreach ($team_list as $key => $value) {
            $pro_id = $value['project'];
            $username = $value['username'];
            $team_list[$key]['count_estimate'] = DB::table('chinatt_pms_task')->where(['assignedTo' => $username, 'project' => $pro_id, 'deleted' => 0])->sum('estimate');
            $team_list[$key]['count_consumed'] = DB::table('chinatt_pms_task')->where(['assignedTo' => $username, 'project' => $pro_id, 'deleted' => 0])->sum('consumed');
        }
        $navtitle = '团队' . ' - ' . $project_detail['name'];
        $this->assign('team_list', $team_list);
        $this->assign('project_id', $project_id);
        $this->assign('project_detail', $project_detail);
        $this->assign('navtitle', $navtitle);
        return $this->fetch($this->templatePath);
    }

    //动态
    public function action() {
        $project_id = input('param.project_id');
        $project_detail = DB::table('chinatt_pms_project')->where(['id' => $project_id])->find();

        $project_list = Db::table('chinatt_pms_action action, chinatt_pms_task task, chinatt_pms_project project')
                ->where("action.objectID = task.id AND action.project = $project_id AND action.project = project.id")
                ->field('action.*,task.name as task_name,project.name as project_name')
                ->order('action.date ')
                ->paginate(10, '', ['path' => url('/index/project/action/'), 'query' => ['project_id' => $project_id]]);
        $page = $project_list->render(); // 分页显示输出
        $project_list = analysis_all($project_list);
        $navtitle = '动态' . ' - ' . $project_detail['name'];
        $this->assign('project_list', $project_list);
        $this->assign('project_detail', $project_detail);
        $this->assign('page', $page);
        $this->assign('project_id', $project_id);
        $this->assign('navtitle', $navtitle);
        return $this->fetch($this->templatePath);
    }

    //文档
    public function article() {
        $project_id = input('param.project_id', 0, 'intval');
        $article_list = DB::name('Article')
                ->alias('a')
                ->join('chinatt_pms_class c', 'a.class = c.id', 'left')
                ->join('chinatt_pms_project p ', 'a.project = p.id', 'left')
                ->field('a.*,c.name as class_name,p.name as project_name')
                ->where(['a.status' => 0, 'a.project' => $project_id])
                ->paginate(10);
        $page = $article_list->render(); // 分页显示输出
        $project_detail = DB::name('Project')->where(['id' => $project_id])->find();
        $project_detail = get_project_consume($project_detail);
        $navtitle = $project_detail['name'];
        $this->assign('project_detail', $project_detail);
        $this->assign('navtitle', $navtitle);
        $this->assign('project_id', $project_id);
        $this->assign('article_list', $article_list);
        return $this->fetch($this->templatePath);
    }

    //收藏
    public function weburl() {
        $project_id = input('param.project_id', 0, 'intval');
        $weburl = DB::name('weburl');
        $weburl_list = DB::name('weburl')
                ->alias('w')
                ->join('chinatt_pms_project p', 'w.project = p.id', 'left')
                ->field('w.*,p.name')
                ->where(['project' => $project_id, 'w.status' => 0])
                ->paginate(10);
        $page = $weburl_list->render(); // 分页显示输出
        $project_detail = DB::name('Project')->where(['id' => $project_id])->find();
        $project_detail = get_project_consume($project_detail);
        $navtitle = $project_detail['name'];
        $this->assign('navtitle', $navtitle);
        $this->assign('project_detail', $project_detail);
        $this->assign('project_id', $project_id);
        $this->assign('weburl_list', $weburl_list);
        return $this->fetch($this->templatePath);
    }
    
    
    public function survey() {
        $project_id = input('project_id', '', 'addslashes');
        $ac = input('ac', '', 'addslashes');
         $this->assign('project_id',$project_id);
         $this->assign('ac',$ac);
         return $this->fetch($this->templatePath);
    }

}
