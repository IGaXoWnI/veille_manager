<?php
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'veille_db');
define('DB_USER', 'postgres');
define('DB_PASS', '070911');

if (!defined('BASE_URL')) define('BASE_URL', '/veille');
if (!defined('APP_PATH')) define('APP_PATH', dirname(__DIR__));
define('PUBLIC_PATH', APP_PATH . '/public');

error_reporting(E_ALL);
ini_set('display_errors', 1);