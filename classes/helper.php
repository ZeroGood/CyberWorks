<?php

//$max = 'LIMIT ' . ($pageNum - 1) * $_SESSION['items'] . ',' . $_SESSION['items'];
class helper
{
    public function __construct()
    {
        $this->settings = 'config/settings.php';
    }

    public function getPlayerSkin($input, $list)
    {
        if ($input !== '[]') {
            $name = after('[`', $input);
            $name = before('`', $name);
            if (in_array($name, $list)) {
                return $name;
            } else {
                return "Default";
            }
        } else {
            return "Default";
        }
    }

    public function yesNo($input)
    {
        if ($input == 1) {
            return 'Yes'; //todo: add lang support
        } else if ($input == 0) {
            return 'No';
        } else {
            return $input;
        }

    }

    public function encrypt($text)
    {
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->settings['key'], $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

    public function decrypt($text)
    {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->settings['key'], base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

    function carType($car)
    {
        switch ($car) {
            case 'Car':
                return 'Car';
            case 'Air':
                return 'Air';
            case 'Ship':
                return 'Ship'; //todo lang
        }
    }
}