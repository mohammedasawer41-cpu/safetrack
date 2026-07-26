<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) die('Invalid template.');

$stmt = $pdo->prepare("SELECT * FROM checklist_templates WHERE id=?");
$stmt->execute([$id]);
$template = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$template) die('Template not found.');

$q_stmt = $pdo->prepare("SELECT * FROM checklist_questions WHERE template_id=? ORDER BY question_order ASC");
$q_stmt->execute([$id]);
$questions = $q_stmt->fetchAll(PDO::FETCH_ASSOC);

$created = isset($_GET['created']);
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1><?= htmlspecialchars($template['template_name']) ?></h1></div>
<div class="col-sm-6 text-end"><a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">

<?php if ($created): ?>
<div class="alert alert-success alert-dismissible fade show">
<i class="fas fa-check-circle"></i> Template created successfully!
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card card-primary">
<div class="card-header"><h3 class="card-title">Template Information</h3></div>
<div class="card-body">
<table class="table table-bordered">
<tr><th width="200">Name</th><td><?= htmlspecialchars($template['template_name']) ?></td></tr>
<tr><th>Description</th><td><?= htmlspecialchars($template['description'] ?? '-') ?></td></tr>
<tr><th>Created</th><td><?= date('d/m/Y H:i', strtotime($template['created_at'])) ?></td></tr>
<tr><th>Total Questions</th><td><span class="badge bg-info"><?= count($questions) ?></span></td></tr>
</table>
</div>
<div class="card-footer">
<a href="edit.php?id=<?= $template['id'] ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
<a href="delete.php?id=<?= $template['id'] ?>" class="btn btn-danger" onclick="return confirm('Delete this template?')"><i class="fas fa-trash"></i> Delete</a>
</div>
</div>

<div class="card card-primary mt-3">
<div class="card-header">
<h3 class="card-title">Checklist Questions (<?= count($questions) ?>)</h3>
<div class="card-tools">
<a href="edit.php?id=<?= $template['id'] ?>" class="btn btn-sm btn-info"><i class="fas fa-edit"></i> Add/Edit Questions</a>
</div>
</div>
<div class="card-body">
<?php if (count($questions) > 0): ?>
<ol class="list-group">
<?php foreach ($questions as $q): ?>
<li class="list-group-item"><?= htmlspecialchars($q['question']) ?></li>
<?php endforeach; ?>
</ol>
<?php else: ?>
<p class="text-muted">No questions added yet. <a href="edit.php?id=<?= $template['id'] ?>">Add questions</a>.</p>
<?php endif; ?>
</div>
</div>

</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>