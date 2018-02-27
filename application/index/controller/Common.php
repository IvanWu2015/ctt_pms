<?php

namespace app\index\controller;

use \traits\controller\Jump;
use \think\Db;
use think\Request;
use think\Cache;
use tree;

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
            $username = cookie('autouser');
            $key = cookie('autokey');
            if (!empty($username) && !empty($key)) {
                $User = model('User');
                $userInfo = $User->checkAutoLogin();
                if (!empty($userInfo)) {
                    $this->_G['user'] = $userInfo;
                    $this->_G['username'] = $userInfo['username'];
                    $this->_G['is_admin'] = $userInfo['isadmin'] == 1 ? 1 : 0;  //管理员判断
                }
            } else {
                $this->_G['user'] = '';
                $this->_G['username'] = '';
                $this->_G['is_admin'] = 0;  //管理员判断
                //未登陆跳转
                if ($request->controller() != 'User' && $request->action() != 'Login') {
                    $this->redirect(url('/index/user/login'));
                }
            }
        }
        //当前所在模块位置信息
        $this->_G['module'] = strtolower($request->module());
        $this->_G['controller'] = strtolower($request->controller());
        $this->_G['action'] = strtolower($request->action());

//        //任务类型列表
//        $this->_G['type_list'] = [
//            'design' => '设计',
//            'devel' => '开发',
//            'test' => '测试',
//            'study' => '研究',
//            'discuss' => '讨论',
//            'ui' => '界面',
//            'affair' => '事务',
//            'misc' => '其他',
//            'BUG' => '修复BUG',
//        ];


        //读取并输出全局的导航链接
        $db_navigation_list = DB('Navigation')
                ->where(['status' => 1])
                ->order('sort ASC')
                ->select();
        //对url进行反向解析并存入urlData中
        foreach($db_navigation_list as $key => $tempNavigation) {
            $tempNavigation['urlData'] = analysisUrl($tempNavigation['url'] );
            $db_navigation_list[$key] = $tempNavigation;
        }
        $tree = new Tree($db_navigation_list);
        $navigation_list = array();
        $navigation_list = classifyTree($db_navigation_list);
        $this->assign('navigation_list', $navigation_list);
        $this->assign('_G', $this->_G);
        $this->assign('common_navigation_list', $common_navigation_list);
        //模板控制
        config('template', [
            // 模板引擎类型 支持 php think 支持扩展
            'type' => 'Think',
            // 模板路径
            'view_path' => '../application/template/index/view/default/',
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
