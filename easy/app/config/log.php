<?php
return [
    'level'=>//要记录日志的级别
        \easy\Log::DEBUG |
        \easy\Log::INFO |
        \easy\Log::NOTICE |
        \easy\Log::WARNING |
        \easy\Log::ERROR,
    'onlog'=>null,//传入callback 记录日志时出发的回调 用于用户管理日志比如推送到日志中心
];