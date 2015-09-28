<?php

/**
 * Class query
 */
class query
{
    public $dbname = 'cyberby1_testdb';
    public $host = '191.101.48.14';
    public $user = 'cyberby1_testdb';
    public $password = 'q3S5[6nQGt}1';

    public function __construct($name, $host, $user, $password, $dbid = null)
    {
        $this->dbh = new PDO("mysql:dbname=$name;host=$host", $user, $password);
        if (isset($dbid)) {
            $server = $this->servers($dbid);

            $this->sh = new PDO("mysql:dbname=$name;host=$host", $user, $password);
        }
    }

    public function login($user)
    {
        try {
            $sql = $this->dbh->prepare("SELECT user_name, user_email, user_level, user_profile, permissions,
            user_password_hash, user_id, playerid, twoFactor, token FROM users WHERE user_name = :user OR user_email = :user OR playerid = :user;");
            $sql->bindParam(':user', $user, PDO::PARAM_STR);
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function steamPlayer($pid)
    {
        $sql = $this->dbh->prepare("SELECT uid FROM players WHERE playerid = :pid;");
        $sql->bindParam(':pid', $pid, PDO::PARAM_STR);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function player($uid = null)
    {
        try {
            if (isset($uid)) {
                $sql = $this->dbh->prepare("SELECT * FROM players WHERE uid = :uid;");
                $sql->bindParam(':uid', $uid, PDO::PARAM_INT);
                $sql->execute();
                return $sql->fetch(PDO::FETCH_ASSOC);
            } else {
                $sql = $this->dbh->prepare("SELECT uid, name, playerid, cash, bankacc, coplevel, mediclevel, adminlevel FROM players");
                $sql->execute();
                return $sql->fetchAll(PDO::FETCH_NUM);
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function playerEdit($uid, $type, $level = null)
    {
        switch ($type) {
            case "civ_inv":
                $sql = $this->dbh->prepare("UPDATE players SET civ_gear = :civ_gear WHERE uid = :uid;");
                $sql->bindParam(':uid', $uid, PDO::PARAM_INT);
                $sql->bindParam(':civ_gear', $_POST['civ_inv_value'], PDO::PARAM_STR);
                $sql->execute();
                break;
            case "copInv":
                $sql = $this->dbh->prepare("UPDATE players SET cop_gear = :cop_gear WHERE uid = :uid;");
                $sql->bindParam(':uid', $uid, PDO::PARAM_INT);
                $sql->bindParam(':cop_gear', $_POST['cop_inv_value'], PDO::PARAM_STR);
                $sql->execute();
                break;
            case "medInv":
                $sql = $this->dbh->prepare("UPDATE players SET med_gear = :med_gear WHERE uid = :uid;");
                $sql->bindParam(':uid', $uid, PDO::PARAM_INT);
                $sql->bindParam(':med_gear', $_POST['med_inv_value'], PDO::PARAM_STR);
                $sql->execute();
                break;
            case "edit":
                if (isset($level)) {
                    switch ($level) {
                        case "civ_inv":
                            $sql = $this->dbh->prepare("UPDATE players SET civ_gear = :civ_gear WHERE uid = :uid;");
                            $sql->bindParam(':uid', $uid, PDO::PARAM_INT);
                            $sql->bindParam(':civ_gear', $_POST['civ_inv_value'], PDO::PARAM_STR);
                            $sql->execute();
                            break;
                    }
                }
                break;
        }
    }

    public function vehicle($vehID = null)
    {
        try {
            if (isset($vehID)) {
                $sql = $this->dbh->prepare("SELECT * FROM vehicles WHERE id = :vehID;");
                $sql->bindParam(':vehID', $vehID, PDO::PARAM_INT);
                $sql->execute();
                return $sql->fetch(PDO::FETCH_ASSOC);
            } else {
                $sql = $this->dbh->prepare("SELECT id, name, classname, type, plate, alive, active FROM vehicles INNER JOIN players ON vehicles.pid=players.playerid;");
                $sql->execute();
                return $sql->fetchAll(PDO::FETCH_NUM);
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function gangs($gID = null)
    {
        try {
            if (isset($gID)) {
                $sql = $this->dbh->prepare("SELECT * FROM gangs WHERE id = :gID;");
                $sql->bindParam(':gID', $gID, PDO::PARAM_INT);
                $sql->execute();
                return $sql->fetch(PDO::FETCH_ASSOC);
            } else {
                $sql = $this->dbh->prepare("SELECT id, owner, name, players.name,bank, active FROM gangs INNER JOIN players ON gangs.owner=players.playerid;");
                $sql->execute();
                return $sql->fetchAll(PDO::FETCH_NUM);
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function editHouse($hID, $owner, $pos, $inv, $containers, $owned)
    {
        try {
            $sql = $this->dbh->prepare("UPDATE houses SET pid = :owner, pos = :position, inventory = :inv, containers = :containers, owned = :owned WHERE id = :hID;");
            $sql->bindValue(':hID', $hID, PDO::PARAM_INT);
            $sql->bindValue(':owner', $owner, PDO::PARAM_STR);
            $sql->bindValue(':position', $pos, PDO::PARAM_STR);
            $sql->bindValue(':inv', $inv, PDO::PARAM_STR);
            $sql->bindValue(':containers', $containers, PDO::PARAM_STR);
            $sql->bindValue(':owned', $owned, PDO::PARAM_INT);
            $sql->execute();
        } catch (Exception $e) {
            return $e;
        }
        return true;
    }

    public function houses($hID = null)
    {
        try {
            if (isset($hID)) {
                $sql = $this->dbh->prepare("SELECT * FROM houses WHERE id = :hID;");
                $sql->bindParam(':hID', $hID, PDO::PARAM_INT);
                $sql->execute();
                return $sql->fetch(PDO::FETCH_ASSOC);
            } else {
                $sql = $this->dbh->prepare("SELECT id, name, pid, pos, owned FROM houses INNER JOIN players ON houses.pid=players.playerid;");
                $sql->execute();
                return $sql->fetchAll(PDO::FETCH_NUM);
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function deleteHouse($hID)
    {
        try {
            $sql = $this->dbh->prepare("DELETE FROM houses WHERE id = :hID");
            $sql->bindParam(':hID', $hID, PDO::PARAM_INT);
            $sql->execute();
            return true;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function servers($sID = null, $small = null)
    {
        try {
            if (isset($sID)) {
                $sql = $this->dbh->prepare("SELECT * FROM servers WHERE sid = :sID");
                $sql->bindParam(':sID', $sID, PDO::PARAM_INT);
                $sql->execute();
                return $sql->fetch(PDO::FETCH_ASSOC);
            } elseif (isset($small)) {
                $sql = $this->dbh->prepare("SELECT sid,name,dbid,type FROM servers");
                $sql->execute();
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $sql = $this->dbh->prepare("SELECT * FROM servers");
                $sql->execute();
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function newServer($name, $DB, $type, $sqPort = null, $sqIP = null, $rconPass = null)
    {
        try {
            if (isset($sqPort) && isset($sqIP) && isset($rconPass)) {
                $sql = $this->dbh->prepare("INSERT INTO servers (name, dbid, type, use_sq, sq_port, sq_ip, rcon_pass) VALUES (:name, :db, :type, '1', :sqPort, :sqIP, :rconPass);");
                $sql->bindValue(':name', $name, PDO::PARAM_STR);
                $sql->bindValue(':db', $DB, PDO::PARAM_INT);
                $sql->bindValue(':type', $type, PDO::PARAM_STR);
                $sql->bindValue(':sqPort', $sqPort, PDO::PARAM_STR);
                $sql->bindValue(':sqIP', $sqIP, PDO::PARAM_STR);
                $sql->bindValue(':rconPass', $rconPass, PDO::PARAM_STR);
                $sql->execute();
            } else {
                $sql = $this->dbh->prepare("INSERT INTO servers (name, dbid, type, use_sq) VALUES (:name, :db, :type, '0');");
                $sql->bindValue(':name', $name, PDO::PARAM_STR);
                $sql->bindValue(':db', $DB, PDO::PARAM_INT);
                $sql->bindValue(':type', $type, PDO::PARAM_STR);
                $sql->execute();
            }
            return $this->dbh->lastInsertId();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function deleteServer($sID)
    {
        try {
            $sql = $this->dbh->prepare("DELETE FROM servers WHERE sid = :sID");
            $sql->bindValue(':sID', $sID, PDO::PARAM_INT);
            $sql->execute();
        } catch (Exception $e) {
            return $e;
        }
        return true;
    }

    public function DB($dbID = null)
    {
        try {
            if (isset($dbID)) {
                $sql = $this->dbh->prepare("SELECT * FROM db WHERE dbid = :dbID");
                $sql->bindParam(':dbID', $dbID, PDO::PARAM_INT);
                $sql->execute();
                return $sql->fetch(PDO::FETCH_ASSOC);
            } else {
                $sql = $this->dbh->prepare("SELECT * FROM db");
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
            $sql = $this->dbh->prepare("UPDATE db SET sql_host = :host, sql_user = :user, sql_pass = :pass, sql_name = :name WHERE dbid = :dbID;");
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
            $sql = $this->dbh->prepare("INSERT INTO db (type, sql_host, sql_user, sql_pass, sql_name) VALUES (:dbType, :host, :user, :pass, :name)");
            $sql->bindValue(':dbType', $dbType, PDO::PARAM_STR);
            $sql->bindValue(':host', $host, PDO::PARAM_STR);
            $sql->bindValue(':user', $user, PDO::PARAM_STR);
            $sql->bindValue(':pass', $pass, PDO::PARAM_STR);
            $sql->bindValue(':name', $name, PDO::PARAM_STR);
            $sql->execute();
        } catch (Exception $e) {
            return $e;
        }
        return $this->dbh->lastInsertId();
    }

    public function deleteDB($dbID)
    {
        try {
            $sql = $this->dbh->prepare("DELETE FROM db WHERE dbid = :dbID");
            $sql->bindValue(':dbID', $dbID, PDO::PARAM_INT);
            $sql->execute();
        } catch (Exception $e) {
            return $e;
        }
        return true;
    }

    public function users($uid = null)
    {
        try {
            if (isset($uid)) {
                $sql = $this->dbh->prepare("SELECT * FROM users WHERE user_id = :uid;");
                $sql->bindValue(':uid', $uid, PDO::PARAM_INT);
                $sql->execute();
                return $sql->fetch(PDO::FETCH_ASSOC);
            } else {
                $sql = $this->dbh->prepare("SELECT * FROM users");
                $sql->bindValue(':uid', $uid, PDO::PARAM_INT);
                $sql->execute();
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function editUser($uID, $email, $pid, $level = null, $permissions = null)
    {
        try {
            if (isset($level) && isset($permissions)) {
                $sql = $this->dbh->prepare("UPDATE users SET user_email = :email, playerid = :pid, user_level = :level, permissions = :permissions WHERE user_id = :uID;");
                $sql->bindValue(':uID', $uID, PDO::PARAM_INT);
                $sql->bindValue(':email', $email, PDO::PARAM_STR);
                $sql->bindValue(':pid', $pid, PDO::PARAM_STR);
                $sql->bindValue(':level', $level, PDO::PARAM_INT);
                $sql->bindValue(':permissions', $permissions, PDO::PARAM_STR);
                $sql->execute();
            } else {
                $sql = $this->dbh->prepare("UPDATE users SET user_email = :email, playerid = :pid WHERE user_id = :uID;");
                $sql->bindValue(':uID', $uID, PDO::PARAM_INT);
                $sql->bindValue(':email', $email, PDO::PARAM_STR);
                $sql->bindValue(':pid', $pid, PDO::PARAM_STR);
            }
        } catch (Exception $e) {
            return $e;
        }
        return true;
    }

    public function newUser($name, $pass, $email, $pid = null, $level, $permissions)
    {
        try {
            $sql = $this->dbh->prepare("INSERT INTO users (user_name, user_password_hash, user_email, playerid, user_level, permissions) VALUES (:name, :pass, :email, :pid, :level, :permissions)");
            $sql->bindValue(':name', $name, PDO::PARAM_STR);
            $sql->bindValue(':pass', password_hash($pass, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $sql->bindValue(':email', $email, PDO::PARAM_STR);
            $sql->bindValue(':pid', $pid, PDO::PARAM_STR);
            $sql->bindValue(':level', $level, PDO::PARAM_INT);
            $sql->bindValue(':permissions', $permissions, PDO::PARAM_STR);
            $sql->execute();
        } catch (Exception $e) {
            return $e;
        }
        return $this->dbh->lastInsertId();
    }

    public function banUser($uID)
    {
        try {
            $sql = $this->dbh->prepare("UPDATE users SET user_level = 0 WHERE user_id = :uID;");
            $sql->bindValue(':uID', $uID, PDO::PARAM_INT);
            $sql->execute();
        } catch (Exception $e) {
            return $e;
        }
        return true;
    }

    public function wanted($uID)
    {
        try {
            $sql = $this->sh->prepare("SELECT wantedCrimes FROM wanted WHERE wantedID = :uID;");
            $sql->bindValue(':uID', $uID, PDO::PARAM_INT);
            $sql->execute();
        } catch (Exception $e) {
            return $e;
        }
        return $sql->fetchAll(PDO::FETCH_NUM);
    }

    public function twoFactorNew($uid, $token = null, $twoFactor = null, $backup = null)
    {
        try {
            if (isset($token) && isset($twoFactor)) {
                $sql = $this->dbh->prepare("UPDATE users SET token = :token, twoFactor = :twoFactor WHERE user_id = :uid;");
                $sql->bindValue(':token', $token, PDO::PARAM_STR);
                $sql->bindValue(':token', $token, PDO::PARAM_STR);
                $sql->bindValue(':uid', $uid, PDO::PARAM_INT);
                $sql->execute();
            } elseif (isset($backup)) {
                $sql = $this->dbh->prepare("UPDATE users SET backup = :backupCode WHERE user_id = :uid;");
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
            if (isset($type)) {
                $sql = $this->dbh->prepare("UPDATE users SET backup = NULL WHERE user_id = :uid;");
                $sql->bindValue(':uid', $uid, PDO::PARAM_INT);
                $sql->execute();
            } else {
                $sql = $this->dbh->prepare("UPDATE users SET backup = NULL, twoFactor = NULL, token = NULL WHERE user_id = :uid;");
                $sql->bindValue(':uid', $uid, PDO::PARAM_INT);
                $sql->execute();
            }
        } catch (Exception $e) {
            return $e;
        }
        return true;
    }

    public function logAdd($user, $action, $level)
    {
        try {
            $sql = $this->dbh->prepare("INSERT INTO logs (user, action, level) VALUES (:user, :action, :level);");
            $sql->bindValue(':user', $user, PDO::PARAM_INT);
            $sql->bindValue(':action', $action, PDO::PARAM_STR);
            $sql->bindValue(':level', $level, PDO::PARAM_INT);
            $sql->execute();
        } catch (Exception $e) {
            return $e;
        }

        return $this->dbh->lastInsertId();
    }

    public function logs()
    {
        try {
            $sql = $this->dbh->prepare("SELECT * FROM logs");
            $sql->execute();
        } catch (Exception $e) {
            return $e;
        }

        return $sql->fetchAll(PDO::FETCH_NUM);
    }

    public function items($uid, $items)
    {
        try {
            $sql = $this->dbh->prepare("UPDATE users SET items = :items WHERE user_id = :uid;");
            $sql->bindValue(':items', $items, PDO::PARAM_INT);
            $sql->bindValue(':uid', $uid, PDO::PARAM_INT);
            $sql->execute();

            return true;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function cops($top = null)
    {
        try {
            if (isset($top)) {
                $sql = $this->sh->prepare("SELECT name,coplevel,playerid FROM players WHERE coplevel >= '1' ORDER BY coplevel DESC LIMIT 10");
                $sql->execute();
                return $sql->fetch(PDO::FETCH_ASSOC);
            } else {
                $sql = $this->dbh->prepare("SELECT * FROM users");
                $sql->bindValue(':uid', $uid, PDO::PARAM_INT);
                $sql->execute();
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            return $e;
        }
    }


}