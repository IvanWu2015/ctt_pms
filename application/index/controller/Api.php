<?php

/**
 * API集中处理
 */

namespace app\index\controller;

use \think\Db;

class api extends Common {

    //输出日历需要的工时
    public function getworkdata() {
        //接收年、月、日
        $year = input('year', 0, 'intval');
        $month = input('month', 0, 'intval');
        $day = input('day', 0, 'intval');
        /**
         * todo:此处需要根据年月日数据返回对应的任务信息或是工时信息
         * 
         * 如果没有指定日则默认返回当月数据
         */
        //当月
        $firstday = date('Y-m-01', strtotime($date));
        $lastday = date('Y-m-t', strtotime($date));
        
        
        return ;
    }

}
