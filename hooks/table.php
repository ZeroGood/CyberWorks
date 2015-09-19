<?php
error_reporting(0);
error_reporting(E_ALL);

if (!isset($_ENV['PHPUNIT'])) {
    session_name('CyberWorks');
    session_set_cookie_params(1209600);
    session_start();
}

require '../classes/query.php';
require '../classes/helper.php';
$dao = new query();
$helper = new helper();

ob_start();

if ($_SESSION['permissions']['view']['player'] && isset($_GET['players'])) {
    $players['data'] = $dao->player();
    echo json_encode($players);
} elseif ($_SESSION['permissions']['view']['vehicles'] && isset($_GET['vehicles'])) {
    require '../config/carNames.php';

    $vehicles =$dao->vehicle();
    for ($i = 0; $i <= count($vehicles) - 1; $i++) {
        $vehicles[$i][2] = carName($car,$vehicles[$i][2]);
        $vehicles[$i][5] = $helper->yesNo($vehicles[$i][5]);
        $vehicles[$i][6] = $helper->yesNo($vehicles[$i][6]);
    }
    $vehicles['data'] = $vehicles;
    echo json_encode($vehicles);
} elseif ($_SESSION['permissions']['view']['logs'] && isset($_GET['logs'])) {
    $logs['data'] = $dao->logs();
    echo json_encode($logs);
} elseif ($_SESSION['permissions']['view']['houses'] && isset($_GET['houses'])) {
    $houses = $dao->houses();
    for ($i = 0; $i <= count($houses) - 1; $i++) {
        $houses[$i][3] = substr($houses[$i][3], 1, -1);
        $houses[$i][4] = $helper->yesNo($houses[$i][4]);
    }
    $houses['data'] = $houses;
    echo json_encode($houses);
} elseif ($_SESSION['permissions']['view']['gangs'] && isset($_GET['gangs'])) {
    $gangs = $dao->gangs();
    /*for ($i = 0; $i <= count($houses) - 1; $i++) {
        $houses[$i][3] = substr($houses[$i][3], 1, -1);
        $houses[$i][4] = $helper->yesNo($houses[$i][4]);
    }*/
    $gangs['data'] = $gangs;
    echo json_encode($gangs);
}