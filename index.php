<?php
/*实现至TP5入口的跳转
不过实际使用建议直接网站网站目录设置为public
*/

header("Location: http://". $_SERVER['SERVER_NAME'] . rtrim(dirname(rtrim($_SERVER['SCRIPT_NAME'], '/')), '/').'/public' );exit();
?>