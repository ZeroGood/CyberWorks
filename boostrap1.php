<?php
require __DIR__ . '/vendor/autoload.php';
error_reporting(0);
error_reporting(E_ALL); // Turn on for error messages

if (file_exists(__DIR__ . '/config/settings.php')) {
    $settings = include( __DIR__ . '/config/settings.php');
}

if (file_exists(__DIR__ . '/devstuff/envConfig.php')) {
    $env = include(__DIR__ . '/devstuff/envConfig.php');
    $host = $env['host'];
    $user = $env['user'];
    $password = $env['password'];
    $dbname = $env['name'];
} elseif (isset($settings)) {
    $host = $settings['db']['host'];
    $user = $settings['db']['user'];
    $password = $settings['db']['password'];
    $dbname = $settings['db']['name'];
} else {
    $host = 'localhost';
    $user = 'travis';
    $password = '';
    $dbname = 'test_db';
}

include __DIR__ . '/classes/database/query.php';
include __DIR__ . '/classes/database/setup.php';
include __DIR__ . '/classes/login.php';

$dao = new query();
$helper = new helper();
$login = new login();

if (isset($_GET['searchText'])) {
    $search = $_GET['searchText'];
}

include 'lang/en.php';