<?php

class setupTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require_once('boostrap.php');
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

    public function testBase()
    {
        $setup = new setup();

        $url = $setup->base('/admin/index.php', 'localhost');
        $this->assertEquals('http://localhost/admin/', $url['full']);
        $this->assertEquals('admin/', $url['base']);

        $url = $setup->base('/index.php', 'localhost');
        $this->assertEquals('http://localhost/', $url['full']);
        $this->assertFalse($url['base']);

        $url = $setup->base('/', 'test.co.uk');
        $this->assertEquals('http://test.co.uk/', $url['full']);
        $this->assertFalse($url['base']);

        $url = $setup->base('/index.php', 'test.com');
        $this->assertEquals('http://test.com/', $url['full']);
        $this->assertFalse($url['base']);

        $url = $setup->base('/index/test/123//index.php', 'test.com');
        $this->assertEquals('http://test.com/index/test/123/', $url['full']);
        $this->assertEquals('index/test/123/', $url['base']);

        $url = $setup->base('/index/test/123////break/now//index.php', 'test.com');
        $this->assertEquals('http://test.com/index/test/123/break/now/', $url['full']);
        $this->assertEquals('index/test/123/break/now/', $url['base']);
    }

    public function testCreateServer()
    {
        $setup = new setup();
        $query = new query();
        $rand = rand();

        $server = $setup->createServer('Test Server #'.$rand, 1, 'life');
        $curServer = $query->servers($server);
        $this->assertEquals('Test Server #'.$rand, $curServer['name']);
        $this->assertEquals(1, $curServer['dbid']);
        $this->assertEquals('life', $curServer['type']);
        $this->assertEquals(0, $curServer['use_sq']);
        $this->assertNull($curServer['sq_port']);
        $this->assertNull($curServer['sq_ip']);
        $this->assertNull($curServer['rcon_pass']);
    }
}
