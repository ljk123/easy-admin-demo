<?php


namespace app\controller\admin;


use app\model\ArticleCate;
use easy\Exception;
use easy\exception\InvalidArgumentException;
use easy\Request;
use app\model\Article as ArticleModel;
use app\model\ArticleCate as ArticleCateModel;

class Article extends Common
{
    /**
     *
     * @permission 内容管理-文章管理
     * @param Request $request
     * @param ArticleModel $article
     * @return array|bool
     */
    public function lists(Request $request, ArticleModel $article)
    {
        $p = (int)$request->get('p');
        if ($p < 1) {
            $p = 1;
        }
        return self::success($article->page($p)->append('cate_name,create_time_format,update_time_format,status_word')->select());
    }

    /**
     * @permission 内容管理-文章管理-新增/修改
     * @param Request $request
     * @param ArticleModel $article
     * @return array
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function save(Request $request, ArticleModel $article)
    {
        $data = $request->only(['id', 'cid', 'title', 'content', 'author', 'status']);
        if (false === $article->validate($data)) {
            return self::error($article->getError());
        }
        $id = $data['id'];
        unset($data['id']);
        if (!empty($id)) {
            if (false === $info = $article->where(['id' => $id])->find()) {
                return self::error($article->getError());
            }
            if (empty($info)) {
                return self::error('未查找到数据');
            }
            $data['update_time'] = time();
            if (false === $num = $article->where(['id' => $id])->save($data)) {
                return self::error('修改数据失败');
            }
            if ($num === 0) {
                return self::error('您未修改数据');
            }
        } else {
            $data['create_time'] = $data['update_time'] = time();
            if (false === $id = $article->add($data)) {
                self::error($article->getError());
            }
        }
        return self::success('操作成功');
    }

    /**
     * @permission 内容管理-文章管理-删除
     * @param Request $request
     * @param ArticleModel $article
     * @return array
     * @throws InvalidArgumentException
     */
    public function del(Request $request, ArticleModel $article)
    {
        $id = $request->post('id');
        if (empty($id)) {
            return self::error('参数错误');
        }
        $info = $article->where(['id' => $id])->find();
        if (empty($info)) {
            return self::error('不存在的分类');
        }
        if (false === $article->where(['id' => $id])->delete()) {
            return self::error($article->getError());
        }
        return self::success('删除成功');

    }

    /**
     * @permission 内容管理-分类管理
     * @param ArticleCateModel $article_cate
     * @return array|bool
     */
    public function cateLists(ArticleCateModel $article_cate)
    {
        return self::success($article_cate->select());
    }

    /**
     * @permission 内容管理-分类管理-新增/修改
     * @param Request $request
     * @param ArticleCateModel $cate
     * @return array
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function saveCate(Request $request, ArticleCateModel $cate)
    {
        $data = $request->only(['id', 'name']);
        if (false === $cate->validate($data)) {
            return self::error($cate->getError());
        }
        $id = $data['id'];
        unset($data['id']);
        if (!empty($id)) {
            if (false === $info = $cate->where(['id' => $id])->find()) {
                return self::error($cate->getError());
            }
            if (empty($info)) {
                return self::error('未查找到数据');
            }
            if (false === $num = $cate->where(['id' => $id])->save($data)) {
                return self::error('修改数据失败');
            }
            if ($num === 0) {
                return self::error('您未修改数据');
            }
        } else {
            if (false === $id = $cate->add($data)) {
                self::error($cate->getError());
            }
        }
        return self::success('操作成功');
    }

    /**
     * @permission 内容管理-分类管理-删除
     * @param Request $request
     * @param ArticleCateModel $cate
     * @param ArticleModel $article
     * @return array
     * @throws InvalidArgumentException
     */
    public function delCate(Request $request, ArticleCateModel $cate, ArticleModel $article)
    {
        $id = $request->post('id');
        if (empty($id)) {
            return self::error('参数错误');
        }
        $info = $cate->where(['id' => $id])->find();
        if (empty($info)) {
            return self::error('不存在的分类');
        }
        if (false === $has = $article->field('id')->where(['cid' => $id])->find()) {
            return self::error($article->getError());
        }
        if (!empty($has)) {
            return self::error('当前分类还有文章');
        }
        if (false === $cate->where(['id' => $id])->delete()) {
            return self::error($cate->getError());
        }
        return self::success('删除成功');
    }
}