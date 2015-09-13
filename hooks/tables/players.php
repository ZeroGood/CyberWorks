<?php
include '../boostrap.ajax.php';
$players = $dao->player();
/*for ($i = 0; $i <= count($players) - 1; $i++) {
    $players[$i][7] = '<a href="'.$settings['url'].'editPlayer/'.$players[$i][7].'">Edit</a>';
}*/
$players['data'] = $players;
echo json_encode($players);