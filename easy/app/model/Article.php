<?php


namespace app\model;


use easy\Container;
use easy\Model;

/**
 * Class Group
 * @validate \app\validate\Article
 * @package app\model
 */
class Article extends Model
{
    protected function getStatusWordAttr($val, $data)
    {
        return $data['status'] ? '有效' : '无效';
    }

    protected function getCreateTimeFormatAttr($val, $data)
    {
        return date('Y-m-d H:i:s', $data['create_time']);
    }

    protected function getUpdateTimeFormatAttr($val, $data)
    {
        return date('Y-m-d H:i:s', $data['update_time']);
    }

    protected function getCateNameAttr($val, $data)
    {
        if (is_null($ArticleCate = Container::getInstance()->get(ArticleCate::class))) {
            $ArticleCate = new ArticleCate();
            Container::getInstance()->bind(ArticleCate::class, $ArticleCate);
        }
        return $ArticleCate->where(['id' => $data['cid']])->value('name');
    }
}