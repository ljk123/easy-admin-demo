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
        $copy=$all_roles=$role->allRoles();
        usort($all_roles,function ($a,$b){
            if(count($a)==count($b)) return 0;
            return(count($a)>count($b))?-1:1;
        });
        $roles=[];
        foreach ($all_roles as $role)
        {
            $roles[array_search($role,$copy)]=$role;
        }
        return self::success($roles);
    }

}