<?php

class webTest extends PHPUnit_Extensions_Selenium2TestCase
{
    protected $url = 'http://localhost/';

    //protected $captureScreenshotOnFailure = TRUE;
    //protected $screenshotPath = 'E:\\screenshots\\';
    //protected $screenshotUrl = 'http://localhost/screenshots';

    protected function setUp()
    {
        require 'bootstrap.php';
        $this->sethost('localhost');
        //$this->setPort('firefox');
        $this->setBrowser('firefox');
        $this->setBrowserUrl($this->url);
    }

    public function login() {
        $this->url('index.php');
        $this->byId('loginLock')->click();
        sleep(1);

        $form = $this->byCssSelector('form');
        $action = $form->attribute('action');
        $this->assertEquals($this->url.'index',$action);

        $this->byName('user_name')->value('SCollins');
        $this->byName('user_password')->value('Pancakeme19');
        $this->byName('login')->click();
        sleep(1);

        $this->assertEquals('Dashboard',$this->byCssSelector('h1')->text());
        $this->url($this->url.'dashboard?id=1');
    }

    public function testConnected()
    {
        $this->url($this->url);
        $this->assertEquals('Cyber Works', $this->title());
    }

    public function testLogin () {
        $this->login(); ///todo finish login system
    }

    public function testEditPlayer()
    {
        $dao = new query();
        $this->login();

        $this->url($this->url.'editPlayer/12');
        $this->byId('editPlayer')->click();
        sleep(1);

        $new['cash'] = rand();
        $cash = $this->byId('player_cash');
        $player['cash'] = $cash->value();
        $cash->clear();
        $cash->value($new['cash']);

        $new['bank'] = rand();
        $bank = $this->byId('player_bank');
        $player['bank'] = $bank->value();
        $bank->clear();
        $bank->value($new['bank']);

        $copSelect = $this->select($this->byId('player_coplvl'));
        $player['copSelect'] = $copSelect->value();
        $copSelect->selectOptionByValue('1');

        $adminSelect = $this->select($this->byId('player_adminlvl'));
        $player['adminSelect'] = $adminSelect->value();
        $adminSelect->selectOptionByValue('1');

        $this->byId('Edit Player Submit')->click();

        $players = $dao->player(12);
        $this->assertEquals($new['cash'], $players['cash']);
        $this->assertEquals($new['bank'], $players['bankacc']);
        $this->assertEquals(1, $players['coplevel']);
        $this->assertEquals(1, $players['adminlevel']);

        $this->byId('editPlayer')->click();
        sleep(1);

        $cash = $this->byId('player_cash');
        $cash->clear();
        $cash->value($player['cash']);

        $bank = $this->byId('player_bank');
        $bank->clear();
        $bank->value($player['bank']);

        $copSelect = $this->select($this->byId('player_coplvl'));
        $player['copSelect'] = $copSelect->value();
        $copSelect->selectOptionByValue('1');

        $adminSelect = $this->select($this->byId('player_adminlvl'));
        $player['adminSelect'] = $adminSelect->value();
        $adminSelect->selectOptionByValue('1');

        $this->byId('Edit Player Submit')->click();

        $players = $dao->player(12);
        $this->assertEquals($player['cash'], $players['cash']);
        $this->assertEquals($player['bank'], $players['bankacc']);
    }

    public function testPages() {
        $this->login();
        $this->url($this->url.'vehicles');
        $this->byId('Page 1');
        $this->byId('Page 2');
        $this->byId('Page 3');
        $this->byId('Page 4');
        $this->byId('Page 5');

        $this->url($this->url.'vehicles?page=2');
        $this->byId('Page 1');
        $this->byId('Page 2');
        $this->byId('Page 3');
        $this->byId('Page 4');
        $this->byId('Page 5');

        $this->url($this->url.'vehicles?page=4');
        $this->byId('Page 2');
        $this->byId('Page 3');
        $this->byId('Page 4');
        $this->byId('Page 5');
        $this->byId('Page 6');

        $this->url($this->url.'vehicles?page=9');
        $this->byId('Page 7');
        $this->byId('Page 8');
        $this->byId('Page 9');
        $this->byId('Page 10');
        $this->byId('Page 11');

        $this->url($this->url.'vehicles?page=1&items=1000');
        $this->byId('Page 1');
        $this->byId('Page 2');
        $this->byId('Page 3');
        $this->byId('Page 4');
        $this->byId('Page 5');
    }
}
