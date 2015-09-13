<?php
error_reporting(0);
error_reporting(E_ALL);

if (!isset($_ENV['PHPUNIT'])) {
    session_name('CyberWorks');
    session_set_cookie_params(1209600);
    session_start();
}

$settings = require '../../config/settings.php';
require '../../classes/query.php';
$dao = new query();

ob_start();