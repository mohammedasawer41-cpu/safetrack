<?php
require_once '../../config/database.php';

$id = (int)$_GET['id'];
if($id) {
    $pdo->prepare("DELETE FROM anomalies WHERE id=?")->execute([$id]);
}

header("Location: index.php?success=1");
exit;
