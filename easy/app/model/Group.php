<?php


namespace app\model;


use easy\Model;

class Group extends Model
{
    protected function getCreateTimeFormatAttr($val,$data){
        return date('Y-m-d H:i:s',$data['create_time']);
    }

    protected function getAuthAttr($val){
        return empty($val)?[]:explode(',',$val);
    }
}