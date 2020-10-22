<?php


namespace app\logic;


class Role
{
    protected $path;
    protected $namespace;
    private $error;

    public function __construct($path, $namespace)
    {
        $this->path = $path;
        $this->namespace = $namespace;
    }

    private $roles = [];

    public function allRoles()
    {
        if (!empty($this->roles)) {
            return $this->roles;
        }
        $roles = [];
        try {
            //获取当前目录所有控制器 只查询一级目录
            $files = scandir($this->path);
            foreach ($files as $file) {
                if (!is_dir($file) && '.php' === substr($file, -4)) {
                    $controller = $this->namespace . '\\' . substr($file, 0, -4);
                    $refl = new \ReflectionClass($controller);
                    $methods = $refl->getMethods();
                    foreach ($methods as $method) {
                        //只判断外部可以访问的方法
                        if ($method->isStatic() || $method->isConstructor() || !$method->isPublic()) {
                            continue;
                        }
                        if ($doc_coment = $method->getDocComment()) {
                            $doc = explode(PHP_EOL, $doc_coment);
                            foreach ($doc as $line) {
                                if (false !== $pos = strpos($line, '@permission')) {
                                    $role = explode('-', trim(substr($line, $pos + strlen('@permission'))));
                                    $roles[md5($controller . '::' . $method->name)] = $role;
                                }
                            }
                        }
                    }
                }
            }
        } catch (\ReflectionException $e) {
            $this->error = $e->getMessage();
            return false;
        }
        $this->roles = $roles;
        return $roles;
    }
}