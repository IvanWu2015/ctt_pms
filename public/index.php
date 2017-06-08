<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 异常错误报错级别,
error_reporting(E_ERROR | E_PARSE );
// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
// 定义类库目录
define('EXTEND_PATH', __DIR__ . '/../application/extend/');

if(!is_file(APP_PATH . 'config.php')){
	header('Location: ./install.php');
	exit;
}

// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
