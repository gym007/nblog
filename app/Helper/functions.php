<?php
/*
 * 专门用来处理数据公共函数文件
 */

/**
 * 将包含父子关系的数据整合成树状数据
 * @param $data array 原始数据
 * @param int $pid 父id
 * @return array 树状数据
 */
function p2s($data, $pid = 0)
{
    // dd($data);
    // 重组数据
    $tree = [];
    if (!empty($data)) {
        $new_data = [];
        foreach ($data as $key => $val) {
            $new_data[$val['id']] = $val;
        }
        foreach ($new_data as $value) {
            if ($pid == $value['pid']) {
                $tree[] = &$new_data[$value['id']];
            } elseif (isset($new_data[$value['pid']])) {
                $new_data[$value['pid']]['children'][] = &$new_data[$value['id']];
            }
        }
    }
    return $tree;
}

/**
 * 将树状数组转化为普通的二维数组并按顺序排列
 */
function tree2list($data, &$arr = [])
{
    // dd($data);
    foreach ($data as $datum) {
        if (isset($datum['children'])) {
            $copy = $datum;
            unset($copy['children']);
            $arr[] = $copy;
            tree2list($datum['children'], $arr);
        } else {
            $arr[] = $datum;
        }
    }
    return $arr;
}

function getLevel($data, $pid, &$level = 0)
{
    if ($pid == -1) return 0;
    if ($pid == 0) {
        $level++;
        return $level;
    }

    foreach ($data as $id => $datum) {
        if ($id == $pid) {
            $level++;
            return getLevel($data, $datum['pid'], $level);
        }
    }
}
