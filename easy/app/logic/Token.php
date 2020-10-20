<?php


namespace app\logic;


use easy\Exception;
use easy\Jwt;
use easy\jwt\JwtObj;
use easy\Request;

class Token
{
    private $data;
    /**@var string $error*/
    private $error;
    /**@var string $token*/
    private $token;
    /**@var Jwt $handle*/
    private $handle;

    /**
     * @param $user_data
     * @param array $auth
     * @param callable $callable
     * @return Token
     */
    public static function create($user_data,$auth=[],$callable=null){
        /**@var JwtObj $jwt */
        $jwt=Jwt::getInstance()->publish();
        $data['data']=$user_data;
        $data['auth']=$auth;
        $jwt->setData($data);
        //其他设置
        if(is_callable($callable))
        {
            call_user_func($callable,$jwt);
        }
        return new self(null,$jwt->getToken());
    }
    public static function auths($item){
        return md5($item);
    }
    public function __construct(Request $request = null ,string $token=null)
    {
        if(empty($token))
        {
            if(empty($request))
            {
                $this->error='token丢失';
            }
            else{
                $token = (string)$request->header('Authorization');
            }
        }
        $this->handle=Jwt::getInstance();
        try {
            $jwtObj = $this->handle->decode($token);
            $status=$jwtObj->getStatus();
            if(JwtObj::STATUS_SIGNATURE_ERROR ===  $status){
                $this->error='无效的token';
                $token=null;
            }
            elseif(JwtObj::STATUS_EXPIRED ===  $status){
                $this->error='token已过期';
                $token=null;
            }
            else{
                $this->data=$jwtObj->getData();
            }
        } catch (Exception $e) {
            $this->error='无效的token';
            $token=null;
        }
        $this->token=$token;
    }

    /**
     * @return Jwt
     */
    public function getHandle()
    {
        return $this->handle;
    }
    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
    public function getData(){
        return $this->data['data'];
    }

    /**
     * @param $method
     * @return bool
     */
    public function auth($method){
        if(empty($this->data['auth']))
        {
            return false;
        }
        return in_array(self::auths($method),$this->data['auth']);
    }

    /**
     * @return string
     */
    public function getError(){
        return $this->error;
    }

}