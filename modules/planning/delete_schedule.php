<?php
require_once '../../config/database.php';
require_once '../../config/auth.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    $_SESSION['error'] = 'Invalid schedule.';
    header('Location: monthly.php');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM inspection_schedule WHERE id=?");
    $stmt->execute([$id]);
    $_SESSION['message'] = 'Schedule deleted successfully!';
} catch (Exception $e) {
    $_SESSION['error'] = 'Error deleting schedule: ' . $e->getMessage();
}

header('Location: monthly.php');
exit;