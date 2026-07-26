<?php
require_once '../../config/database.php';

$id = (int)$_GET['id'];

if($id) {
    $pdo->prepare("DELETE FROM inspection_schedule WHERE id=?")->execute([$id]);
}

header("Location: monthly.php?success=1");
exit;
