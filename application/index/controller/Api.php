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
        $day = input('day', 1, 'intval');
        /**
         * todo:此处需要根据年月日数据返回对应的任务信息或是工时信息
         * 
         * 如果没有指定日则默认返回当月数据
         */
        //当月
        $firstday = date('Y-m-01', strtotime($date));
        
        
        
        
        $calendar_list = DB('Taskestimate')
                ->alias('e')
                ->join('chinatt_pms_task t', 'e.task = t.id', 'left')
                ->field('e.date as start,t.name as title')
                ->where(['t.assignedTo' => $this->_G['username']])
                ->select();
        $calendar = array($calendar_list);
        $data = json_encode($calendar);
        return $data;
    }

}
