<?php


namespace app\controller\admin;


use app\logic\Token;
use easy\App;
use easy\exception\ResponseException;
use ReflectionException;

class Common
{

    protected $app;

    /**
     * Common constructor.
     * @param App $app
     * @throws ResponseException
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        //允许跨域
        $app->response->setHeader('Access-Control-Allow-Origin', "*");
        $app->response->setHeader('Access-Control-Allow-Headers', "*");
        $app->response->setHeader('Access-Control-Allow-Methods', "*");

        if ($app->request->server('REQUEST_METHOD') === 'OPTIONS') {
            throw new ResponseException();
        }

        if (method_exists($this, '__initialize')) {
            call_user_func([$this, '__initialize']);
        }
    }

    /**
     *
     * 在这里控制权限
     * <p>
     * 注解说明 permission 表示token的操作权限
     *         nologin 表示不需要登陆可访问
     *         无注解表示需要登陆 但没有权限要求
     * </p>
     * @throws ResponseException
     * @throws ReflectionException
     */
    public function before()
    {
        //通过反射拿到当前doc
        $refl = new \ReflectionClass(get_called_class());
        $doc_coment = $refl->getMethod($this->app->dispatch->action)->getDocComment();
        $login = true;
        $permission = false;
        $doc = explode(PHP_EOL, $doc_coment);
        foreach ($doc as $line) {
            if (false !== strpos($line, '@nologin')) {
                $login = false;
            }
            if (false !== strpos($line, '@permission')) {
                $permission = true;
            }
        }
        if ($login || $permission) {
            $token = new Token($this->app->request);
            if (!$token->getData()) {
                throw new ResponseException(self::error('UNLOGIN', $token->getError()));
            }
            if ($permission && false === $token->auth(get_called_class() . '::' . $this->app->dispatch->action)) {
                throw new ResponseException(self::error('NOAUTH', $token->getError()));
            }
        }
    }

    /**
     * 定义成功格式
     * @param $data
     * @return array
     */
    protected static function success($data)
    {
        return [
            'data' => $data,//数据
            'msg' => 'OK',//信息
            'status' => 1,//业务状态

        ];
    }

    /**
     * @param $msg
     * @param $data
     * @return array
     */
    protected static function error($msg, $data = null)
    {
        return [
            'data' => $data,//数据
            'msg' => $msg,//错误信息
            'status' => 0,//业务状态
        ];
    }
}