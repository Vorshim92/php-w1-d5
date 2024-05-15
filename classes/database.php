<?php
class Database
{
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'php-w1-d5';
    private $db;

    public function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
        try {
            $this->db = new PDO($dsn, $this->username, $this->password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connessione al database fallita: ' . $e->getMessage();
            exit();
        }
    }

    public function getConnection()
    {
        return $this->db;
    }
}
