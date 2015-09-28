<?php

class setupTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require 'boostrap1.php';
        $this->setup = new setup();
        $this->query = new query();

        $this->setup->connect($host, $user, $password, $dbname);
    }

    public function testBase()
    {
        $url = $this->setup->base('/admin/index.php', 'localhost');
        $this->assertEquals('http://localhost/admin/', $url['full']);
        $this->assertEquals('admin/', $url['base']);

        $url = $this->setup->base('/index.php', 'localhost');
        $this->assertEquals('http://localhost/', $url['full']);
        $this->assertFalse($url['base']);

        $url = $this->setup->base('/', 'test.co.uk');
        $this->assertEquals('http://test.co.uk/', $url['full']);
        $this->assertFalse($url['base']);

        $url = $this->setup->base('/index.php', 'test.com');
        $this->assertEquals('http://test.com/', $url['full']);
        $this->assertFalse($url['base']);

        $url = $this->setup->base('/index/test/123//index.php', 'test.com');
        $this->assertEquals('http://test.com/index/test/123/', $url['full']);
        $this->assertEquals('index/test/123/', $url['base']);

        $url = $this->setup->base('/index/test/123////break/now//index.php', 'test.com');
        $this->assertEquals('http://test.com/index/test/123/break/now/', $url['full']);
        $this->assertEquals('index/test/123/break/now/', $url['base']);
    }

    public function testCreateServer()
    {
        $rand = rand();
        
        $server = $this->setup->createServer('Test Server #' . $rand, 1, 'life');
        $curServer = $this->query->servers($server);
        $this->assertEquals('Test Server #' . $rand, $curServer['name']);
        $this->assertEquals(1, $curServer['dbid']);
        $this->assertEquals('life', $curServer['type']);
        $this->assertEquals(0, $curServer['use_sq']);
        $this->assertNull($curServer['sq_port']);
        $this->assertNull($curServer['sq_ip']);
        $this->assertNull($curServer['rcon_pass']);
    }
}
