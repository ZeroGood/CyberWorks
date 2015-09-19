<?php

require 'classes/helper.php';

class helperTest extends PHPUnit_Framework_TestCase
{
    public function permissions()
    {
        $helper = new helper();
        $perms = $helper->permissions(1, true);
        $this->assertEquals(1, $perms['']);
    }

    public function testYesNo()
    {
        $helper = new helper();
        $this->assertEquals('Yes', $helper->yesNo(1));
        $this->assertEquals('No', $helper->yesNo(0));
        $this->assertEquals('test', $helper->yesNo('test'));
    }

    public function testEncryption()
    {
        $helper = new helper();
        $this->assertEquals('testString', $helper->decrypt($helper->encrypt('testString')));
    }
}
