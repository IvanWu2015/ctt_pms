<?php

// +----------------------------------------------------------------------
// | ChinaTT.com
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ChinaTT.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: WuXin@ChinaTT.com> <http://www.ChinaTT.com>
// +----------------------------------------------------------------------

namespace app\common\model;

/**
 * 会员模型
 */
class User extends \think\Model {
//    /* 用户模型自动验证 */
//    protected $_validate = array(
//        /* 验证用户名 */
//        array('username', '1,30', -1, self::EXISTS_VALIDATE, 'length'), //用户名长度不合法
//        array('username', 'checkDenyMember', -2, self::EXISTS_VALIDATE, 'callback'), //用户名禁止注册
//        array('username', '', -3, self::EXISTS_VALIDATE, 'unique'), //用户名被占用
//
//        /* 验证密码 */
//        array('password', '6,30', -4, self::EXISTS_VALIDATE, 'length'), //密码长度不合法
//
//        /* 验证邮箱 */
//        array('email', 'email', -5, self::EXISTS_VALIDATE), //邮箱格式不正确
//        array('email', '1,32', -6, self::EXISTS_VALIDATE, 'length'), //邮箱长度不合法
//        array('email', 'checkDenyEmail', -7, self::EXISTS_VALIDATE, 'callback'), //邮箱禁止注册
//        array('email', '', -8, self::EXISTS_VALIDATE, 'unique'), //邮箱被占用
//
//        /* 验证手机号码 */
//        array('mobile', '//', -9, self::EXISTS_VALIDATE), //手机格式不正确 TODO:
//        array('mobile', 'checkDenyMobile', -10, self::EXISTS_VALIDATE, 'callback'), //手机禁止注册
//        array('mobile', '', -11, self::EXISTS_VALIDATE, 'unique'), //手机号被占用
//    );
//
//    /* 用户模型自动完成 */
//    protected $_auto = array(
//        array('password', 'think_ucenter_md5', self::MODEL_BOTH, 'function', UC_AUTH_KEY),
//        array('reg_time', NOW_TIME, self::MODEL_INSERT),
//        array('reg_ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
//        array('update_time', NOW_TIME),
//        array('status', 'getStatus', self::MODEL_BOTH, 'callback'),
//    );

    /**
     * 检测用户名是不是被禁止注册
     * @param  string $username 用户名
     * @return boolean          ture - 未禁用，false - 禁止注册
     */
    protected function checkDenyMember($username) {
        return true; //TODO: 暂不限制，下一个版本完善
    }

    /**
     * 检测邮箱是不是被禁止注册
     * @param  string $email 邮箱
     * @return boolean       ture - 未禁用，false - 禁止注册
     */
    protected function checkDenyEmail($email) {
        return true; //TODO: 暂不限制，下一个版本完善
    }

    /**
     * 检测手机是不是被禁止注册
     * @param  string $mobile 手机
     * @return boolean        ture - 未禁用，false - 禁止注册
     */
    protected function checkDenyMobile($mobile) {
        return true; //TODO: 暂不限制，下一个版本完善
    }

    /**
     * 密码错误次数处理
     * @param  string $user 用户信息
     * @return boolean        ture - 正常，false - 禁止注册
     */
    protected function checkFails($user) {
        $check_time = time() - 1800; //半小时内不准再尝试登陆
        if ($user['fails'] >= 5 && $user['last_fail'] > $check_time) {
            return FALSE;
        }
        return true;
    }

    /**
     * 修改密码
     * @param  string $uid 用户信息
     * @param string $password 密码
     * @return boolean        ture - 正常，false - 禁止注册
     */
    public function changePassword($uid, $password) {
        if (!is_numeric($uid) || empty($password)) {
            return false;
        }
        $user = $this->getUser($uid);
        $new_password = md5(md5($password) . $user['salt']);
        $this->where(['uid' => $uid])->update(['password' => $new_password]);
        return true;
    }

    /**
     * 注册一个新用户
     * @param  string $username 用户名
     * @param  string $password 用户密码
     * @param  string $email    用户邮箱
     * @param  string $mobile   用户手机号码
     * @return integer          注册成功-用户信息，注册失败-错误编号
     */
    public function register($username, $password, $email, $mobile) {
        $data = array(
            'username' => $username,
            'salt' => random(6),
            'password' => $password,
            'email' => $email,
            'mobile' => $mobile,
        );
        $data['password'] = md5(md5($password) . $data['salt']);
        //验证手机
        if (empty($data['mobile'])) {
            unset($data['mobile']);
        }

        /* 添加用户 */
        if ($this->create($data)) {
            $uid = $this->add();
            return $uid ? $uid : 0; //0-未知错误，大于0-注册成功
        } else {
            return $this->getError(); //错误详情见自动验证注释
        }
    }

