<?php

require_once('../classes/query.php');

class queryTest extends PHPUnit_Framework_TestCase
{
    protected $testUser = 1;

    public function testConnection()
    {
        try {
            $dbh = new PDO("mysql:dbname=$this->dbname;host=$this->host", $this->user, $this->password);
            $this->assertTrue($dbh instanceof PDO);
        } catch (PDOException $e) {
            $this->fail($e);
        }
    }

    public function testTwoFactor()
    {
        $query = new query();
        $original = $query->user($this->testUser);

        $code = rand();
        $query->twoFactorNew($this->testUser, null, null, $code);
        $user = $query->user($this->testUser);
        $this->assertEquals($user['backup'], $code);

        $query->twoFactorRevoke($this->testUser, 'backup');
        $this->assertNotEquals($query->user($this->testUser)['backup'], $code);
        $query->twoFactorRevoke($this->testUser);
        // todo: test for all null
        $query->twoFactorNew($this->testUser, $original['token'], $original['twoFactor'], $original['backup']);

        $user = $query->user($this->testUser);
        $this->assertEquals($original['token'], $user['token']);
        $this->assertEquals($original['twoFactor'], $user['twoFactor']);
        $this->assertEquals($original['backup'], $user['backup']);
    }

    public function testUser()
    {
        $query = new query();
        $user = $query->user($this->testUser);

        $this->assertArrayHasKey('user_id', $user);
        $this->assertArrayHasKey('user_name', $user);
        $this->assertArrayHasKey('user_password_hash', $user);
        $this->assertArrayHasKey('user_level', $user);
        $this->assertArrayHasKey('twoFactor', $user);
        $this->assertArrayHasKey('backup', $user);
        $this->assertArrayHasKey('token', $user);
    }

    public function testLogAdd()
    {
        $query = new query();
        $rand = rand();
        $id = $query->logAdd('PHPUnit', 'Testing Log System #' . $rand, '1');
        $this->assertTrue(ctype_digit($id));
        $this->assertEquals($query->logs()[$id - 1]['action'], 'Testing Log System #' . $rand);
    }

    public function testHouses()
    {
        $query = new query();
        $houses = $query->houses(24);

        $this->assertArrayHasKey('id', $houses);
        $this->assertArrayHasKey('pid', $houses);
        $this->assertArrayHasKey('pos', $houses);
        $this->assertArrayHasKey('inventory', $houses);
        $this->assertArrayHasKey('containers', $houses);
        $this->assertArrayHasKey('owned', $houses);

        $houses = $query->houses();

        $this->assertArrayHasKey('0', $houses);
        $this->assertArrayHasKey('id', $houses[0]);
        $this->assertArrayHasKey('pid', $houses[0]);
        $this->assertArrayHasKey('pos', $houses[0]);
        $this->assertArrayHasKey('inventory', $houses[0]);
        $this->assertArrayHasKey('containers', $houses[0]);
        $this->assertArrayHasKey('owned', $houses[0]);
    }

    public function testGangs()
    {
        $query = new query();
        $gangs = $query->gangs(15);

        $this->assertArrayHasKey('id', $gangs);
        $this->assertArrayHasKey('owner', $gangs);
        $this->assertArrayHasKey('name', $gangs);
        $this->assertArrayHasKey('members', $gangs);
        $this->assertArrayHasKey('maxmembers', $gangs);
        $this->assertArrayHasKey('bank', $gangs);
        $this->assertArrayHasKey('active', $gangs);

        $gangs = $query->gangs();

        $this->assertArrayHasKey('0', $gangs);
        $this->assertArrayHasKey('id', $gangs[0]);
        $this->assertArrayHasKey('owner', $gangs[0]);
        $this->assertArrayHasKey('name', $gangs[0]);
        $this->assertArrayHasKey('members', $gangs[0]);
        $this->assertArrayHasKey('maxmembers', $gangs[0]);
        $this->assertArrayHasKey('bank', $gangs[0]);
        $this->assertArrayHasKey('active', $gangs[0]);
    }

    public function testDB()
    {
        $query = new query();
        $dbID = $query->newDB('life', '127.0.0.1', 'test', 'database', 'test');
        $this->assertTrue(ctype_digit($dbID));

        $db = $query->DB($dbID);

        $this->assertEquals($db['type'], 'life');
        $this->assertEquals($db['sql_host'], '127.0.0.1');
        $this->assertEquals($db['sql_user'], 'test');
        $this->assertEquals($db['sql_pass'], 'database');
        $this->assertEquals($db['sql_name'], 'test');

        $query->editDB($dbID, 'localhost', 'user', 'testing', 'tested');
        $db = $query->DB($dbID);

        $this->assertEquals($db['type'], 'life');
        $this->assertEquals($db['sql_host'], 'localhost');
        $this->assertEquals($db['sql_user'], 'user');
        $this->assertEquals($db['sql_pass'], 'testing');
        $this->assertEquals($db['sql_name'], 'tested');

        $this->assertTrue($query->deleteDB($dbID));
        $this->assertFalse($query->DB($dbID));
    }

}