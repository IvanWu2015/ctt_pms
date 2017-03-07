<?php

// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace app\index\controller;

load_trait('controller/Jump');  // 引入traits\controller\Jump

/**
 * 用户控制器
 * 包括用户中心，用户登录及注册
 */
class User extends Common {

    use \traits\controller\Jump;

    /* 注册页面 */

    public function register($username = '', $password = '', $repassword = '', $email = '', $verify = '') {
        if (!config('USER_ALLOW_REGISTER')) {
            $this->error('注册已关闭');
        }
        if (request()->isPost()) { //注册用户
            /* 检测验证码 */
            if (!check_verify($verify)) {
                $this->error('验证码输入错误！');
            }

            /* 检测密码 */
            if ($password != $repassword) {
                $this->error('密码和重复密码不一致！');
            }

            /* 调用注册接口注册用户 */
            $User = new UserApi;
            $uid = $User->register($username, $password, $email);
            if (0 < $uid) { //注册成功
                //TODO: 发送验证邮件
                $this->success('注册成功！', url('login'));
            } else { //注册失败，显示错误信息
                $this->error($this->showRegError($uid));
            }
        } else { //显示注册表单
            $navtitle = '用户注册';
            $this->assign('navtitle', $navtitle);
            return $this->fetch($this->templatePath);
        }
    }

    /* 登录页面 */
    public function login($username = '', $password = '', $verify = '') {
        if (request()->isPost()) { //登录验证
            /* 检测验证码 */
            if (!captcha_check($verify)) {
                $this->error('验证码输入错误！', url('/index/user/login'));
            }

            $User = model('User');
            $autologin = input('autologin', 0, 'intval');
            $uid = $User->login($username, $password, $autologin);
            if ($uid > 0) { //登录成功
                $this->success('登录成功！', url('/index/index'), ['status' => 1]);
            } else { //登录失败
                switch ($uid) {
                    case -1: $error = '用户不存在或被禁用！';
                        break; //系统级别禁用
                    case -2: $error = '密码错误！';
                        break;
                    case -3: $error = '密码错误次数过多，锁定中！';
                        break;
                    default: $error = '未知错误！';
                        break; // 0-接口参数错误（调试阶段使用）
                }
                $this->error($error);
            }
        } else { //显示登录表单
            $navtitle = '用户登陆';
            $this->assign('navtitle', $navtitle);
            return $this->fetch($this->templatePath);
        }
    }

    /* 退出登录 */

    public function logout() {
        if (is_login()) {
            model('User')->logout();
            $this->success('退出成功！', url('User/login'));
        } else {
            $this->redirect('User/login');
        }
    }

    /**
     * 获取用户注册错误信息
     * @param  integer $code 错误编码
     * @return string        错误信息
     */
    private function showRegError($code = 0) {
        switch ($code) {
            case -1: $error = '用户名长度必须在16个字符以内！';
                break;
            case -2: $error = '用户名被禁止注册！';
                break;
            case -3: $error = '用户名被占用！';
                break;
            case -4: $error = '密码长度必须在6-30个字符之间！';
                break;
            case -5: $error = '邮箱格式不正确！';
                break;
            case -6: $error = '邮箱长度必须在1-32个字符之间！';
                break;
            case -7: $error = '邮箱被禁止注册！';
                break;
            case -8: $error = '邮箱被占用！';
                break;
            case -9: $error = '手机格式不正确！';
                break;
            case -10: $error = '手机被禁止注册！';
                break;
            case -11: $error = '手机号被占用！';
                break;
            default: $error = '未知错误';
        }
        return $error;
    }

    /**
     * 修改密码提交
     * @author huajie <banhuajie@163.com>
     */
    public function profile() {
        if (!$this->_G['uid']) {
            $this->error('您还没有登陆', url('User/login'));
        }
        if (request()->isPost()) {
            //获取参数
            $uid = $this->_G['uid'];
            $password = input('post.old');
            $repassword = input('post.repassword');
            $data['password'] = input('post.password');
            empty($password) && $this->error('请输入原密码');
            empty($data['password']) && $this->error('请输入新密码');
            empty($repassword) && $this->error('请输入确认密码');

            if ($data['password'] !== $repassword) {
                $this->error('您输入的新密码与确认密码不一致');
            }
            $user = model('User');
            //验证原密码
            if($user->verifyUser($uid, $password)) {
                $result = $user->changePassword($uid, $repassword);
                if ($result) {
                    $this->logout();
                    $this->success('修改密码成功！', url('/index/user/logout'));
                } else {
                    $this->error('修改失败。');
                }
            } else {
                $this->error('修改失败，原密码错误。');
            }
        } else {
            $navtitle = '密码修改';
            $this->assign('navtitle', $navtitle);
            return $this->fetch($this->templatePath);
        }
    }

}
