<?php
class User extends Database
{

    public $username;
    public $password;

    public function __construct($host, $username, $password, $dbname)
    {
        parent::__construct($host, $username, $password, $dbname);
        $this->username = $username;
        $this->password = $password;
    }



    function login($username, $password)
    {

        if ($username == $this->username && $password == $this->password) {
            return true;
        }
    }
}
