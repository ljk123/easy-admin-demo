<?php
return[
    'app_debug'=>true,
    'config_files'=>[//需要自动加载的配置文件
        //file=>name
    ],
    'exception_handle'=>\app\exception\Handle::class,// 自定义异常处理文件 如\app\exception\Handle::class,
];