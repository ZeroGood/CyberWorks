<?php

class actions
{
    public function changePass() {
        if (isset($_POST['user_password'])) {
            if (formtoken::validateToken($_POST)) {
                $sql = "SELECT `user_password_hash` FROM `users` WHERE `user_id` = '" . $_SESSION['user_id'] . "';";
                $result = $db_connection->query($sql)->fetch_object();
                if ($_POST['user_password'] == $_POST['user_password_again'] && password_verify($_POST['current_password'],$result->user_password_hash)) {
                    $sql = "UPDATE `users` SET `user_password_hash`= '" . password_hash($_POST['user_password'], PASSWORD_DEFAULT) . "' WHERE `user_id` = '" . $_SESSION['user_id'] . "';";
                    $result_of_query = $db_connection->query($sql);
                    message($lang['passChanged']);
                }
            } else {
                message($lang['expired']);
            }
        }
    }
}