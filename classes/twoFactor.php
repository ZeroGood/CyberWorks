<?php

// add in try catches

class twoFactor
{
    function __construct($query) {
        $this->query = $query;
        $this->gauth = new PHPGangsta_GoogleAuthenticator();
    }

    public function getBackupCode () {
        $this->query->twoFactorBackupCode($this->gauth->createSecret(8), $_SESSION['user_id']);
    }


}