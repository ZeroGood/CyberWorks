<?php

class setup
{
    public function sqlHost ($host) {
        if (strpos($host, "http://")) {
            $ip = str_replace('/','',substr($host, 7));
        } elseif (strpos($host, "https://")) {
            $ip = str_replace('/','',substr($host, 8));
        } else {
            $ip = $host;
        }
        return $ip;
    }

    public function connect($host, $user, $password, $name) {
        $host = $this->sqlHost($host);
        return new PDO("mysql:dbname=$name;host=$host", $user, $password);
    }

    public function addSettings($settings) {
        try {
            return true;
        } catch (Exception $e) {
            return $e;
        }
    }


    public function base($uri = null, $host = null)
    {
        if (!isset($uri)) {
            $uri = $_SERVER['REQUEST_URI'];
        }
        if (!isset($host)) {
            $host = $_SERVER['HTTP_HOST'];
        }
        $uri = preg_replace('{(.)\1+}', '$1', $uri);;
        $last = str_replace(strrchr($uri, '/'), '', $uri) . '/';
        $url['full'] = 'http://' . $host . $last;
        $url['base'] = substr($last, 1);
        return $url;
    }

    public function hta()
    {
        $url = $this->base();
        $settings['url'] = $url['full'];
        $settings['base'] = substr_count($settings['url'], "/") - 2;
        $hta = 'RewriteEngine On
RewriteBase /' . $url['base'] . '
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule . /' . $url['base'] . 'index.php [L]

php_value file_get_contents 1';
        file_put_contents('.htaccess', $hta);
    }

