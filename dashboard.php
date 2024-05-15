<?php
session_start();

include_once 'classes/Database.php';
include_once 'classes/User.php';

$db = new Database();
$connection = $db->getConnection();
$user = new User($connection);

if (!$user->isLoggedIn()) {
    header('Location: login.php');
    exit();
} else {
    $userID = $user->isLoggedIn();
}

$query = "SELECT username FROM users WHERE id = :id";
$statement = $connection->prepare($query);
$statement->bindParam(':id', $userID);
$statement->execute();
$userData = $statement->fetch(PDO::FETCH_ASSOC);
$username = $userData['username'];

// Effettua il logout quando l'utente fa clic sul link "Logout"
if (isset($_POST['logout'])) {
    $user->logout();
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <h2>Dashboard</h2>
        <p>Benvenuto, <?php echo $username; ?>!</p>
        <form method="post">
            <button type="submit" name="logout">Logout</button>
        </form>
    </div>
</body>

</html>