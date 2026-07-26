<?php
require_once '../../config/database.php';

$sql = "UPDATE corrective_actions SET status=?, completion_date=? WHERE id=?";
$stmt = $pdo->prepare($sql);

$completion_date = $_POST['status'] == 'Completed' ? (date('Y-m-d')) : null;

$stmt->execute([
    $_POST['status'],
    $completion_date,
    $_POST['id']
]);

header("Location: index.php?success=1");
exit;
