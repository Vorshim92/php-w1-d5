<?php
session_start();

include_once 'classes/Database.php';
include_once 'classes/User.php';

$db = new Database();
$connection = $db->getConnection();

$user = new User($connection);

if ($user->isLoggedIn()) {
    if ($user->isAdmin()) {
        header('Location: admin/administration.php');
        exit();
    } else {
        header('Location: user/dashboard.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $login_result = $user->login($username, $password);
    if ($login_result === true) {
        $user = new User($connection);
        if ($user->isAdmin()) {
            header('Location: admin/administration.php');
            exit();
        } else {
            header('Location: user/dashboard.php');
            exit();
        }
    } else {
        $error = "Credenziali non valide.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
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
            <button type="submit" name="login">Login</button>
        </form>
        <p>Non hai ancora un account? <a href="register.php">Registrati</a></p>
    </div>
</body>

</html>