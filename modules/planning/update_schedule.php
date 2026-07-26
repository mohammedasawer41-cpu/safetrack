<?php
require_once '../../config/database.php';

$sql = "UPDATE inspection_schedule SET inspection_date=?, status=?, remarks=? WHERE id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['inspection_date'],
    $_POST['status'],
    $_POST['remarks'] ?? '',
    $_POST['id']
]);

header("Location: monthly.php?success=1");
exit;
