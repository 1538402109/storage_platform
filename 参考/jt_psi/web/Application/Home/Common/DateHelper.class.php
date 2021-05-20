<?php
namespace Home\Common;

class DateHelper
{


    /**
* 获取当月天数
* @param $date
* @param $rtype 1天数 2具体日期数组
* @return
*/
    public function get_day($date, $rtype = '1')
    {
        $tem = explode('-', $date);    //切割日期 得到年份和月份
        $year = $tem['0'];
        $month = $tem['1'];
        if (in_array($month, array( '1' , '3' , '5' , '7' ,' 8' ,  '10' , '12'))) {
            // $text = $year.'年的'.$month.'月有31天';
            $text = '31';
        } elseif ($month == 2) {
            if ($year%400 == 0 || ($year%4 == 0 && $year%100 !== 0)) {    //判断是否是闰年
        // $text = $year.'年的'.$month.'月有29天';
                $text = '29';
            } else {
                // $text = $year.'年的'.$month.'月有28天';
                $text = '28';
            }
        } else {
            // $text = $year.'年的'.$month.'月有30天';
            $text = '30';
        }
        if ($rtype == '2') {
            for ($i = 1; $i <= $text ; $i ++) {
                if ($i<10) {
                    $r[] = $year."-".$month."-0".$i;
                } else {
                    $r[] = $year."-".$month."-".$i;
                }
            }
        } else {
            $r = $text;
        }
        return $r;
    }
}