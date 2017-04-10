<?php

/**
 * 通用的树型类
 * @date 2013年08月22日00:29:31
 */


class Tree {

    var $arr = array(); //生成树型结构所需要的2维数组
    var $icon = array('&nbsp;&nbsp;&nbsp;│', '&nbsp;&nbsp;&nbsp;├', '&nbsp;&nbsp;&nbsp;└'); //生成树型结构所需修饰符号
    var $ret = ''; // @access private
    var $list = array(); //直系目录列表数组
    var $subids = array(); //子目录ID

    //初始化类

    function tree($class_lists) {
        $arr = array();
        foreach ($class_lists as $data) {
            $arr[$data['id']] = $data;
        }
        $this->arr = $arr;
        $this->ret = '';
        return is_array($arr);
    }

    //获取所有直系上级目录列表
    function get_list($myid) {
        if ($this->arr[$myid]['parentid']) {
            $this->get_list($this->arr[$myid]['parentid']);
        }
        $this->list[] = $this->arr[$myid];
        return array_reverse($this->list);
    }

    //得到父级数组
    function get_parent($myid) {
        $newarr = array();
        if (!isset($this->arr))
            return false;
        $pid = $this->arr[$myid]['parentid'];
        $pid = $this->arr[$pid]['parentid'];
        if (is_array($this->arr)) {
            foreach ($this->arr as $id => $a) {
                if ($a['parentid'] == $pid)
                    $newarr[$id] = $a;
            }
        }
        return $newarr;
    }

    //得到子级数组
    function get_child($myid) {
        $a = $newarr = array();
        if (is_array($this->arr)) {
            foreach ($this->arr as $id => $a) {
                if ($a['parentid'] == $myid)
                    $newarr[$id] = $a;
            }
        }
        return $newarr ? $newarr : false;
    }

    //得到所有子级ID
    function get_subids($myid) {
        if (count($this->arr)) {
            foreach ($this->arr as $temp) {
                if ($temp['parentid'] == $myid) {
                    $this->subids[] = $temp['id'];
                    $this->get_subids($temp['id']);
                }
            }
        }
        $this->subids[] = $myid;
        $this->subids = array_unique($this->subids);
        return $this->subids;
    }

    /* -------------------------------------
     *  得到树型结构
     * -------------------------------------
     * @param $myid 表示获得这个ID下的所有子级
     * @param $str 生成树形结构基本代码, 例如: "<option value=\$id \$select>\$spacer\$name</option>"
     * @param $sid 被选中的ID, 比如在做树形下拉框的时候需要用到
     * @param $adds
     * @param $str_group
     */

    function get_tree($myid, $str = '', $sid = 0, $adds = '', $str_group = '') {
        $child = $this->get_child($myid);
        if (is_array($child)) {
            $total = count($child);
            $now_number = 1;
            foreach ($child as $id => $a) {
                $j = $k = '';
                if ($now_number == $total) {
                    $j .= $this->icon[2];
                } else {
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer = $adds ? $adds . $j : '';
                $select = $id == $sid ? 'selected' : '';
                @extract($a);
                $parentid == 0 && $str_group ? eval("\$nstr = \"$str_group\";") : eval("\$nstr = \"$str\";");
                $this->ret .= $nstr;
                $this->get_tree($id, $str, $sid, $adds . $k . '&nbsp;&nbsp;&nbsp;', $str_group);
                $now_number++;
            }
        }
        return $this->ret;
    }

    //格式化数组
    function getArray($myid = 0, $sid = 0, $adds = '') {
        $child = $this->get_child($myid);
        if (is_array($child)) {
            $now_number = 1;
            $total = count($child);
            foreach ($child as $id => $a) {
                 $j = $k = '';
                if ($now_number == $total) {
                    $j .= $this->icon[2];
                } else {
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $a['spacer'] = $adds ? $adds . $j : '';
                @extract($a);
                $this->ret[$a['id']] = $a;
                $fd = $adds . $k . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                $this->getArray($id, $sid, $fd);
                $now_number++;
            }
        }

        return $this->ret;
    }

}

?>