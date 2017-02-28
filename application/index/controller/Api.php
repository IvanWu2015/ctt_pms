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
        //当日
        $day = 0;
        if($month < 10){
            $month = '0' . $month;
        }
        if($day > 1){
            $firstday = date($day);
        }elseif($day == 0){
            $firstday = '';
        }else {
            $firstday = date('d');
        }

        if($month >= 1){
            $month = date($month). '-';
        } else {
            $month = date('m'). '-';
        }
        
        if($year > 0){
            $year = date($year). '-';
        }  else {
            $year = date('Y'). '-';
        }
        $date = $year . $month  . $firstday;
        $data['t.assignedTo'] = array('eq',$this->_G['username']);
        $data['e.date'] = array('like',"%$date%");
        $calendar_list = DB('Taskestimate')
                ->alias('e')
                ->join('chinatt_pms_task t', 'e.task = t.id', 'left')
                ->field('e.date as start,t.name as title')
                ->where($data)
                ->select();
        $calendar = $calendar_list;
                var_dump($calendar);exit;

        return $calendar;
    }

}
