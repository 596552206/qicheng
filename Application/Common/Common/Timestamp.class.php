<?php
namespace Common\Common;
class Timestamp{
    static public function format($timestamp){
        if(strlen($timestamp) == 13){
            return substr($timestamp, 0,10);
        }
        else{
            return $timestamp;
        }
    }
}