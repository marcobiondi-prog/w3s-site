<?php

return [
    'enabled' => true,
    'host' => 'smtp.gmail.com',
    'username' => getenv('GMAIL_USERNAME') ?: 'mabiondi82@gmail.com',
    'password' => getenv('hbzt uddw txhp aqft') ?: '',
    'encryption' => 'tls',
    'port' => 587,
    'from_email' => getenv('GMAIL_USERNAME') ?: 'mabiondi82@gmail.com',
    'from_name' => 'W3S',
];
