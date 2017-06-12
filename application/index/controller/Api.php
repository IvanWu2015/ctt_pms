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
                ->field('e.date as start,t.name as title,t.id as tid, e.consumed,t.type')
                ->where($data)
                ->select();
        
        foreach ($calendar_list as $key => $value) {
            $calendar_list[$key]['title'] = $calendar_list[$key]['consumed'].' '.$calendar_list[$key]['title'];
            $calendar_list[$key]['url'] = url('index/task/detail?id=' . $value['tid']);
            //todo:根据不同的任务类型 设置不同的颜色 
            
            if($value['type'] == 'bug'){
                $calendar_list[$key]['color'] = '#d24d0e';
            }elseif ($value['type'] == 'affair') {
                $calendar_list[$key]['color'] = '#d2720e';
            }elseif ($value['type'] == 'design') {
                $calendar_list[$key]['color'] = '##0ec0d2';
            }elseif ($value['type'] == 'misc') {
                $calendar_list[$key]['color'] = '#0e90d2';
            }elseif ($value['type'] == 'ui') {
                $calendar_list[$key]['color'] = '#0ed2c9';
            }elseif ($value['type'] == 'study') {
                $calendar_list[$key]['color'] = '##c6d20e';
            }elseif ($value['type'] == 'test') {
                $calendar_list[$key]['color'] = '#0ed2a5';
            }elseif ($value['type'] == 'devel') {
                $calendar_list[$key]['color'] = '##110ed2';
            }
            
            
        }
        return $calendar_list;
    }

}
