<?php


namespace app\controller\admin;

use app\logic\Role as RoleLogic;

class Role extends Common
{
    /**
     * @return array
     * @unlogin
     */
    public function index(){
        $role = new RoleLogic(__DIR__,__NAMESPACE__);
        return self::success($role->allRoles());
    }

}