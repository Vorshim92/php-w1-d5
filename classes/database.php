<?php
class Database
{
    private $host;
    private $username;
    private $password;
    private $dbname;
    private $db;

    public function __construct($host, $username, $password, $dbname)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;

        try {
            $this->db = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connessione al database fallita: ' . $e->getMessage();
            exit();
        }
    }

    public function __destruct()
    {
        $this->db = null;
    }

    public function query($args)
    {
        return $this->db->query($args);
    }

    public function prepare($args)
    {
        return $this->db->prepare($args);
    }

    // CREA
    public function create($table, $data)
    {
        $fields = array_keys($data);
        $placeholders = array_fill(0, count($fields), '?');

        $fieldsSql = implode(', ', $fields);
        $placeholdersSql = implode(', ', $placeholders);

        $sql = "INSERT INTO $table ($fieldsSql) VALUES ($placeholdersSql)";
        $statement = $this->db->prepare($sql);

        return $statement->execute(array_values($data));
    }

    // LEGGI
    public function read($table, $conditions = array(), $fields = array())
    {
        $sql = "SELECT " . (empty($fields) ? '*' : implode(', ', $fields)) . " FROM $table";

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $conditionsSql = array();
            foreach ($conditions as $key => $value) {
                $conditionsSql[] = "$key = ?";
            }
            $sql .= implode(' AND ', $conditionsSql);
        }

        $statement = $this->db->prepare($sql);
        $statement->execute(array_values($conditions));

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // AGGIORNA
    public function update($table, $data, $conditions)
    {
        $fields = array();
        foreach ($data as $key => $value) {
            $fields[] = "$key=?";
        }
        $fieldsSql = implode(', ', $fields);

        $conditionsSql = array();
        foreach ($conditions as $key => $value) {
            $conditionsSql[] = "$key = ?";
        }
        $conditionsSql = implode(' AND ', $conditionsSql);

        $sql = "UPDATE $table SET $fieldsSql WHERE $conditionsSql";
        $statement = $this->db->prepare($sql);

        $values = array_merge(array_values($data), array_values($conditions));

        return $statement->execute($values);
    }

    // ELIMINA
    public function delete($table, $conditions)
    {
        $conditionsSql = array();
        foreach ($conditions as $key => $value) {
            $conditionsSql[] = "$key = ?";
        }
        $conditionsSql = implode(' AND ', $conditionsSql);

        $sql = "DELETE FROM $table WHERE $conditionsSql";
        $statement = $this->db->prepare($sql);

        return $statement->execute(array_values($conditions));
    }
}
