<?php
class User
{
    private $db;
    private $userData;

    public function __construct($db)
    {
        $this->db = $db;
        $this->userData = $this->getUserData();
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
        session_destroy();
    }

    public function register($username, $password)
    {
        // Controlla se l'username esiste già nel database
        $query = "SELECT COUNT(*) as count FROM users WHERE username = :username";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->execute();
        $rowCount = $statement->fetch(PDO::FETCH_ASSOC)['count'];

        if ($rowCount > 0) {
            return "Username già esistente.";
        }

        // Registra l'utente
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, password, accesslevel) VALUES (:username, :password, 'none')";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->bindParam(':password', $hashed_password);

        if ($statement->execute()) {
            return true;
        } else {
            return "Errore durante la registrazione.";
        }
    }

    public function getUsername()
    {
        return $this->userData['username'] ?? null;
    }

    public function getAccessLevel()
    {
        return $this->userData['accesslevel'] ?? null;
    }
    public function isAdmin()
    {
        return $this->userData['accesslevel'] === 'admin';
    }

    private function getUserData()
    {
        if ($this->isLoggedIn()) {
            $query = "SELECT * FROM users WHERE id = :id";
            $statement = $this->db->prepare($query);
            $statement->bindParam(':id', $_SESSION['user_id']);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }
}
