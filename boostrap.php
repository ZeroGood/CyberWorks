<?php
session_name('CyberWorks');
session_set_cookie_params(1209600);
session_start();

function autoloader($class)
{
    include __DIR__ . '/classes/' . $class . '.php';
}

spl_autoload_register('autoloader');

if (file_exists('views/debug')) {
    include("views/debug/init.php");
} else {
    $debug = false;
}

require_once 'gfunctions.php';

$pluginLang = 'en';
if (isset($_COOKIE['lang'])) $pluginLang = $_COOKIE['lang'];
if (isset($_SESSION['lang'])) $pluginLang = $_SESSION['lang'];
if (!$settings['allowLang']) $pluginLang = $settings['language'];

if ($pluginLang == 'de') {
    include 'lang/de.php';
}

ob_start();