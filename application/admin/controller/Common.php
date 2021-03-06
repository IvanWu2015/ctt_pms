<?php

namespace app\admin\controller;

use \traits\controller\Jump;
use \think\Db;
use think\Request;
use app\index\model;

load_trait('controller/Jump');  // 引入traits\controller\Jump

class Common extends \think\Controller {

    public $_G = []; //前台通用变量
    public $templatePath = '';  //  模板变量 用于控制模板输出

    //初始化操作 用户登陆状态判断

    public function _initialize() {
        $this->_G['time'] = time();
        $this->_G['uid'] = is_login();
        $request = Request::instance();
        //用户相关信息初始化
        if ($this->_G['uid'] > 0) {
            $User = model('User');
            $userInfo = $User->info($this->_G['uid']);
            $this->_G['user'] = $userInfo;
            $this->_G['username'] = $userInfo['username'];
            $this->_G['is_admin'] = $userInfo['isadmin'] == 1 ? 1 : 0;  //管理员判断
        } else {
            $this->_G['user'] = '';
            $this->_G['username'] = '';
            $this->_G['is_admin'] = 0;  //管理员判断
            //未登陆跳转
            if ($request->controller() != 'User' && $request->action() != 'Login') {
                $this->redirect(url('/index/user/login'));
            }
        }
        //当前所在模块位置信息
        $this->_G['module'] = $request->module();
        $this->_G['controller'] = $request->controller();
        $this->_G['action'] = $request->action();

        //任务类型列表
//        $this->_G['type_list'] = [
//            'design' => '设计',
//            'devel' => '开发',
//            'test' => '测试',
//            'study' => '研究',
//            'discuss' => '讨论',
//            'ui' => '界面',
//            'affair' => '事务',
//            'misc' => '其他',
//        ];

        //用户列表
        //$this->_G['userlist'] = DB::name('user')->column('uid,username,realname', 'username');
        //print_r($this->_G);exit;
        $this->assign('_G', $this->_G);

        //模板控制
        config('template', [
            // 模板引擎类型 支持 php think 支持扩展
            'type' => 'Think',
            // 模板路径
            'view_path' => '../application/template/default/admin/',
            // 模板后缀
            'view_suffix' => 'html',
            // 模板文件名分隔符
            'view_depr' => DS,
            // 模板引擎普通标签开始标记
            'tpl_begin' => '{',
            // 模板引擎普通标签结束标记
            'tpl_end' => '}',
            // 标签库标签开始标记
            'taglib_begin' => '{',
            // 标签库标签结束标记
            'taglib_end' => '}',
            // 是否开启模板编译缓存,设为false则每次都会重新编译
            'tpl_cache' => false,
                ]
        );
        if ($this->_G['module'] = $request->module() == 'admin') {
            if ($this->_G['is_admin'] != 1) {
                $this->error('您不是管理员，无权访问后台。');
            }
        }
        if (request()->isMobile()) {
            $this->templatePath = 'mobile/' . strtolower($this->_G['controller']) . '/' . strtolower($this->_G['action']);
        } else {
            $this->templatePath = strtolower($this->_G['controller']) . '/' . strtolower($this->_G['action']);
        }
    }

    //析构函数 最后执行
    public function __destruct() {
        
    }

}
