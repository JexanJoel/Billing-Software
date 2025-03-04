<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'owner') {
    header('Location: login.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard</title>
</head>
<body>
    <h1>Welcome, Owner!</h1>
    <p>This is the owner dashboard.</p>
</body>
</html>