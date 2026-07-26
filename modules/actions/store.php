<?php
require_once '../../config/database.php';

$sql = "INSERT INTO corrective_actions (anomaly_id, action_required, assigned_to, target_date, status) VALUES (?, ?, ?, ?, 'Open')";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['anomaly_id'],
    $_POST['action_required'],
    $_POST['assigned_to'] ?? null,
    $_POST['target_date']
]);

header("Location: index.php?success=1");
exit;
