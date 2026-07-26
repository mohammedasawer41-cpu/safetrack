<?php
require_once '../../config/database.php';

$sql = "UPDATE users SET fullname=?, email=?, username=?, role_id=?, status=? WHERE id=?";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    $_POST['fullname'],
    $_POST['email'],
    $_POST['username'],
    $_POST['role_id'],
    $_POST['status'],
    $_POST['id']
]);

header("Location: index.php?success=1");
exit;
