<?php
session_start();

require_once 'classes/Database.php';
require_once 'classes/Config.php';
require_once 'classes/Validate.php';
require_once 'classes/Input.php';
require_once 'classes/Session.php';
require_once 'classes/Token.php';
require_once 'classes/User.php';
require_once 'classes/Redirect.php';

$GLOBALS['config'] = [
    'mysql' => [
        'db_host' => 'mysql_oop1',
        'db_name' => 'posts',
        'db_login' => 'root',
        'db_password' => 'root'
    ],
    'session' => [
        'token_name' => 'token',
        'user_session' => 'user'
    ]
];
