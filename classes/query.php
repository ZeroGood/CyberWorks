<?php

/**
 * Class query
 */
class query
{
    /*public function __construct() {
        require_once '../gfunctions.php';
        $settings = require_once '../config/settings.php';
        $this->host = decrypt($settings['db']['host']);
        $this->user = decrypt($settings['db']['user']);
        $this->password = decrypt($settings['db']['pass']);
        $this->dbname = decrypt($settings['db']['name']);
    }*/

    public function player($uid = null)
    {
        $dbh = new PDO("mysql:dbname=$this->dbname;host=$this->host", $this->user, $this->password);
        if (isset($_GET['uid'])) {
            $sql = $dbh->prepare("SELECT * FROM players WHERE uid = :uid;");
            $sql->bindParam(':uid', $uid, PDO::PARAM_INT);
            $sql->execute();
            $json['result'] = $sql->fetch(PDO::FETCH_ASSOC);
        } else {
            $sql = $dbh->prepare("SELECT * FROM players");
            $sql->execute();
            $json['result'] = $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        $dbh = null;
    }

    public function playerEdit($uid, $type, $level = null)
    {
        $dbh = new PDO("mysql:dbname=$this->dbname;host=$this->host", $this->user, $this->password);
        switch ($type) {
            case "civ_inv":
                $sql = $dbh->prepare("UPDATE players SET civ_gear = :civ_gear WHERE uid = :uid;");
                $sql->bindParam(':uid', $uid, PDO::PARAM_INT);
                $sql->bindParam(':civ_gear', $_POST['civ_inv_value'], PDO::PARAM_STR);
                $sql->execute();
                break;
            case "copInv":
                $sql = $dbh->prepare("UPDATE players SET cop_gear = :cop_gear WHERE uid = :uid;");
                $sql->bindParam(':uid', $uid, PDO::PARAM_INT);
                $sql->bindParam(':cop_gear', $_POST['cop_inv_value'], PDO::PARAM_STR);
                $sql->execute();
                break;
            case "medInv":
                $sql = $dbh->prepare("UPDATE players SET med_gear = :med_gear WHERE uid = :uid;");
                $sql->bindParam(':uid', $uid, PDO::PARAM_INT);
                $sql->bindParam(':med_gear', $_POST['med_inv_value'], PDO::PARAM_STR);
                $sql->execute();
                break;
            case "edit":
                if (isset($level)) {
                    switch ($level) {
                        case "civ_inv":
                            $sql = $dbh->prepare("UPDATE players SET civ_gear = :civ_gear WHERE uid = :uid;");
                            $sql->bindParam(':uid', $uid, PDO::PARAM_INT);
                            $sql->bindParam(':civ_gear', $_POST['civ_inv_value'], PDO::PARAM_STR);
                            $sql->execute();
                            break;
                    }
                }
                break;
        }

        $dbh = null;
    }

    public function vehicle($vehID = null)
    {
        $dbh = new PDO("mysql:dbname=$this->dbname;host=$this->host", $this->user, $this->password);
        if (isset($vehID)) {
            $sql = $dbh->prepare("SELECT * FROM vehicles WHERE id = :vehID");
            $sql->bindParam(':vehID', $vehID, PDO::PARAM_INT);
            $sql->execute();
            $json['result'] = $sql->fetch(PDO::FETCH_ASSOC);
        } else {
            $sql = $dbh->prepare("SELECT * FROM vehicles");
            $sql->execute();
            $json['result'] = $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        $dbh = null;
    }

    public function gangs($gID = null)
    {
        try {
            $dbh = new PDO("mysql:dbname=$this->dbname;host=$this->host", $this->user, $this->password);
            if (isset($gID)) {
                $sql = $dbh->prepare("SELECT * FROM gangs WHERE id = :gID");
                $sql->bindParam(':gID', $gID, PDO::PARAM_INT);
                $sql->execute();
                return $sql->fetch(PDO::FETCH_ASSOC);
            } else {
                $sql = $dbh->prepare("SELECT * FROM gangs");
                $sql->execute();
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function houses($hID = null)
    {
        try {
            $dbh = new PDO("mysql:dbname=$this->dbname;host=$this->host", $this->user, $this->password);
            if (isset($hID)) {
                $sql = $dbh->prepare("SELECT * FROM houses WHERE id = :hID");
                $sql->bindParam(':hID', $hID, PDO::PARAM_INT);
                $sql->execute();
                return $sql->fetch(PDO::FETCH_ASSOC);
            } else {
                $sql = $dbh->prepare("SELECT * FROM houses");
                $sql->execute();
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function servers($sID = null)
    {
        try {
            $dbh = new PDO("mysql:dbname=$this->dbname;host=$this->host", $this->user, $this->password);
            if (isset($hID)) {
                $sql = $dbh->prepare("SELECT * FROM servers WHERE sid = :sID");
                $sql->bindParam(':sID', $sID, PDO::PARAM_INT);
                $sql->execute();
                return $sql->fetch(PDO::FETCH_ASSOC);
            } else {
                $sql = $dbh->prepare("SELECT * FROM servers");
                $sql->execute();
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function DB($dbID = null)
    {
        try {
            $dbh = new PDO("mysql:dbname=$this->dbname;host=$this->host", $this->user, $this->password);
            if (isset($dbID)) {
                $sql = $dbh->prepare("SELECT * FROM db WHERE dbid = :dbID");
                $sql->bindParam(':dbID', $dbID, PDO::PARAM_INT);
                $sql->execute();
                return $sql->fetch(PDO::FETCH_ASSOC);
            } else {
                $sql = $dbh->prepare("SELECT * FROM db");
                $sql->execute();
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function editDB($dbID, $host, $user, $pass, $name)
    {
        try {
            $dbh = new PDO("mysql:dbname=$this->dbname;host=$this->host", $this->user, $this->password);

            $sql = $dbh->prepare("UPDATE db SET sql_host = :host, sql_user = :user, sql_pass = :pass, sql_name = :name WHERE dbid = :dbID;");
            $sql->bindValue(':dbID', $dbID, PDO::PARAM_INT);
            $sql->bindValue(':host', $host, PDO::PARAM_STR);
            $sql->bindValue(':user', $user, PDO::PARAM_STR);
            $sql->bindValue(':pass', $pass, PDO::PARAM_STR);
            $sql->bindValue(':name', $name, PDO::PARAM_STR);
            $sql->execute();
        } catch (Exception $e) {
            return $e;
        }
        return true;
    }

    public function newDB($dbType, $host, $user, $pass, $name)
    {
        try {
            $dbh = new PDO("mysql:dbname=$this->dbname;host=$this->host", $this->user, $this->password);

            $sql = $dbh->prepare("INSERT INTO db (type, sql_host, sql_user, sql_pass, sql_name) VALUES (:dbType, :host, :user, :pass, :name)");
            $sql->bindValue(':dbType', $dbType, PDO::PARAM_STR);
            $sql->bindValue(':host', $host, PDO::PARAM_STR);
            $sql->bindValue(':user', $user, PDO::PARAM_STR);
            $sql->bindValue(':pass', $pass, PDO::PARAM_STR);
            $sql->bindValue(':name', $name, PDO::PARAM_STR);
            $sql->execute();
        } catch (Exception $e) {
            return $e;
        }
        return $dbh->lastInsertId();
    }

    public function deleteDB($dbID)
    {
        try {
            $dbh = new PDO("mysql:dbname=$this->dbname;host=$this->host", $this->user, $this->password);

            $sql = $dbh->prepare("DELETE FROM db WHERE dbid = :dbID");
            $sql->bindValue(':dbID', $dbID, PDO::PARAM_INT);
            $sql->execute();
        } catch (Exception $e) {
            return $e;
        }
        return true;
    }

    /*public function editUser($uid, $type)
    {
        try {
            $dbh = new PDO("mysql:dbname=$this->dbname;host=$this->host", $this->user, $this->password);

            $sql = $dbh->prepare("UPDATE users SET backup = :backupCode WHERE user_id = :uid;");
            $sql->bindValue(':backupCode', $backupCode, PDO::PARAM_STR);
            $sql->bindValue(':uid', $uid, PDO::PARAM_INT);
            $sql->execute();
        } catch (Exception $e) {
            return $e;
        }
        return true;
    }*/

    public function twoFactorNew($uid, $token = null, $twoFactor = null, $backup = null)
    {
        try {
            $dbh = new PDO("mysql:dbname=$this->dbname;host=$this->host", $this->user, $this->password);
            if (isset($token) && isset($twoFactor)) {
                $sql = $dbh->prepare("UPDATE users SET token = :token, twoFactor = :twoFactor WHERE user_id = :uid;");
                $sql->bindValue(':token', $token, PDO::PARAM_STR);
                $sql->bindValue(':token', $token, PDO::PARAM_STR);
                $sql->bindValue(':uid', $uid, PDO::PARAM_INT);
                $sql->execute();
            } elseif (isset($backup)) {
                $sql = $dbh->prepare("UPDATE users SET backup = :backupCode WHERE user_id = :uid;");
                $sql->bindValue(':backupCode', $backup, PDO::PARAM_STR);
                $sql->bindValue(':uid', $uid, PDO::PARAM_INT);
                $sql->execute();
            }
        } catch (Exception $e) {
            return $e;
        }
        return true;
    }

    public function twoFactorRevoke($uid, $type = null)
    {
        try {
            $dbh = new PDO("mysql:dbname=$this->dbname;host=$this->host", $this->user, $this->password);
            if (isset($type)) {
                $sql = $dbh->prepare("UPDATE users SET backup = NULL WHERE user_id = :uid;");
                $sql->bindValue(':uid', $uid, PDO::PARAM_INT);
                $sql->execute();
            } else {
                $sql = $dbh->prepare("UPDATE users SET backup = NULL, twoFactor = NULL, token = NULL WHERE user_id = :uid;");
                $sql->bindValue(':uid', $uid, PDO::PARAM_INT);
                $sql->execute();
            }
        } catch (Exception $e) {
            return $e;
        }
        return true;
    }

    public function user($uid = null)
    {
        try {
            $dbh = new PDO("mysql:dbname=$this->dbname;host=$this->host", $this->user, $this->password);
            if (isset($uid)) {
                $sql = $dbh->prepare("SELECT * FROM users WHERE user_id = :uid;");
                $sql->bindValue(':uid', $uid, PDO::PARAM_INT);
                $sql->execute();
                return $sql->fetch(PDO::FETCH_ASSOC);
            } else {
                $sql = $dbh->prepare("SELECT * FROM users");
                $sql->bindValue(':uid', $uid, PDO::PARAM_INT);
                $sql->execute();
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function logAdd($user, $action, $level)
    {
        try {
            $dbh = new PDO("mysql:dbname=$this->dbname;host=$this->host", $this->user, $this->password);

            $sql = $dbh->prepare("INSERT INTO logs (user, action, level) VALUES (:user, :action, :level);");
            $sql->bindValue(':user', $user, PDO::PARAM_INT);
            $sql->bindValue(':action', $action, PDO::PARAM_STR);
            $sql->bindValue(':level', $level, PDO::PARAM_INT);
            $sql->execute();
        } catch (Exception $e) {
            return $e;
        }

        return $dbh->lastInsertId();
    }

    public function logs()
    {
        try {
            $dbh = new PDO("mysql:dbname=$this->dbname;host=$this->host", $this->user, $this->password);

            $sql = $dbh->prepare("SELECT * FROM logs");
            $sql->execute();
        } catch (Exception $e) {
            return $e;
        }

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}