    /**
     * 用户登录认证
     * @param  string  $username 用户名
     * @param  string  $password 用户密码
     * @param int $autologin 是否需要自动登陆
     * @param  integer $type     用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
     * @return integer           登录成功-用户ID，登录失败-错误编号
     */
    public function login($username, $password, $autologin = 0, $type = 1) {
        $map = array();
        switch ($type) {
            case 1:
                $map['username'] = $username;
                break;
            case 2:
                $map['email'] = $username;
                break;
            case 3:
                $map['mobile'] = $username;
                break;
            case 4:
                $map['uid'] = $username;
                break;
            default:
                return 0; //参数错误
        }
        /* 获取用户数据 */
        $user = $this->getUser($map);
        if (!$this->checkFails($user)) {
            return -3;  //密码错误次数过多
        }
        if (is_array($user) && $user['deleted'] == 0) {
            /* 验证用户密码 */
            if (md5(md5($password) . $user['salt']) === $user['password']) {
                $this->updateLogin($user['uid']);    //更新用户登录信息
                $this->autoLogin($user);          //登录用户
                write_action($user['username'], 0, 'user', $user['uid'], 'login'); //操作记录
                //记录用户COOKIE用于自动登陆
                if ($autologin == 1) {
                    cookie('autouser', $user['username'], 604800);
                    cookie('autokey', md5($user['username'] . $user['password']), 604800);
                }
                return $user['uid'];                 //登录成功，返回用户ID
            } else {
                $this->updateLogin($user['uid'], $user['fails'] + 1);    //更新用户登录信息
                return -2; //密码错误
            }
        } else {
            return -1; //用户不存在或被禁用
        }
    }

    /**
     * 获取用户信息
     * @param  string  $uid         用户ID或用户名
     * @param  boolean $is_username 是否使用用户名查询
     * @return array                用户信息
     */
    public function info($uid, $is_username = false) {
        $map = array();
        if ($is_username) { //通过用户名获取
            $map['username'] = $uid;
        } else {
            $map['uid'] = $uid;
        }
        $user = $this->getUser($map);
        //判断用户是否有效
        if (is_array($user) && $user['deleted'] == 0) {
            return $user;
        } else {
            return -1; //用户不存在或被禁用
        }
    }

    /**
     * 检测用户信息
     * @param  string  $field  用户名
     * @param  integer $type   用户名类型 1-用户名，2-用户邮箱，3-用户电话
     * @return integer         错误编号
     */
    public function checkField($field, $type = 1) {
        $data = array();
        switch ($type) {
            case 1:
                $data['username'] = $field;
                break;
            case 2:
                $data['email'] = $field;
                break;
            case 3:
                $data['mobile'] = $field;
                break;
            default:
                return 0; //参数错误
        }
        return $this->create($data) ? 1 : $this->getError();
    }

    /**
     * 更新用户登录信息
     * @param  integer $uid 用户ID
     * @param  integer $fails 1登陆失败
     */
    protected function updateLogin($uid, $fails = 0) {
        $data = array(
            'last' => time(),
            'last_ip' => request()->ip(),
            'fails' => 0,
            'last_fail' => 0,
        );
        if ($fails > 0) {
            $data['fails'] = $fails;
            $data['last_fail'] = time();
        }
        $this->where('uid', $uid)->update($data);
    }

    /**
     * 更新用户信息
     * @param int $uid 用户id
     * @param array $data 修改的字段数组
     * @return true 修改成功，false 修改失败
     * @author huajie <banhuajie@163.com>
     */
    public function updateUserFields($uid, $data) {
        if (empty($uid) || empty($data)) {
            $this->error = '参数错误！';
            return false;
        }
        //更新用户信息
        $data = $this->create($data);
        if ($data) {
            return $this->where(array('uid' => $uid))->update($data);
        }
        return false;
    }

    /**
     * 验证用户密码
     * @param int $uid 用户id
     * @param string $password_in 密码
     * @return true 验证成功，false 验证失败
     * @author huajie <banhuajie@163.com>
     */
    public function verifyUser($uid, $password_in) {
        $user = User::get(['uid' => $uid])->toArray();
        if (md5(md5($password_in) . $user['salt']) === $user['password']) {
            return true;
        }
        return false;
    }

    /**
     * 注销当前用户
     * @return void
     */
    public function logout() {
        session('user_auth', null);
        session('user_auth_sign', null);
        cookie('autouser', '');
        cookie('autokey', '');
    }

    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user) {
        /* 更新登录信息 */
        $data = array(
            'visits' => array('exp', '`visits`+1'),
            'last' => THINK_START_TIME,
            'last_ip' => request()->ip(),
        );
        $this->where('uid', $user['uid'])->update($data);

        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'uid' => $user['uid'],
            'username' => $user['username'],
            'last_login_time' => $user['last'],
        );

        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));
    }

    /**
     * 对自动登陆信息进行校验 正确则自动登陆用户
     */
    public function checkAutoLogin() {
        $username = cookie('autouser');
        $key = cookie('autokey');
        $user = $this->getUser(['username' => $username]);
        if ($key == md5($user['username'] . $user['password'])) {
            $this->autoLogin($user);          //登录用户
            return $user;
        }
        return false;
    }

    /**
     * 获取用户信息
     * @param type $param   获取参数 可直接uid或map
     * @return array        返回用户信息
     */
    public function getUser($param) {
        if (is_int($param)) {
            $user = User::get($param);
        } else {
            $user = User::get($param);
        }
        if ($user->data) {
            $user = $user->toArray();
        }
        return $user;
    }

}
