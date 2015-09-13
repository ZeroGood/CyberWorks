<?php
require 'vendor/autoload.php';
error_reporting(0);
error_reporting(E_ALL); // Turn on for error messages

if (!isset($_ENV['PHPUNIT'])) {
    session_name('CyberWorks');
    session_set_cookie_params(1209600);
    session_start();
}

function autoloader($class) {
    include 'classes/' . $class . '.php';
}

spl_autoload_register('autoloader');
//function __autoload($class);
/*{
    $file = 'classes/' . $class . '.php';
    if (!file_exists($file)) {
        echo "Requested module $class is missing. Execution stopped.";
        exit();
    }
    require($file);
}*/

$settings = require 'config/settings.php';
$dao = new query();
$helper = new helper();
$login = new Login();

if (file_exists('views/debug')) {
    include("views/debug/init.php");
} else {
    $debug = false;
}

require_once("gfunctions.php");

if (isset($_GET['searchText'])) {
    $search = $_GET['searchText'];
}

include_once('config/english.php');
foreach ($settings['plugins'] as &$plugin) {
    if (file_exists("plugins/" . $plugin . "/lang/lang.php")) {
        include("plugins/" . $plugin . "/lang/lang.php");
    }
}

$db_connection = masterConnect();

ob_start();