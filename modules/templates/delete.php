<?php
require_once '../../config/database.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: index.php');
    exit;
}

try {
    $pdo->beginTransaction();
    
    // Delete questions first (foreign key)
    $del_q = $pdo->prepare("DELETE FROM checklist_questions WHERE template_id=?");
    $del_q->execute([$id]);
    
    // Delete template
    $del_t = $pdo->prepare("DELETE FROM checklist_templates WHERE id=?");
    $del_t->execute([$id]);
    
    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
}

header('Location: index.php?deleted=1');
exit;
