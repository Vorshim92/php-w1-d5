<?php
session_start();

include_once '../classes/Database.php';
include_once '../classes/User.php';

$db = new Database();
$connection = $db->getConnection();
$user = new User($connection);

if (!$user->isLoggedIn()) {
    header('Location: ../login.php');
    exit();
}

if (!$user->isAdmin()) {
    header('Location: ../access_denied.php');
    exit();
}
if (isset($_POST['logout'])) {
    $user->logout();
    header('Location: ../login.php');
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
        <h2>Admin Page</h2>
        <p>Benvenuto, <?php echo $user->getUsername(); ?>!</p>
        <form method="post">
            <button type="submit" name="logout">Logout</button>
        </form>
    </div>
</body>

</html>