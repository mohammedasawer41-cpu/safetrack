<?php
require_once '../../config/database.php';

$year = date('Y');
$next = $pdo->query("SELECT IFNULL(MAX(id),0)+1 FROM anomalies")->fetchColumn();
$anomalyNo = "ANO-" . $year . str_pad($next, 4, "0", STR_PAD_LEFT);

$sql = "INSERT INTO anomalies (anomaly_no, title, description, severity, status, reported_by, reported_date, inspection_id) VALUES (?, ?, ?, ?, 'Open', ?, NOW(), ?)";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    $anomalyNo,
    $_POST['title'],
    $_POST['description'] ?? '',
    $_POST['severity'],
    $_SESSION['user_id'],
    $_POST['inspection_id'] ?? null
]);

header("Location: index.php?success=1");
exit;
