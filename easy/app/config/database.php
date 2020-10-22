<?php
return [
    // 服务器地址
    'host' => '127.0.0.1',//逗号分割 多个表示主从第一个是主库其他是从库
    // 数据库名
    'database' => 'easy_mt234_cn',//主从的话逗号必须跟host对应 否者表示相同密码
    // 用户名
    'username' => 'easy_mt234_cn',//同上
    // 密码
    'password' => 'hwrwNXcKsK2s58eS',//同上
    // 端口
    'port' => 3306,//同上
    // 数据库连接参数
    'options' => [],
    // 数据库编码默认采用utf8
    'charset' => 'utf8mb4',
    // 数据库表前缀
    'prefix' => 'easy_',
];