    public function makeTables () {
        //$dbh = $this->connect();
        $sql = $dbh->prepare("DROP TABLE IF EXISTS users");
        $sql->execute();
        $sql = $dbh->prepare("DROP TABLE IF EXISTS notes");
        $sql->execute();
        $sql = $dbh->prepare("DROP TABLE IF EXISTS db");
        $sql->execute();
        $sql = $dbh->prepare("DROP TABLE IF EXISTS servers");
        $sql->execute();
        $sql = $dbh->prepare("DROP TABLE IF EXISTS logs");
        $sql->execute();

        $sql = $dbh->prepare("CREATE TABLE IF NOT EXISTS `users` (
      `user_id` int(11) NOT NULL primary key,
      `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
      `user_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
      `playerid` varchar(17) COLLATE utf8_unicode_ci DEFAULT NULL,
      `user_level` int(1) NOT NULL DEFAULT '1',
      `permissions` text COLLATE utf8_unicode_ci NOT NULL,
      `user_profile` varchar(255) NOT NULL,
      `items` int(2) NULL,
      `twoFactor` VARCHAR(25) NULL,
      `backup` VARCHAR(255) NULL,
      `token` VARCHAR(64) NULL
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
        $sql->execute();



        $sql = $dbh->prepare("CREATE TABLE IF NOT EXISTS `notes` (
    	  `note_id` INT(11) NOT NULL AUTO_INCREMENT,
    	  `uid` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    	  `staff_name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    	  `note_text` VARCHAR(255) NOT NULL,
    	  `note_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    	  PRIMARY KEY (`note_id`),
    	  UNIQUE KEY `note_id` (`note_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8_unicode_ci AUTO_INCREMENT=1;");
        $sql->execute();


        $sql = $dbh->prepare("CREATE TABLE IF NOT EXISTS `db` (
        `dbid` INT(11) NOT NULL AUTO_INCREMENT,
        `type` VARCHAR(64) NOT NULL,
        `sql_host` VARCHAR(64) NOT NULL,
        `sql_user` VARCHAR(64) NOT NULL,
        `sql_pass` VARCHAR(255) NOT NULL,
        `sql_name` VARCHAR(64) NOT NULL,
    	PRIMARY KEY (dbid),
    	UNIQUE KEY `dbid` (`dbid`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;");
        $sql->execute();
//todo: add to table

        //todo:server stuff
    }

    public function makeSettings () {
        $settings['maxLevels']['cop'] = 7;
        $settings['maxLevels']['medic'] = 3;
        $settings['maxLevels']['admin'] = 3;
        $settings['maxLevels']['donator'] = 8;

        $settings['items'] = 15;
        $settings['notifications'] = true;
        $settings['news'] = true;
        $settings['sql_phone'] = false;
        $settings['language'] = 'en';
        $settings['allowLang'] = true;
        $settings['wanted'] = false;
        $settings['version'] = '0.4';
        $settings['staffRanks'] = 5;
        $settings['logging'] = true;

        $settings['steamAPI'] = '';
        $settings['vacTest'] = false;
        $settings['steamdomain'] = '';
        $settings['steamlogin'] = false;
        $settings['plugins'] = array();
        $settings['performance'] = false;
        $settings['annonlogin'] = false;
        $settings['performance'] = false;
        $settings['register'] = false;
        $settings['passreset'] = false;
        $settings['performance'] = false;
        $settings['refresh'] = 30;
        $settings['communityBansTest'] = false;
        $settings['communityBansAPI'] = '';

        $settings['item'] = array(5,10,15,25,50);

        $settings['installedLanguage']=array();
        $lang = array('English','en');
        array_push($settings['installedLanguage'], $lang);

        $settings['names'] = array('', 'Dave', 'Sam', 'Joe', 'Kerry', 'Connie', 'Jess');
        $settings['ranks'] = array('Banned','Player','Member','Moderator','Server Admin','Super Admin');
    }












































    public function createServer($serverName, $serverDB, $serverSQ, $sqPort = null, $sqIP = null, $rconPass = null) {
        $sql = $dbh->prepare("CREATE TABLE IF NOT EXISTS `servers` (
        `sid` INT(2) NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(64) NOT NULL,
        `dbid` INT(2) NOT NULL,
        `type` VARCHAR(64) NOT NULL,
        `use_sq` INT(2) NOT NULL,
        `sq_port` VARCHAR(255) NULL,
        `sq_ip` VARCHAR(255) NULL,
        `rcon_pass` VARCHAR(255) NULL,
    	PRIMARY KEY (`sid`),
    	UNIQUE KEY `sid` (`sid`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;");
        $sql->execute();

        $query = new query();
        if (isset($sqPort) && isset($sqIP) && isset($rconPass)) {
            $query->newServer($serverName, $serverDB, $serverSQ, $sqPort, $sqIP, $rconPass);
        } else {
            $query->newServer($serverName, $serverDB, $serverSQ);
        }
    }

    public function createDB($type, $host, $user, $pass, $name) {
        $sql = $dbh->prepare("CREATE TABLE IF NOT EXISTS `db` (
        `dbid` INT(11) NOT NULL AUTO_INCREMENT,
        `type` VARCHAR(64) NOT NULL,
        `sql_host` VARCHAR(64) NOT NULL,
        `sql_user` VARCHAR(64) NOT NULL,
        `sql_pass` VARCHAR(255) NOT NULL,
        `sql_name` VARCHAR(64) NOT NULL,
    	PRIMARY KEY (dbid),
    	UNIQUE KEY `dbid` (`dbid`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;");
        $sql->execute();

        $query = new query();
        $query->newDB($type, $host, $user, $pass, $name);
    }

    public function createLog () {
        $sql = $dbh->prepare("CREATE TABLE IF NOT EXISTS `logs` (
        `logid` int(11) NOT NULL AUTO_INCREMENT,
        `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `user` varchar(64) DEFAULT NULL,
        `action` varchar(255) DEFAULT NULL,
        `level` int(11) NOT NULL,
        PRIMARY KEY (`logid`),
        UNIQUE KEY `logid` (`logid`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");
        $sql->execute();
    }

    public function createNotes () {
        $sql = $dbh->prepare("CREATE TABLE IF NOT EXISTS `notes` (
    	  `note_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing note_id of each user, unique index',
    	  `uid` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    	  `staff_name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
    	  `note_text` VARCHAR(255) NOT NULL,
    	  `note_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    	  PRIMARY KEY (`note_id`),
    	  UNIQUE KEY `note_id` (`note_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
        $sql->execute();
    }

    public function createUsers($type, $host, $user, $pass, $name) {
        $sql = $dbh->prepare("CREATE TABLE IF NOT EXISTS `users` (
      `user_id` int(11) NOT NULL primary key,
      `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
      `user_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
      `playerid` varchar(17) COLLATE utf8_unicode_ci DEFAULT NULL,
      `user_level` int(1) NOT NULL DEFAULT '1',
      `permissions` text COLLATE utf8_unicode_ci NOT NULL,
      `user_profile` varchar(255) NOT NULL,
      `items` int(2) NULL,
      `twoFactor` VARCHAR(25) NULL,
      `backup` VARCHAR(255) NULL,
      `token` VARCHAR(64) NULL
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
        $sql->execute();

        $query = new query();
        $query->newUser($type, $host, $user, $pass, $name);
    }




}