<?php

class login
{
    public $errors = array();
    public $messages = array();

    public function __construct()
    {
        $this->settings = require 'config/settings.php';

        $this->dao = new query();

        if (isset($_GET["logout"])) {
            $this->logout();
        } elseif (isset($_GET["steam"])) {
            $this->steam();
        } elseif (isset($_POST["login"])) {
            $this->userLogin();
        }
    }

    public function steamInfo($steamID)
    {
        $response = json_decode(file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . $this->settings['steamAPI'] . '&steamids=' . $steamID));
        return $response->response->players[0];
    }

    public function loggedIn()
    {
        if (isset($_SESSION['user_level'])) {
            return true;
        }
        return false;
    }

    public function userLogin()
    {
        if (empty($_POST['user_name'])) {
            $this->errors[] = "Username field was empty.";
        } elseif (empty($_POST['user_password'])) {
            $this->errors[] = "Password field was empty.";
        } elseif (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {
            $user = $this->dao->login($_POST['user_name']);
            if (!empty($user)) {
                $user = $user[0];
                if ($user['user_level'] > 0) {
                    if (password_verify($_POST['user_password'], $user['user_password_hash'])) {
                        $_SESSION['steamsignon'] = false;
                        $this->main($user);
                    } else {
                        $this->errors[] = "Your password is incorrect";
                        $this->dao->logAdd($_POST['user_name'], 'Login Failed - Banned User (' . $_SERVER['REMOTE_ADDR'] . ')', 3);
                    }
                } else {
                    $this->errors[] = "User is banned";
                    $this->dao->logAdd($_POST['user_name'], 'Login Failed - Banned User (' . $_SERVER['REMOTE_ADDR'] . ')', 3);
                }
            } else {
                $this->errors[] = "User not found";
                $this->dao->logAdd($_POST['user_name'], 'Login Failed - User not Found (' . $_SERVER['REMOTE_ADDR'] . ')', 3);
            }
        } //no input
    }

    public function steam()
    {
        if ($this->settings['steamlogin']) {
            require_once 'openid.php';
            $openid = new LightOpenID($this->settings['url']);
            if (!$openid->mode) {
                $openid->identity = 'http://steamcommunity.com/openid';
                header('Location: ' . $openid->authUrl());
            } elseif ($openid->mode == 'cancel') {
                $this->errors[] = "Steam Login cancelled";
                $this->dao->logAdd('Login Failed', 'Failed Steam Login (' . $_SERVER['REMOTE_ADDR'] . ')', 3);
            } else {
                if ($openid->validate()) {
                    preg_match("/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/", $openid->identity, $matches);
                    $this->steamLogin($matches[1]);
                } else {
                    $this->errors[] = "Validation Failed";
                    $this->dao->logAdd('Login Failed', 'Failed Steam Login (' . $_SERVER['REMOTE_ADDR'] . ')', 3);
                }
            }
        } else {
            $this->errors[] = "Steam Login not enabled";
            $this->dao->logAdd('Login Failed', 'Failed Steam Login (' . $_SERVER['REMOTE_ADDR'] . ')', 3);
        }
    }

    public function steamLogin($pid)
    {
        if (empty($pid)) {
            $this->errors[] = "Username field was empty.";
        } elseif (!empty($pid)) {
            $user = $this->dao->login($pid);
            if (!empty($user)) {
                if ($user['user_level'] < 1) {
                    $_SESSION['steamsignon'] = true;
                    $this->main($user);
                } else {
                    $this->errors[] = "User is banned.";
                    $this->dao->logAdd($_POST['user_name'], 'Login Failed - Banned User (' . $_SERVER['REMOTE_ADDR'] . ')', 3);
                }
            } else {
                if ($this->settings['annonlogin']) {
                    $permissions = require('config/permissions.php');
                    $steam = $this->steamInfo($pid);
                    $_SESSION['playerid'] = $pid;
                    $_SESSION['user_name'] = $steam->personaname;
                    $_SESSION['user_level'] = 1;
                    $_SESSION['user_profile'] = $steam->avatarmedium;
                    $_SESSION['permissions'] = $permissions[1];
                    $_SESSION['items'] = $this->settings['items'];
                    $_SESSION['user_login_status'] = 1;
                    $_SESSION['profile_link'] = $steam->profileurl;
                    $_SESSION['steamsignon'] = true;
                    $_SESSION['servers'] = $this->dao->servers();
                    $_SESSION['2factor'] = 0;

                    if (count($_SESSION['servers']) > 1) {
                        $_SESSION['multiDB'] = true;
                    } else {
                        $_SESSION['multiDB'] = false;
                        $_SESSION['server_type'] = $_SESSION['servers'][0]['type'];
                        $_SESSION['dbid'] = $_SESSION['servers'][0]['life'];
                    }
                    $this->dao->logAdd($_SESSION['user_name'], 'Successful Steam Login (' . $_SERVER['REMOTE_ADDR'] . ')', 2);
                } else {
                    $this->errors[] = "Steam login has only been enabled for users";
                    $this->dao->logAdd($pid, 'Login Failed - Steam Login only for users (' . $_SERVER['REMOTE_ADDR'] . ')', 3);
                }
            }
        } //no input
    }

    public function main($user)
    {
        $_SESSION['2factor'] = 0;
        if (!empty($user['twoFactor'])) {
            if ($this->settings['2factor']) {
                $_SESSION['2factor'] = 1;
                if (isset($_COOKIE['token']) && !empty($user->token)) {
                    if (decrypt($user->token) == $_COOKIE['token']) {
                        $_SESSION['2factor'] = 2;
                    }
                }
            } else {
                $this->dao->twoFactorRevoke($user['user_id']);
                $this->errors[] = '2 Factor has been disabled on your account';
            }
        }
        $_SESSION['servers'] = $this->dao->servers(null, true);

        if (count($_SESSION['servers']) > 1) {
            $_SESSION['multiDB'] = true;
        } else {
            $_SESSION['multiDB'] = false;
            $_SESSION['server_type'] = $_SESSION['servers'][0]['type'];
            $_SESSION['dbid'] = $_SESSION['servers'][0]['life'];
        }

        $_SESSION['sudo'] = time();
        $_SESSION['user_name'] = $user['user_name'];
        $_SESSION['user_level'] = $user['user_level'];
        $_SESSION['user_profile'] = $user['user_profile'];
        $_SESSION['user_email'] = $user['user_email'];
        $_SESSION['playerid'] = $user['playerid'];
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['steamsignon'] = false;
        $_SESSION['permissions'] = json_decode($user['permissions'], true);
        if (isset($user->items)) $_SESSION['items'] = $user->items; else $_SESSION['items'] = $this->settings['items'];
        if (isset($_POST['lang'])) {
            setcookie('lang', $_POST['lang'], time() + (3600 * 24 * 30));
            $_SESSION['lang'] = $_POST['lang'];
        }

        $_SESSION['2factor'] = 2;

        $this->dao->logAdd($_SESSION['user_name'], 'Successful Login (' . $_SERVER['REMOTE_ADDR'] . ')', 2);
    }

    public function logout()
    {
        if (isset($_SESSION['user_name'])) {
            $this->dao->logAdd($_SESSION['user_name'], 'Logged Out', 1);
        }
        $_SESSION = array();
        session_destroy();
        $this->messages[] = 'You have been logged out';

    }
}