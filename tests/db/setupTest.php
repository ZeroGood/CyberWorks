<?php

class setupTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        require_once('classes/setup.php');
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

    public function testConnect() {
        $setup = new setup();
        $setup->connect($this->host,$this->user, $this->password, $this->dbname);
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
}
