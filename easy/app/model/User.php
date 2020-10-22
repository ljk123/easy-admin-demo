<?php


namespace app\model;


use easy\Model;

class User extends Model
{
    protected function getStatusWordAttr($val, $data)
    {
        return $data['status'] ? '有效' : '无效';
    }

    protected function getCreateTimeFormatAttr($val, $data)
    {
        return date('Y-m-d H:i:s', $data['create_time']);
    }

    protected function getLoginTimeFormatAttr($val, $data)
    {
        return date('Y-m-d H:i:s', $data['login_time']);
    }

    protected function getGroupNameAttr($val, $data)
    {
        return (new Group())->where(['id' => $data['group_id']])->value('name');
    }

    protected function getGroupAuthAttr($val, $data)
    {
        return (new Group())->where(['id' => $data['group_id']])->field('auth')->find()['auth'];
    }
}