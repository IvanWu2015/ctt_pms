<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 应用公共文件
// 

/**
 * 系统公共库文件
 * 主要定义系统公共函数库
 */

/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function is_login() {
    $user = session('user_auth');
    if (empty($user)) {
        return 0;
    } else {
        return session('user_auth_sign') == data_auth_sign($user) ? $user['uid'] : 0;
    }
}

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true) {
    if (function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif (function_exists('iconv_substr')) {
        $slice = iconv_substr($str, $start, $length, $charset);
        if (false === $slice) {
            $slice = '';
        }
    } else {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice . '...' : $slice;
}

/**
 * 系统加密方法
 * @param string $data 要加密的字符串
 * @param string $key  加密密钥
 * @param int $expire  过期时间 单位 秒
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function think_encrypt($data, $key = '', $expire = 0) {
    $key = md5(empty($key) ? config('DATA_AUTH_KEY') : $key);
    $data = base64_encode($data);
    $x = 0;
    $len = strlen($data);
    $l = strlen($key);
    $char = '';

    for ($i = 0; $i < $len; $i++) {
        if ($x == $l)
            $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }

    $str = sprintf('%010d', $expire ? $expire + time() : 0);

    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1))) % 256);
    }
    return str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($str));
}

/**
 * 系统解密方法
 * @param  string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
 * @param  string $key  加密密钥
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function think_decrypt($data, $key = '') {
    $key = md5(empty($key) ? config('DATA_AUTH_KEY') : $key);
    $data = str_replace(array('-', '_'), array('+', '/'), $data);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
        $data .= substr('====', $mod4);
    }
    $data = base64_decode($data);
    $expire = substr($data, 0, 10);
    $data = substr($data, 10);

    if ($expire > 0 && $expire < time()) {
        return '';
    }
    $x = 0;
    $len = strlen($data);
    $l = strlen($key);
    $char = $str = '';

    for ($i = 0; $i < $len; $i++) {
        if ($x == $l)
            $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }

    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        } else {
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return base64_decode($str);
}

/**
 * 数据签名认证
 * @param  array  $data 被认证的数据
 * @return string       签名
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function data_auth_sign($data) {
    //数据类型检测
    if (!is_array($data)) {
        $data = (array) $data;
    }
    ksort($data); //排序
    $code = http_build_query($data); //url编码并生成query字符串
    $sign = sha1($code); //生成签名
    return $sign;
}

/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0) {
    // 创建Tree
    $tree = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = & $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] = & $list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent = & $refer[$parentId];
                    $parent[$child][] = & $list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
 * 将list_to_tree的树还原成列表
 * @param  array $tree  原来的树
 * @param  string $child 孩子节点的键
 * @param  string $order 排序显示的键，一般是主键 升序排列
 * @param  array  $list  过渡用的中间数组，
 * @return array        返回排过序的列表数组
 * @author yangweijie <yangweijiester@gmail.com>
 */
function tree_to_list($tree, $child = '_child', $order = 'id', &$list = array()) {
    if (is_array($tree)) {
        $refer = array();
        foreach ($tree as $key => $value) {
            $reffer = $value;
            if (isset($reffer[$child])) {
                unset($reffer[$child]);
                tree_to_list($value[$child], $child, $order, $list);
            }
            $list[] = $reffer;
        }
        $list = list_sort_by($list, $order, $sortby = 'asc');
    }
    return $list;
}

/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function format_bytes($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++)
        $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 设置跳转页面URL
 * 使用函数再次封装，方便以后选择不同的存储方式（目前使用cookie存储）
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function set_redirect_url($url) {
    cookie('redirect_url', $url);
}

/**
 * 获取跳转页面URL
 * @return string 跳转页URL
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_redirect_url() {
    $url = cookie('redirect_url');
    return empty($url) ? __APP__ : $url;
}

/**
 * 处理插件钩子
 * @param string $hook   钩子名称
 * @param mixed $params 传入参数
 * @return void
 */
function hook($hook, $params = array()) {
    \Think\Hook::listen($hook, $params);
}

/**
 * 根据用户ID获取用户名
 * @param  integer $uid 用户ID
 * @return string       用户名
 */
function get_username($uid = 0) {
    static $list;
    if (!($uid && is_numericonfig($uid))) { //获取当前登录用户名
        return session('user_auth.username');
    }

    /* 获取缓存数据 */
    if (empty($list)) {
        $list = S('sys_active_user_list');
    }

    /* 查找用户信息 */
    $key = "u{$uid}";
    if (isset($list[$key])) { //已缓存，直接使用
        $name = $list[$key];
    } else { //调用接口获取用户信息
        $User = new User\Api\UserApi();
        $info = $User->info($uid);
        if ($info && isset($info[1])) {
            $name = $list[$key] = $info[1];
            /* 缓存用户 */
            $count = count($list);
            $max = config('USER_MAX_CACHE');
            while ($count-- > $max) {
                array_shift($list);
            }
            S('sys_active_user_list', $list);
        } else {
            $name = '';
        }
    }
    return $name;
}

/**
 * 获取分类信息并缓存分类
 * @param  integer $id    分类ID
 * @param  string  $field 要获取的字段名
 * @return string         分类信息
 */
function get_category($id, $field = null) {
    static $list;

    /* 非法分类ID */
    if (empty($id) || !is_numericonfig($id)) {
        return '';
    }

    /* 读取缓存数据 */
    if (empty($list)) {
        $list = S('sys_category_list');
    }

    /* 获取分类名称 */
    if (!isset($list[$id])) {
        $cate = M('Category')->find($id);
        if (!$cate || 1 != $cate['status']) { //不存在分类，或分类被禁用
            return '';
        }
        $list[$id] = $cate;
        S('sys_category_list', $list); //更新缓存
    }
    return is_null($field) ? $list[$id] : $list[$id][$field];
}

/* 根据ID获取分类标识 */

function get_category_name($id) {
    return get_category($id, 'name');
}

/**
 * 记录行为日志，并执行该行为的规则
 * @param string $action 行为标识
 * @param string $model 触发行为的模型名
 * @param int $record_id 触发行为的记录id
 * @param int $user_id 执行行为的用户id
 * @return boolean
 * @author huajie <banhuajie@163.com>
 */
function action_log($action = null, $model = null, $record_id = null, $user_id = null) {

    //参数检查
    if (empty($action) || empty($model) || empty($record_id)) {
        return '参数不能为空';
    }
    if (empty($user_id)) {
        $user_id = is_login();
    }

    //查询行为,判断是否执行
    $action_info = M('Action')->getByName($action);
    if ($action_info['status'] != 1) {
        return '该行为被禁用或删除';
    }

    //插入行为日志
    $data['action_id'] = $action_info['id'];
    $data['user_id'] = $user_id;
    $data['action_ip'] = ip2long(get_client_ip());
    $data['model'] = $model;
    $data['record_id'] = $record_id;
    $data['create_time'] = NOW_TIME;

    //解析日志规则,生成日志备注
    if (!empty($action_info['log'])) {
        if (preg_match_all('/\[(\S+?)\]/', $action_info['log'], $match)) {
            $log['user'] = $user_id;
            $log['record'] = $record_id;
            $log['model'] = $model;
            $log['time'] = NOW_TIME;
            $log['data'] = array('user' => $user_id, 'model' => $model, 'record' => $record_id, 'time' => NOW_TIME);
            foreach ($match[1] as $value) {
                $param = explode('|', $value);
                if (isset($param[1])) {
                    $replace[] = call_user_funconfig($param[1], $log[$param[0]]);
                } else {
                    $replace[] = $log[$param[0]];
                }
            }
            $data['remark'] = str_replace($match[0], $replace, $action_info['log']);
        } else {
            $data['remark'] = $action_info['log'];
        }
    } else {
        //未定义日志规则，记录操作url
        $data['remark'] = '操作url：' . $_SERVER['REQUEST_URI'];
    }

    M('ActionLog')->add($data);

    if (!empty($action_info['rule'])) {
        //解析行为
        $rules = parse_action($action, $user_id);

        //执行行为
        $res = execute_action($rules, $action_info['id'], $user_id);
    }
}

/**
 * 解析行为规则
 * 规则定义  table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
 * 规则字段解释：table->要操作的数据表，不需要加表前缀；
 *              field->要操作的字段；
 *              condition->操作的条件，目前支持字符串，默认变量{$self}为执行行为的用户
 *              rule->对字段进行的具体操作，目前支持四则混合运算，如：1+score*2/2-3
 *              cycle->执行周期，单位（小时），表示$cycle小时内最多执行$max次
 *              max->单个周期内的最大执行次数（$cycle和$max必须同时定义，否则无效）
 * 单个行为后可加 ； 连接其他规则
 * @param string $action 行为id或者name
 * @param int $self 替换规则里的变量为执行用户的id
 * @return boolean|array: false解析出错 ， 成功返回规则数组
 * @author huajie <banhuajie@163.com>
 */
function parse_action($action = null, $self) {
    if (empty($action)) {
        return false;
    }

    //参数支持id或者name
    if (is_numericonfig($action)) {
        $map = array('id' => $action);
    } else {
        $map = array('name' => $action);
    }

    //查询行为信息
    $info = M('Action')->where($map)->find();
    if (!$info || $info['status'] != 1) {
        return false;
    }

    //解析规则:table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
    $rules = $info['rule'];
    $rules = str_replace('{$self}', $self, $rules);
    $rules = explode(';', $rules);
    $return = array();
    foreach ($rules as $key => &$rule) {
        $rule = explode('|', $rule);
        foreach ($rule as $k => $fields) {
            $field = empty($fields) ? array() : explode(':', $fields);
            if (!empty($field)) {
                $return[$key][$field[0]] = $field[1];
            }
        }
        //cycle(检查周期)和max(周期内最大执行次数)必须同时存在，否则去掉这两个条件
        if (!array_key_exists('cycle', $return[$key]) || !array_key_exists('max', $return[$key])) {
            unset($return[$key]['cycle'], $return[$key]['max']);
        }
    }

    return $return;
}

/**
 * 执行行为
 * @param array $rules 解析后的规则数组
 * @param int $action_id 行为id
 * @param array $user_id 执行的用户id
 * @return boolean false 失败 ， true 成功
 * @author huajie <banhuajie@163.com>
 */
function execute_action($rules = false, $action_id = null, $user_id = null) {
    if (!$rules || empty($action_id) || empty($user_id)) {
        return false;
    }

    $return = true;
    foreach ($rules as $rule) {

        //检查执行周期
        $map = array('action_id' => $action_id, 'user_id' => $user_id);
        $map['create_time'] = array('gt', NOW_TIME - intval($rule['cycle']) * 3600);
        $exec_count = M('ActionLog')->where($map)->count();
        if ($exec_count > $rule['max']) {
            continue;
        }

        //执行数据库操作
        $Model = M(ucfirst($rule['table']));
        $field = $rule['field'];
        $res = $Model->where($rule['condition'])->setField($field, array('exp', $rule['rule']));

        if (!$res) {
            $return = false;
        }
    }
    return $return;
}

//基于数组创建目录和文件
function create_dir_or_files($files) {
    foreach ($files as $key => $value) {
        if (substr($value, -1) == '/') {
            mkdir($value);
        } else {
            @file_put_contents($value, '');
        }
    }
}

if (!function_exists('array_column')) {

    function array_column(array $input, $columnKey, $indexKey = null) {
        $result = array();
        if (null === $indexKey) {
            if (null === $columnKey) {
                $result = array_values($input);
            } else {
                foreach ($input as $row) {
                    $result[] = $row[$columnKey];
                }
            }
        } else {
            if (null === $columnKey) {
                foreach ($input as $row) {
                    $result[$row[$indexKey]] = $row;
                }
            } else {
                foreach ($input as $row) {
                    $result[$row[$indexKey]] = $row[$columnKey];
                }
            }
        }
        return $result;
    }

}

/**
 * 根据条件字段获取指定表的数据
 * @param mixed $value 条件，可用常量或者数组
 * @param string $condition 条件字段
 * @param string $field 需要返回的字段，不传则返回整个数据
 * @param string $table 需要查询的表
 * @author huajie <banhuajie@163.com>
 */
function get_table_field($value = null, $condition = 'id', $field = null, $table = null) {
    if (empty($value) || empty($table)) {
        return false;
    }

    //拼接参数
    $map[$condition] = $value;
    $info = M(ucfirst($table))->where($map);
    if (empty($field)) {
        $info = $info->field(true)->find();
    } else {
        $info = $info->getField($field);
    }
    return $info;
}

/**
 * 获取链接信息
 * @param int $link_id
 * @param string $field
 * @return 完整的链接信息或者某一字段
 * @author huajie <banhuajie@163.com>
 */
function get_link($link_id = null, $field = 'url') {
    $link = '';
    if (empty($link_id)) {
        return $link;
    }
    $link = M('Url')->getById($link_id);
    if (empty($field)) {
        return $link;
    } else {
        return $link[$field];
    }
}

/**
 * 获取文档封面图片
 * @param int $cover_id
 * @param string $field
 * @return 完整的数据  或者  指定的$field字段值
 * @author huajie <banhuajie@163.com>
 */
function get_cover($cover_id, $field = null) {
    if (empty($cover_id)) {
        return false;
    }
    $picture = M('Picture')->where(array('status' => 1))->getById($cover_id);
    return empty($field) ? $picture : $picture[$field];
}

/**
 * 检查$pos(推荐位的值)是否包含指定推荐位$contain
 * @param number $pos 推荐位的值
 * @param number $contain 指定推荐位
 * @return boolean true 包含 ， false 不包含
 * @author huajie <banhuajie@163.com>
 */
function check_document_position($pos = 0, $contain = 0) {
    if (empty($pos) || empty($contain)) {
        return false;
    }

    //将两个参数进行按位与运算，不为0则表示$contain属于$pos
    $res = $pos & $contain;
    if ($res !== 0) {
        return true;
    } else {
        return false;
    }
}

/**
 * 获取数据的所有子孙数据的id值
 * @author 朱亚杰 <xcoolcc@gmail.com>
 */
function get_stemma($pids, Model &$model, $field = 'id') {
    $collection = array();

    //非空判断
    if (empty($pids)) {
        return $collection;
    }

    if (is_array($pids)) {
        $pids = trim(implode(',', $pids), ',');
    }
    $result = $model->field($field)->where(array('pid' => array('IN', (string) $pids)))->select();
    $child_ids = array_column((array) $result, 'id');

    while (!empty($child_ids)) {
        $collection = array_merge($collection, $result);
        $result = $model->field($field)->where(array('pid' => array('IN', $child_ids)))->select();
        $child_ids = array_column((array) $result, 'id');
    }
    return $collection;
}

//统计项目工时总数
function get_project_consume($pros) {

    if ($pros['id'] > 0) {
        $project_id = $pros['id'];
        $task = db('Task');
        $task_data['project'] = ['eq', $project_id];
        $task_data['status'] = ['in', ['closed', 'done']];
        $task_data['deleted'] = ['eq', 0];
        $pros['estimate_count'] = $task->where($task_data)->sum('estimate');    //已完成的预计时间
        $count_data = $task->where(['project' => $project_id])->field('sum(estimate) as estimate_count')->find();
        $count_data['consumed_done_count'] = $task->where($task_data)->sum('estimate');
        $count_data['consumed_ing_count'] = $task->where(['project' => $project_id, 'status' => 'doing'])->sum('consumed');
        $count_data['consumed_count'] = $count_data['consumed_done_count'] + $count_data['consumed_ing_count'];
        $pros['all_time'] = $count_data['estimate_count'];  //总预计时间
        $pros['consumed_count'] = $count_data['consumed_count'];  //总消耗时间
        $pros['left_time'] = $task->where(['project' => $project_id, 'status' => 'wait'])->sum('consumed') + $task->where(['project' => $project_id, 'status' => 'doing'])->sum('`left`');
        $nodone_data['status'] = array('in', 'wait,doing');
        $nodone_data['deleted'] = array('eq', '0');
        $nodone_data['project'] = array('eq', $project_id);
        $pros['nodone'] = $task->where($nodone_data)->count();
        $pros['count_task'] = $task->where(['deleted' => 0, 'project' => $project_id])->count();
        if ($pros['estimate_count'] > 0) {
            $pros['percent'] = round($pros['estimate_count'] / $pros['all_time'] * 100, 2); //以预计时间计算的进度
        } else {
            $pros['percent'] = 0;
        }
        return $pros;
    } else {
        foreach ($pros as $key => $pro) {
            $pros[$key] = get_project_consume($pro);
        }
        return $pros;
    }
}

/**
 * 产生随机码
 * @param $length - 要多长
 * @param $numberic - 数字还是字符串
 * @return 返回字符串
 */
function random($length, $numeric = 0) {
    $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
    $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
    if ($numeric) {
        $hash = '';
    } else {
        $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
        $length--;
    }
    $max = strlen($seed) - 1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $seed{mt_rand(0, $max)};
    }
    return $hash;
}

/**
 * 获取项目成员列表
 * @param int $project_id 项目ID
 * @return array 该项目成员列表 以username为主键
 */
function get_userlist_by_projectid($project_id) {
    $user_list = db('team')->alias('t')->join('chinatt_pms_user u', 't.username = u.username')->where(['project' => $project_id])->column('u.uid,u.username,u.realname', 'u.username');
    return $user_list;
}

/**
 * 写入动态
 * @param $username - 用户名
 * @param $project_id - 项目id
 * @param $objectType - 对象类型
 * @param $objectid - 对象ID
 * @param $action - 动作
 * @param $comment - 内容
 * @param $extra - 对象名称
 * @return 返回字符串
 */
function write_action($username, $project_id, $objectType, $objectid, $action, $comment = '', $extra = '') {

    $action_data = array(
        'actor' => $username,
        'project' => $project_id,
        'objectType' => $objectType,
        'action' => $action,
        'comment' => $comment,
        'objectID' => $objectid,
        'date' => date('Y-m-d H:i:s'),
        'extra' => $extra,
    );
    DB('Action')->insert($action_data);
    return;
}

/**
 * 获取项目动态列表
 * @param int $task_id 任务ID
 */
function analysis($task_id) {
//  $action_list = DB('Action')->where(['objectID' => $task_id])->order('id')->select();
    $action_list = DB('Action')
            ->alias('a')
            ->join('chinatt_pms_taskestimate b', 'a.extra =b.id', 'left')
            ->field('a.*,b.left,b.consumed,b.username')
            ->where("a.objectID = $task_id AND a.objectType = 'task'")
            ->order('a.date')
            ->select();
    $action_list = analysis_all($action_list);
    return $action_list;
}

/**
 * 获取项目动态列表
 * @param int $action_list 对象列表
 */
function analysis_all($action_list) {
//    $action_list = DB('Action')->where(['objectID' => $task_id])->order('id')->select();
    foreach ($action_list as $key => $value) {
        if ($value['objectType'] == 'product') {
            $value['typename'] = "产品";
        } elseif ($value['objectType'] == 'project') {
            $value['typename'] = "项目";
        } elseif ($value['objectType'] == 'task') {
            $value['typename'] = "任务";
        } elseif ($value['objectType'] == 'user') {
            $value['typename'] = "用户";
        } elseif ($value['objectType'] == 'bug') {
            $value['typename'] = "BUG";
        } elseif ($value['objectType'] == 'build') {
            $value['typename'] = "创建";
        } elseif ($value['objectType'] == 'navigation') {
            $value['typename'] = $value['comment'] . "的导航";
        } elseif ($value['objectType'] == 'class') {
            $value['typename'] = $value['comment'] . "的分类";
        }
        if ($value['action'] == 'assignedTo') {
            $username = $action_list[$key]['extra'];
            $value['actionname'] = "指派给 <b>$username</b>";
        } elseif ($value['action'] == 'started') {
            $value['actionname'] = "开始 (消耗{$value['consumed']}小时,剩余{$value['left']}小时)";
        } elseif ($value['action'] == 'closed') {
            $value['actionname'] = "关闭";
        } elseif ($value['action'] == 'finished') {
            $value['actionname'] = "完成";
        } elseif ($value['action'] == 'opened') {
            $value['actionname'] = "创建";
        } elseif ($value['action'] == 'login') {
            $value['actionname'] = "登陆";
        } elseif ($value['action'] == 'edited') {
            $value['actionname'] = "编辑";
        } elseif ($value['action'] == 'changed') {
            $value['actionname'] = "修改";
        } elseif ($value['action'] == 'resolved') {
            $value['actionname'] = "暂停";
        } elseif ($value['action'] == 'update') {
            $value['actionname'] = "修改";
        } elseif ($value['action'] == 'started') {
            $value['actionname'] = "开始";
        } elseif ($value['action'] == 'done') {
            $value['actionname'] = "完成{$value['consumed']}";
        } elseif ($value['action'] == 'recordestimate') {
            $value['actionname'] = "记录工时(消耗{$value['consumed']}小时,剩余{$value['left']}小时)";
        } elseif ($value['action'] == 'commented') {
            $value['actionname'] = "添加了备注";
        } elseif ($value['action'] == 'add') {
            $value['actionname'] = "添加";
        } elseif ($value['action'] == 'deleted') {
            $value['actionname'] = "删除";
        } elseif ($value['action'] == 'updata') {
            $value['actionname'] = "修改";
        } elseif ($value['action'] == 'paused') {
            $value['actionname'] = "暂停";
        } elseif ($value['action'] == 'restarted') {
            $value['actionname'] = "重启";
        } elseif ($value['action'] == 'doing') {
            $value['actionname'] = "开始";
        } elseif ($value['action'] == 'cancel') {
            $value['actionname'] = "取消";
        }
        $action_list[$key] = $value;
    }

    return $action_list;
}

/**
 * 格式化任务
 * @param int $action_list 任务列表
 */
function format_task($task_list) {
    if ($task_list['id'] > 0) {
        if ($task_list['type'] == 'devel') {
            $task_list['typename'] = "开发";
        } elseif ($task_list['type'] == 'design') {
            $task_list['typename'] = "设计";
        } elseif ($task_list['type'] == 'test') {
            $task_list['typename'] = "测试";
        } elseif ($task_list['type'] == 'study') {
            $task_list['typename'] = "研究";
        } elseif ($task_list['type'] == 'discuss') {
            $task_list['typename'] = "讨论";
        } elseif ($task_list['type'] == 'ui') {
            $task_list['typename'] = "界面";
        } elseif ($task_list['type'] == 'affair') {
            $task_list['typename'] = "事务";
        } elseif ($task_list['type'] == 'misc') {
            $task_list['typename'] = "其他";
        } elseif ($task_list['type'] == 'BUG') {
            $task_list['typename'] = "BUG";
        }

        if ($task_list['status'] == 'wait') {
            $task_list['status_name'] = '等待';
        } elseif ($task_list['status'] == 'done') {
            $task_list['status_name'] = '完成';
        } elseif ($task_list['status'] == 'doing') {
            $task_list['status_name'] = '进行中';
        } elseif ($task_list['status'] == 'closed') {
            $task_list['status_name'] = '关闭';
        } elseif ($task_list['status'] == 'cancel') {
            $task_list['status_name'] = '取消';
        }
        return $task_list;
    } else {
        foreach ($task_list as $key => $value) {
            $task_list[$key] = format_task($value);
        }
    }return $task_list;
}

/**
 * 获取用户各类总数
 * @param int $action_list 任务列表
 */
function user_count($user_lists) {
    if ($user_lists['uid'] > 0) {
        //发布任务总数
        $task_count = DB('User')
                ->alias('u')
                ->join('chinatt_pms_task t', 'u.username = t.openedBy', 'left')
                ->field('u.username,t.*')
                ->where(['t.openedBy' => $user_lists['username']])
                ->count();
        $project_count = DB('User')
                ->alias('u')
                ->join('chinatt_pms_project p', 'u.username = p.openedBy', 'left')
                ->field('u.username,t.*')
                ->where(['p.openedBy' => $user_lists['username']])
                ->count();
        $task_data['t.status'] = array('in', 'close,done');
        $task_data['t.assignedTo'] = array('eq', $user_lists['username']);
        $done_task_count = DB('User')
                ->alias('u')
                ->join('chinatt_pms_task t', 'u.username = t.assignedTo', 'left')
                ->field('u.username,t.*')
                ->where($task_data)
                ->count();
        $not_task['t.status'] = array('in', 'wait,doing');
        $not_task['t.assignedTo'] = array('eq', $user_lists['username']);
        $not_task_count = DB('User')
                ->alias('u')
                ->join('chinatt_pms_task t', 'u.username = t.assignedTo', 'left')
                ->field('u.username,t.*')
                ->where($not_task)
                ->count();
        $user_lists['task_count'] = $task_count;
        $user_lists['project_count'] = $project_count;
        $user_lists['done_task_count'] = $done_task_count;
        $user_lists['not_task_count'] = $not_task_count;
        return $user_lists;
    } else {
        foreach ($user_lists as $key => $value) {
            $user_lists[$key] = user_count($value);
        }
    }
    return $user_lists;
}

/**
 * 判断是否项目成员
 * @param str $username 用户名
 * @param array $userlist 项目成员列表
 * @return boolean 是否项目成员
 */
function isProjectUser($username, $userlist) {
    $username_arr = [];
    foreach ($userlist as $tempuser) {
        $username_arr[] = $tempuser['username'];
    }
    if (in_array($username, $username_arr)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 工时统计
 * @param str $subjectTYPE 对象类型
 * @param array $objectID 操作对象ID
 * @param array $username 用户
 * @param array $consumed 消耗时间
 * @return boolean 是否项目成员
 */
function working_count($subjectTYPE, $objectID, $username, $consumed) {
    if ($objectID > 0) {
        if ($subjectTYPE == 'project') {
            $task_detail = DB('Task')->where(['id' => $objectID])->find();
            $working_data['type'] = $task_detail['type'];
        }

        $working_data = [
            'objectType' => $subjectTYPE,
            'objectID' => $objectID,
            'username' => $username,
            'date' => date('Y-m-d'),
            'consumed' => $consumed,
            'lasttime' => time(),
            'week' => date("W"),
        ];
        $working_detail = DB('Workcount')->where(['objectType' => $subjectTYPE, 'objectID' => $objectID, 'username' => $username, 'date' => date('Y--m-d')])->find();


        if (empty($working_detail)) {
            DB('Workcount')->insert($working_data);
        } else {
            $save_data = [
                'consumed' => $working_detail['consumed'] + $consumed,
                'lasttime' => time(),
            ];
            DB('Workcount')->where(['objectType' => $subjectTYPE, 'objectID' => $objectID, 'username' => $username, 'date' => date('Y--m-d')])->update($save_data);
        }
    }
}

/**
 * 用户列表统计工时
 * @param str $user_list 用户列表
 * @param array $type 类型
 * @param array $startdate 开始
 * @param array $enddate 结束
 */
function get_user_count($user_list, $startdate, $enddate) {
    foreach ($user_list as $key => $value) {
        $value['workcount'] = get_count($value['username'], $startdate, $enddate);
        $user_list[$key] = $value;
    }
    return $user_list;
}

/**
 * 获取用户工时
 * @param str $user 用户
 * @param array $type 类型
 * @param array $startdate 开始
 * @param array $enddate 结束
 */
function get_count($user, $startdate, $enddate) {
    //获取今天的工时
    $today_data['username'] = array('eq', $user);
    $today_data['objectType'] = array('eq', 'user');
    $startdate = $enddate = date('Y-m-d');
    $today_data['date'] = array('eq', "$startdate");
    $count['today_working'] = intval(DB('Workcount')->where($today_data)->sum('consumed'));
    //今天创建的任务的预计总工时
    $not_today_data['assignedTo'] = array('eq', $user);
    $not_today_data['openedDate'] = array('like', "%$startdate%");
    $count['not_today_working'] = intval(DB('Task')->where($not_today_data)->sum('estimate'));
    //获取当周的工时
    $sdefaultDate = date("Y-m-d");
    $first = 0;
    $w = date('w', strtotime($sdefaultDate));
    $startdate = date('Y-m-d', strtotime("$sdefaultDate -" . ($w ? $w - $first : 6) . ' days'));
    $enddate = date('Y-m-d', strtotime("$startdate +6 days"));
    $week_data['date'] = array('between time', "$startdate,$enddate");
    $week_data['username'] = array('eq', $user);
    $week_data['objectType'] = array('eq', 'user');
    $count['week_working'] = DB('Workcount')->where($week_data)->sum('consumed');

    //获取当月工时
    $startdate = date('Y-m-01');
    $enddate = date('Y-m-t');
    $month_data['date'] = array('between time', "$startdate,$enddate");
    $month_data['username'] = array('eq', $user);
    $month_data['objectType'] = array('eq', 'user');
    $count['month_working'] = DB('Workcount')->where($month_data)->sum('consumed');

    return $count;
}

//访问操作日志存储
function save_log($uid, $username) {
    global $_G;
    $log = array(
        'uid' => $uid,
        'username' => $username,
        'url' => $_SERVER['REQUEST_URI'],
        'get' => $_SERVER['REQUEST_URI'],
        'post' => empty($_POST) ? '' : serialize($_POST),
        'time' => date('Y-m-d H:i:s'),
        'type' => 'common',
    );
    db('Log')->insert($log);
    return true;
}

/**
 * 对读取出来的多级菜单进行归类为树形多级数组
 * @param type $db_navigation_list  有层级关系的列表数组
 * @param type $parent_id
 * @return array
 */
function classifyTree($db_navigation_list, $parent_id = 0) {
    foreach ($db_navigation_list as $temp_nav) {
        $temp_nav['true_url'] = strpos($temp_nav['url'], 'http://') !== false ? $temp_nav['url'] : url($temp_nav['url']);
        if ($temp_nav['parentid'] == $parent_id) {
            if ($parent_id == 0) {
                $navgation_list[$temp_nav['id']]['main'] = $temp_nav;
            } else {
                $navgation_list[$temp_nav['id']] = $temp_nav;
            }
            $sub_navlist = classifyTree($db_navigation_list, $temp_nav['id']);
            if (!empty($sub_navlist)) {
                $navgation_list[$temp_nav['id']]['sub_count'] = count($sub_navlist);
                $navgation_list[$temp_nav['id']]['sub'] = $sub_navlist;
            }
            $sub_navlist = array();
        }
    }
    return $navgation_list;
}

/**
 * 获取用户名对应的真名
 * @param string $username  用户名
 * @param int $onlyRealname 是否只需要真名
 * @return string or array
 */
function getUser($username, $onlyRealname = 1) {

    $userlist = cache('userlist');
    //读取用户列表并写入缓存
    if (empty($userlist)) {
        $userlist = db('user')->column('uid,username,realname', 'username');
        cache('userlist', $userlist, 1800);    //缓存时间为半小时
    }
    $user = $onlyRealname == 1 ? $userlist[$username]['realname'] : $userlist[$username];
    return $user;
}

/**
 * 获取用户拥有项目权限的IDS
 * @param string $username  用户名
 * @return string $ids      
 */
function getUserprojectids($username) {
    $project_list = db('project')
            ->alias('p')
            ->join('chinatt_pms_team t', "t.project = p.id ", 'left')
            ->where(['t.username' => $username])
            ->whereOr(['p.acl' => 'open'])
            ->group('p.id')
            ->select();
    foreach ($project_list as $key => $value) {
        $project_username_list[] = $value['project'];
        if ($key == 0) {
            $ids .= $value['id'];
        } else {
            $ids .= ',' . $value['id'];
        }
    }
    return $ids;
}

/**
 * 获取用户拥有项目列表
 * @param string $username  用户名
 * @return string $list
 */
function getUserProjectList($username) {
    $project_list = db('project')
                    ->alias('p')
                    ->join('chinatt_pms_team t', "t.project = p.id ", 'left')
                    ->where(function ($query) {
                        $query->where(['p.acl' => 'private', 't.username' => 'chen']);
                    })->whereOr(function ($query) {
                        $query->where(['p.acl' => 'open']);
                    })
                    ->group('p.id')->select();
    return $project_list;
}

/**
 * urt反向解析为对应的模块控制器等
 * @param str $url
 * @return array 返回对应的模块、控制器、方法名称
 */
function analysisUrl($url) {
    if (strpos($url, '/') !== false) {
        $request = request();
        $urlArray = explode('/', $url);
        if (strpos($url, '/') === 0) {
            unset($urlArray[0]);
        }
        krsort($urlArray);
        $urlArray = array_values($urlArray);
        $returnData['action'] = empty($urlArray[0]) ? $request->action() : $urlArray[0];
        $returnData['controller'] = empty($urlArray[1]) ? $request->controller() : $urlArray[1];
        $returnData['module'] = empty($urlArray[2]) ? $request->module() : $urlArray[2];
        return $returnData;
    } else {
        return $url;
    }
}

/**
 * 返回指定分组的设置列表
 * @param type $groupName
 * @return type
 */
function getCommonConfigList($groupName = '') {
    $config = db('config');
    if($groupName) {
        $map['group'] = $groupName;
    }
    $configList = $config->where($map)->select();
    $returnConfig = array();
    foreach ($configList as $config) {
        $returnConfig[$config['c_key']] = $config['c_value'];
    }
    return $returnConfig;
    return;
}