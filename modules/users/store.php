<?php
require_once '../../config/database.php';

$sql = "INSERT INTO users (fullname, email, username, password, role_id, status) VALUES (?, ?, ?, ?, ?, 'Active')";
$stmt = $pdo->prepare($sql);

$hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

$stmt->execute([
    $_POST['fullname'],
    $_POST['email'],
    $_POST['username'],
    $hashedPassword,
    $_POST['role_id']
]);

header("Location: index.php?success=1");
exit;
