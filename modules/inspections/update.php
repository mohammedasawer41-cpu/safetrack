<?php
require_once '../../config/database.php';

$sql = "UPDATE inspections SET status=?, remarks=? WHERE id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['status'],
    $_POST['remarks'] ?? '',
    $_POST['id']
]);

header("Location: view.php?id=" . $_POST['id']);
exit;
