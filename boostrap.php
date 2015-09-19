<?php
require __DIR__ . '/vendor/autoload.php';
error_reporting(0);
error_reporting(E_ALL); // Turn on for error messages

if (!isset($_ENV['PHPUNIT'])) {
    session_name('CyberWorks');
    session_set_cookie_params(1209600);
    session_start();
}

function autoloader($class) {
    include __DIR__ . '/classes/' . $class . '.php';
}
spl_autoload_register('autoloader');

$settings = require 'config/settings.php';
$dao = new query();
$helper = new helper();
$login = new login();

if (file_exists('views/debug')) {
    include("views/debug/init.php");
} else {
    $debug = false;
}

if (isset($_GET['searchText'])) {
    $search = $_GET['searchText'];
}

require_once 'gfunctions.php';

include 'lang/en.php';
$pluginLang = 'en';
if (isset($_COOKIE['lang'])) $pluginLang = $_COOKIE['lang'];
if (isset($_SESSION['lang'])) $pluginLang = $_SESSION['lang'];
if (!$settings['allowLang']) $pluginLang = $settings['language'];

if($pluginLang == 'de') {
    include 'lang/de.php';
}

ob_start();