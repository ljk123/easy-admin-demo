<?php
return[
    'server'=>[
        'reactor_num'   => 1,     // 设置启动的 Reactor 线程数。【默认值：CPU 核数】
        'worker_num'    => 2,     // 设置启动的 Worker 进程数。【默认值：CPU 核数】
        'max_request'   => 0,      //设置 worker 进程的最大任务数。【默认值：0 即不会退出进程】
        'dispatch_mode' => 2,   //数据包分发策略。【默认值：2】 //https://wiki.swoole.com/#/server/setting?id=dispatch_mode
//        'daemonize'     => 1,  //守护进程化【默认值：0】
//        'log_level'     =>   SWOOLE_LOG_WARNING

    ],
    'http'=>[
        'host'=>'127.0.0.1',
        'port'=>9599,
    ],
    'mysql'=>[
        'pool'=>true,//是否起用连接池
        'name'=>'mysql',//区别连接池的标示
        'min_size'=>20,//每个进程启动链接数量
        'max_size'=>100,//最大连接数
        'free_time'=>600,//空闲时间
        'timeout'=>1,//获取连接池超时时间
    ],
    'redis'=>[
        'pool'=>true,//是否起用连接池
        'name'=>'redis',//区别连接池的标示
        'min_size'=>20,//每个进程启动链接数量
        'max_size'=>100,//最大连接数
        'free_time'=>600,//空闲时间
        'timeout'=>1,//获取连接池超时时间
    ],
];