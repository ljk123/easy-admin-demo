<?php


namespace app\controller\admin;


use app\logic\Role as RoleLogic;
use app\logic\Token;
use app\model\User as UserModel;
use easy\Exception;
use easy\exception\InvalidArgumentException;
use easy\Request;
use easy\Validate;

class User extends Common
{
    /**
     * @nologin
     * @param Request $request
     * @param UserModel $user
     * @return array
     * @throws InvalidArgumentException
     */
    public function login(Request $request,UserModel $user)
    {
        if($request->isPost())
        {
            $post = $request->only([
                'username',
                'pwd',
            ]);
            if(empty($post['username']) || empty($post['pwd']))
            {
                return self::error('用户名或密码错误');
            }
            $info=$user
                ->append('group_auth')
                ->where([
                'username'=>$post['username'],
            ])->find();
            if(empty($info))
            {
                return self::error('用户名或密码错误');
            }
            if(!password_verify($post['pwd'],$info['pwd']))
            {
                return self::error('用户名或密码错误');
            }
            if(!$info['status'])
            {
                return self::error('账号已经禁用');
            }
            $data=[];
            $data['id']=$info['id'];
            $data['username']=$info['username'];

            return self::success(Token::create($data,$info['group_auth'])->getToken());

        }
        return self::error('不存在api');
    }
    public function info(Token $token){
        if(!$info=$token->getData())
        {
            return self::error('UNLOGIN',$token->getError());
        }
        return self::success($info);
    }

    /**
     * @param Request $request
     * @param UserModel $user
     * @return array
     * @permission 账号管理-账号管理
     */
    public function lists(Request $request,UserModel $user){
        $p = $request->get('p');
        $lists = $user->append('status_word,create_time_format,login_time_format,group_name')
            ->hidden('pwd')
            ->page(intval($p))
            ->select();
        return self::success($lists);
    }

    /**
     * @param Request $request
     * @param UserModel $user
     * @return array
     * @permission 账号管理-账号管理-新增/修改
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function save(Request $request,UserModel $user){
        $data = $request->only(['id','username','pwd','status','group_id']);
        $valid=new Validate();
        if(false===$valid->validate($data,[
                'username'  => 'required|min_len:3|max_len:20',
                'group_id'  => 'required',
            ])->isOk())
        {
            return self::error($valid->getErrors()[0]);
        }
        if(!empty($data['pwd']))
        {
            $data['pwd']=password_hash($data['pwd'],PASSWORD_DEFAULT);
        }
        else{
            unset($data['pwd']);
        }
        $id = $data['id'];
        unset($data['id']);
        if(!empty($id))
        {
            if($id==1)
            {
                return self::error('演示平台不能修改管理员');
            }
            if(false===$info = $user->where(['id'=>$id])->find())
            {
                return self::error($user->getError());
            }
            if(empty($info))
            {
                return self::error('未查找到数据');
            }
            if(false===$num=$user->where(['id'=>$id])->save($data))
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
            if(false===$id=$user->add($data))
            {
                self::error($user->getError());
            }
        }
        return self::success('操作成功');
    }

    /**
     * @param Request $request
     * @param UserModel $user
     * @param Token $token
     * @return array
     * @throws InvalidArgumentException
     * @permission 账号管理-账号管理-删除
     */
    public function del(Request $request,UserModel $user,Token $token){
        $uinfo=$token->getData();
        $id = $request->post('id');
        if(empty($id))
        {
            return self::error('参数错误');
        }
        if($id==1)
        {
            return self::error('演示平台不能修改管理员');
        }
        $info = $user->where(['id'=>$id])->find();
        if(empty($info))
        {
            return self::error('不存在的用户');
        }
        if($uinfo['id']===$id)
        {
            return self::error('不能删除自己');
        }
        if(false===$user->where(['id'=>$id])->delete())
        {
            return self::error($user->getError());
        }
        return self::success('删除成功');
    }
    /**
     * 根据权限获取菜单
     * @param Request $request
     * @return array
     */
    public function menu(Request $request,Token $token)
    {
        //哎 还没想好怎么写
        $all_menu=
            [
                [
                    'name'=>'概览',
                    'link'=>'/dashboard',
                    'icon'=>'el-icon-odometer',
                ],
                [
                    'name'=>'账号管理',
                    'link'=>'/user',
                    'icon'=>'el-icon-user',
                    'sub'=>[
                        [
                            'name'=>'分组管理',
                            'link'=>'/users/group',
                        ],
                        [
                            'name'=>'账号管理',
                            'link'=>'/users/user',
                        ],
                    ]
                ]
            ];
        $role = new RoleLogic(__DIR__,__NAMESPACE__);

        return self::success($all_menu);
    }
}