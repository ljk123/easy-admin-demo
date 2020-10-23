<?php


namespace app\exception;

use app\controller\admin\Common;
use easy\App;
use easy\Container;
use easy\exception\ClassNotFoundException;
use easy\exception\MethodNotFoundException;
use easy\exception\UserHandleInterface;
use Throwable;

class Handle implements UserHandleInterface
{

    public function report(Throwable $e): bool
    {
        /**@var App $app */
        $app = Container::getInstance()->get('app');

        $app->response->setHeader('Access-Control-Allow-Origin', "*");
        $app->response->setHeader('Access-Control-Allow-Headers', "*");
        $app->response->setHeader('Access-Control-Allow-Methods', "*");
        if ($e instanceof ClassNotFoundException || $e instanceof MethodNotFoundException) {
            $app->response->json(Common::error("不存在的api:" . $app->request->getPath()));
            return true;
        }
        return false;//false表示未处理 true表示已处理
    }
}