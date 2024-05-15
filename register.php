<?php
session_start();

include_once 'classes/Database.php';
include_once 'classes/User.php';

$db = new Database();
$connection = $db->getConnection();
$user = new User($connection);

// Se l'utente è già loggato, reindirizza alla dashboard
if ($user->isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

// Gestione della registrazione
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $registerResult = $user->register($username, $password);

    // Se la registrazione ha avuto successo, effettua il login e reindirizza alla dashboard
    if ($registerResult === true) {
        if ($user->login($username, $password)) {
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Errore durante il login.";
        }
    } else {
        $error = $registerResult;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <h2>Registration</h2>
        <?php if (!empty($error)) { ?>
            <div class="error"><?php echo $error; ?></div> <!-- Mostra il messaggio di errore -->
        <?php } ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="register">Register</button>
        </form>
    </div>
</body>

</html>