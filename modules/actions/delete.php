<?php
require_once '../../config/database.php';
require_once '../../config/auth.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    $_SESSION['error'] = 'Invalid action.';
    header('Location: index.php');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM corrective_actions WHERE id=?");
    $stmt->execute([$id]);
    $_SESSION['message'] = 'Action deleted successfully!';
} catch (Exception $e) {
    $_SESSION['error'] = 'Error deleting action: ' . $e->getMessage();
}

header('Location: index.php');
exit;