<?php
class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function login($username, $password)
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            return true;
        } else {
            return false;
        }
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
    }
    public function register($username, $password)
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->execute();
        $existingUser = $statement->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            return "Username già in uso.";
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->bindParam(':password', $hashed_password);

        if ($statement->execute()) {
            return true;
        } else {
            return "Errore durante la registrazione.";
        }
    }
}
