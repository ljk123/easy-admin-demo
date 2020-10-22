<?php


namespace app\controller\admin;

use app\model\Group as GroupModel;
use app\model\User as UserModel;
use easy\Exception;
use easy\exception\InvalidArgumentException;
use easy\Request;

class Group extends Common
{
    /**
     *
     * @permission 账号管理-分组管理
     * @param GroupModel $group
     * @return array
     */
    public function index(GroupModel $group){
        return self::success($group->append('create_time_format')->select());
    }

    /**
     * @permission 账号管理-分组管理-新增/修改
     * @param Request $request
     * @param GroupModel $group
     * @return array
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function save(Request $request,GroupModel $group)
    {
        $data = $request->only(['id','auth','desc','name']);
        //todo 框架增加验证器 控制器验证 或者嵌入模型
        if(0)
        {
            return self::error('验证失败 xxxx');
        }
        $data['auth']=implode(',',$data['auth']);
        $id = $data['id'];
        unset($data['id']);
        if(!empty($id))
        {
            if(false===$info = $group->where(['id'=>$id])->find())
            {
                return self::error($group->getError());
            }
            if(empty($info))
            {
                return self::error('未查找到数据');
            }
            if($id==1)
            {
                return self::error('演示平台不能修改管理员');
            }
            if(false===$num=$group->where(['id'=>$id])->save($data))
            {
                return self::error('修改数据失败');
            }
            if($num===0)
            {
                return self::error('您未修改数据');
            }
        }
        else{
            $data['create_time']=time();
            if(false===$id=$group->add($data))
            {
                self::error($group->getError());
            }
        }
        return self::success('操作成功');

    }

    /**
     * @permission账号管理-分组管理-删除
     * @param Request $request
     * @param GroupModel $group
     * @return array
     * @throws InvalidArgumentException
     */
    public function del(Request $request,GroupModel $group,UserModel $user){
        $id = $request->post('id');
        if(empty($id))
        {
            return self::error('参数错误');
        }
        $info = $group->where(['id'=>$id])->find();
        if(empty($info))
        {
            return self::error('不存在的分组');
        }
        if($id==1)
        {
            return self::error('演示平台不能修改管理员');
        }
        if(false===$users=$user->where(['group_id'=>$id])->find())
        {
            return self::error($group->getError());
        }
        if(!empty($users))
        {
            return self::error('该分组下存在用户，不能删除');
        }
        if(false===$group->where(['id'=>$id])->delete())
        {
            return self::error($group->getError());
        }
        return self::success('删除成功');
    }

}