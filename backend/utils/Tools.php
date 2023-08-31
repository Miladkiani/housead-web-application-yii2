<?php
namespace backend\utils;


class Tools {
    public static function changeToFa($number, $separator = false)
    {
        if (empty($number)){
            return '';
        }
        $en = array("0","1","2","3","4","5","6","7","8","9");
        $fa = array("۰","۱","۲","۳","۴","۵","۶","۷","۸","۹");

        if ($separator){
            return str_replace($en, $fa, number_format($number,0,".",","));
        }else{
            return str_replace($en, $fa, $number);
        }
    }

    public static function getThumbSize($width,$height,$max){
        if ($width <= $max && $height <= $max){
            $ratio = 1;
        }else if($width>$height){
            $ratio = $max / $width;
        }else{
            $ratio =  $max / $height;
        }
       return ['thumbWidth'=> round($width*$ratio),
        'thumbHeight'=>round($height*$ratio)];
    }

}

