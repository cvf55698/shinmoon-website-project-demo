<?php
    
return [
    'debug_level'=>0, // 0~4
    'auth'=>true,
    'secure'=>env('MAIL_SECURE','tls'),
    'host'=> env('MAIL_SMTP_HOST',''),
    'port'=> (int) env('MAIL_PORT',587),
    'mail_address'=>env('MAIL_FROM_ADDRESS',''),
    "mail_app_password"=>env('MAIL_PASSWORD',''),
    "from_name"=>env('MAIL_FROM_NAME',''),
    "word_wrap"=>50,
];

?>