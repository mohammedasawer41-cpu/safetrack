<?php
require_once '../../config/database.php';

$id = (int)($_POST['id'] ?? 0);
$status = trim($_POST['status'] ?? '');
$remarks = trim($_POST['remarks'] ?? '');

if (!$id) {
    $_SESSION['error'] = 'Invalid schedule.';
    header('Location: monthly.php');
    exit;
}

if (!in_array($status, ['Planned', 'Assigned', 'In Progress', 'Completed', 'Verified', 'Closed'])) {
    $_SESSION['error'] = 'Invalid status.';
    header('Location: schedule_details.php?id=' . $id);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE inspection_schedule SET status=?, remarks=? WHERE id=?");
    $stmt->execute([$status, $remarks, $id]);
    $_SESSION['message'] = 'Status updated successfully!';
} catch (Exception $e) {
    $_SESSION['error'] = 'Error updating status: ' . $e->getMessage();
}

header('Location: schedule_details.php?id=' . $id);
exit;