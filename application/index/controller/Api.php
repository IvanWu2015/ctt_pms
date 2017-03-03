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
        $start = input('start', '', 'addslashes');
        $end = input('end', '', 'addslashes');
        //筛选条件
        $data['e.date'] = array('between time', [$start, $end]);
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
