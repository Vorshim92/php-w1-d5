<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: user/dashboard.php');
    exit();
} else {
    header('Location: login.php');
    exit();
}
