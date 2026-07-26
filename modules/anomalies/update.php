<?php
require_once '../../config/database.php';

$sql = "UPDATE anomalies SET title=?, description=?, severity=?, status=? WHERE id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['title'],
    $_POST['description'] ?? '',
    $_POST['severity'],
    $_POST['status'],
    $_POST['id']
]);

header("Location: index.php?success=1");
exit;
