<?php

class queryTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require_once('classes/query.php');
        if (file_exists('devstuff/envConfig.php')) {
            $settings = include('devstuff/envConfig.php');
            $this->host = $settings['host'];
            $this->user = $settings['user'];
            $this->password = $settings['password'];
            $this->dbname = $settings['name'];
        } else {
            $this->host = 'localhost';
            $this->user = 'travis';
            $this->password = '';
            $this->dbname = 'test_db';
        }
    }

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
        $user = $query->user($this->testUser);

        $this->assertNull($user['backup']);
        $this->assertNull($user['twoFactor']);
        $this->assertNull($user['token']);

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
        $house = $query->houses(24);

        $this->assertArrayHasKey('id', $house);
        $this->assertArrayHasKey('pid', $house);
        $this->assertArrayHasKey('pos', $house);
        $this->assertArrayHasKey('inventory', $house);
        $this->assertArrayHasKey('containers', $house);
        $this->assertArrayHasKey('owned', $house);

        $houses = $query->houses();

        $this->assertArrayHasKey('0', $houses);
        $this->assertArrayHasKey('id', $houses[0]);
        $this->assertArrayHasKey('pid', $houses[0]);
        $this->assertArrayHasKey('pos', $houses[0]);
        $this->assertArrayHasKey('inventory', $houses[0]);
        $this->assertArrayHasKey('containers', $houses[0]);
        $this->assertArrayHasKey('owned', $houses[0]);

        $query->editHouse(24, '929282828', 'test', 'tested', 'yep', 0);
        $houses = $query->houses(24);

        $this->assertEquals(24, $houses['id']);
        $this->assertEquals(929282828, $houses['pid']);
        $this->assertEquals('test', $houses['pos']);
        $this->assertEquals('tested', $houses['inventory']);
        $this->assertEquals('yep', $houses['containers']);
        $this->assertEquals(0, $houses['owned']);

        $query->editHouse($house['id'], $house['pid'], $house['pos'], $house['inventory'], $house['containers'], $house['owned']);
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

    public function testServers()
    {
        $query = new query();
        $rand = rand();
        $server = $query->newServer('Test Server #' . $rand, 1, 'life');
        $this->assertTrue(ctype_digit($server));

        $servers = $query->servers($server);
        $this->assertEquals($servers['sid'], $server);
        $this->assertEquals($servers['name'], 'Test Server #' . $rand);
        $this->assertEquals($servers['dbid'], 1);
        $this->assertEquals($servers['type'], 'life');
        $this->assertEquals($servers['use_sq'], 0);
        $this->assertNull($servers['sq_port']);
        $this->assertNull($servers['sq_ip']);
        $this->assertNull($servers['rcon_pass']);
        $this->assertTrue($query->deleteServer($server));

        $servers = $query->servers($server);
        $this->assertNull($servers['sid']);
        $this->assertNull($servers['name']);
        $this->assertNull($servers['type']);

        $rand = rand();
        $server = $query->newServer('Test Server #' . $rand, 1, 'life', '3202', '127.0.0.1', 'test');
        $this->assertTrue(ctype_digit($server));
        $servers = $query->servers($server);
        $this->assertEquals($servers['sid'], $server);
        $this->assertEquals($servers['name'], 'Test Server #' . $rand);
        $this->assertEquals($servers['dbid'], 1);
        $this->assertEquals($servers['type'], 'life');
        $this->assertEquals($servers['use_sq'], 1);
        $this->assertEquals($servers['sq_port'], '3202');
        $this->assertEquals($servers['sq_ip'], '127.0.0.1');
        $this->assertEquals($servers['rcon_pass'], 'test');
        $this->assertTrue($query->deleteServer($server));

        $servers = $query->servers($server);
        $this->assertNull($servers['sid']);
        $this->assertNull($servers['name']);
        $this->assertNull($servers['type']);
    }

    public function testItems()
    {
        $query = new query();

        $this->assertTrue($query->items($this->testUser,5));
        $this->assertTrue($query->items($this->testUser,50));
        $this->assertTrue($query->items($this->testUser,15));
    }

}