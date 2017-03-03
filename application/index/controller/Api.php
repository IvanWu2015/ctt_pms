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
        $year = $year > 0 ? $year : date("Y");  //年
        $month = $month > 0 ? $month : date("m");//月
        $firstday = $day > 0 ? $day : '';//起始日
        //数字补零操作
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        $day = str_pad($day, 2, '0', STR_PAD_LEFT);
        //组合日期
        $date = $year.'-'. $month .'-'. $firstday;
        //筛选条件
        $data['e.date'] = array('like', "$date%");
        $data['e.username'] = ['eq', $this->_G['username']];
        //工时表为主
        $calendar_list = DB('Taskestimate')
                ->alias('e')
                ->join('chinatt_pms_task t', 'e.task = t.id', 'left')
                ->field('e.date as start,t.name as title,t.id as tid')
                ->where($data)
                ->select();
        foreach ($calendar_list as $key => $value) {
            $calendar_list[$key]['href'] = url('index/task/detail?id=' . $value['tid']);
        }
        return $calendar_list;
    }

}
