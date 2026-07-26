<?php
require_once '../../config/database.php';

$sql = "INSERT INTO inspection_schedule (inspection_date, inspector_id, template_id, site_id, location, status, remarks) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    $_POST['inspection_date'],
    $_POST['inspector_id'] ?? null,
    $_POST['template_id'],
    $_POST['site_id'] ?? null,
    $_POST['location'],
    $_POST['status'],
    $_POST['remarks'] ?? ''
]);

header("Location: monthly.php?success=1");
exit;
