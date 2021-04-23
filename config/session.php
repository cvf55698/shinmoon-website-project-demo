<?php

return [
    'session_name'=>env('SESSION_COOKIE_NAME','PHPSESSIONID'),
    'session_lifetime'=>env('SESSION_LIFETIME','1440'),
    'httponly'=>true,
    'secure'=>false,
    'use_redis'=>(bool) env('SESSION_USE_REDIS',false),
    'redis_session_save_path'=>env('SESSION_REDIS_PATH','tcp://127.0.0.1:6379?auth='),
];

?>