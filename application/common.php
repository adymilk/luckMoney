<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/*
      $total 红包总额
      $num 发几个
      $min  最小红包
*/
function get_hongbao($total, $num,$min=1)
{
    $money_arr = array();
    $return_arr = array();
    for ($i = 1; $i <$num; ++$i) {
        $max =round($total, 2)/($num-$i);
        $random =  0.01+ mt_rand() / mt_getrandmax() * (0.99- 0.01);
        $money = $random*$max;
        $money = $money<=$min?$min:$money;
        $money =floor($money*100)/100;
        $total = $total - $money;
        $money_arr[$i] = round($money, 2);
    }
    $money_arr[$i] = round($total, 2);
    shuffle($money_arr);
    $return_arr['money'] = $money_arr;
    $return_arr['total'] = array_sum($money_arr);
    return $return_arr;
}