<?php

require 'classes/helper.php';

class helperTest extends PHPUnit_Framework_TestCase
{
    public function testPages() {
        $helper = new helper();

        $page = $helper->pages(5);
        $this->assertEquals(1,$page['start']);
        $this->assertEquals(1,$page['end']);
        $this->assertEquals(1,$page['num']);

        $page = $helper->pages(5,5,1);
        $this->assertEquals(1,$page['start']);
        $this->assertEquals(5,$page['end']);
        $this->assertEquals(5,$page['num']);

        $page = $helper->pages(30);
        $this->assertEquals(1,$page['start']);
        $this->assertEquals(2,$page['end']);
        $this->assertEquals(1,$page['num']);

        $page = $helper->pages(500);
        $this->assertEquals(1,$page['start']);
        $this->assertEquals(5,$page['end']);
        $this->assertEquals(1,$page['num']);

        $page = $helper->pages(100,2,5);
        $this->assertEquals(1,$page['start']);
        $this->assertEquals(5,$page['end']);
        $this->assertEquals(2,$page['num']);

        $page = $helper->pages(1000,67,15);
        $this->assertEquals(63,$page['start']);
        $this->assertEquals(67,$page['end']);
        $this->assertEquals(67,$page['num']);

    }

    public function permissions () {
        $helper = new helper();
        $perms = $helper->permissions(1, true);
        $this->assertEquals(1, $perms['']);
    }
}
