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

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $template_name = trim($_POST['template_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $new_questions = $_POST['questions'] ?? [];
    $existing_ids = $_POST['question_ids'] ?? [];
    
    if (empty($template_name)) {
        $error = 'Template name is required.';
    } else {
        try {
            $pdo->beginTransaction();
            
            // Update template
            $upd_stmt = $pdo->prepare("UPDATE checklist_templates SET template_name=?, description=? WHERE id=?");
            $upd_stmt->execute([$template_name, $description, $id]);
            
            // Delete old questions
            $del_stmt = $pdo->prepare("DELETE FROM checklist_questions WHERE template_id=?");
            $del_stmt->execute([$id]);
            
            // Insert new questions
            $q_insert = $pdo->prepare("INSERT INTO checklist_questions (template_id, question, question_order) VALUES (?, ?, ?)");
            foreach ($new_questions as $order => $question) {
                $question = trim($question);
                if (!empty($question)) {
                    $q_insert->execute([$id, $question, $order + 1]);
                }
            }
            
            $pdo->commit();
            $message = 'Template updated successfully!';
            
            // Reload data
            $stmt->execute([$id]);
            $template = $stmt->fetch(PDO::FETCH_ASSOC);
            $q_stmt->execute([$id]);
            $questions = $q_stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Error updating template: ' . $e->getMessage();
        }
    }
}
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1>Edit Template</h1></div>
<div class="col-sm-6 text-end"><a href="view.php?id=<?= $id ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">

<?php if ($message): ?>
<div class="alert alert-success alert-dismissible fade show">
<i class="fas fa-check-circle"></i> <?= htmlspecialchars($message) ?>
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger alert-dismissible fade show">
<i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card card-primary">
<div class="card-header"><h3 class="card-title">Template Information</h3></div>
<form method="POST">
<div class="card-body">
<div class="form-group mb-3">
<label>Template Name *</label>
<input type="text" name="template_name" class="form-control" value="<?= htmlspecialchars($template['template_name']) ?>" required>
</div>
<div class="form-group mb-3">
<label>Description</label>
<textarea name="description" rows="3" class="form-control"><?= htmlspecialchars($template['description'] ?? '') ?></textarea>
</div>
</div>
</form>
</div>

<div class="card card-primary mt-3">
<div class="card-header">
<h3 class="card-title">Checklist Questions</h3>
<div class="card-tools">
<button type="button" class="btn btn-sm btn-info" id="addQuestion"><i class="fas fa-plus"></i> Add Question</button>
</div>
</div>
<form method="POST">
<input type="hidden" name="template_name" value="<?= htmlspecialchars($template['template_name']) ?>">
<input type="hidden" name="description" value="<?= htmlspecialchars($template['description'] ?? '') ?>">
<div class="card-body">
<div id="questionsContainer">
<?php foreach ($questions as $idx => $q): ?>
<div class="question-row mb-2">
<div class="input-group">
<span class="input-group-text">Q<?= $idx + 1 ?></span>
<input type="hidden" name="question_ids[]" value="<?= $q['id'] ?>">
<input type="text" name="questions[]" class="form-control" value="<?= htmlspecialchars($q['question']) ?>">
<button type="button" class="btn btn-outline-danger removeQuestion" <?= count($questions) <= 1 ? 'style="display:none;"' : '' ?>><i class="fas fa-trash"></i></button>
</div>
</div>
<?php endforeach; ?>
</div>
</div>
<div class="card-footer">
<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
<a href="view.php?id=<?= $id ?>" class="btn btn-secondary">Cancel</a>
</div>
</form>
</div>

</div>
</section>
</div>

<script>
let questionCount = <?= count($questions) ?>;

document.getElementById('addQuestion').addEventListener('click', function() {
    const container = document.getElementById('questionsContainer');
    const newRow = document.createElement('div');
    newRow.className = 'question-row mb-2';
    newRow.innerHTML = `
        <div class="input-group">
            <span class="input-group-text">Q${++questionCount}</span>
            <input type="text" name="questions[]" class="form-control" placeholder="Enter question">
            <button type="button" class="btn btn-outline-danger removeQuestion"><i class="fas fa-trash"></i></button>
        </div>
    `;
    container.appendChild(newRow);
    updateRemoveButtons();
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.removeQuestion')) {
        e.preventDefault();
        e.target.closest('.question-row').remove();
        updateQuestionNumbers();
        updateRemoveButtons();
    }
});

function updateQuestionNumbers() {
    document.querySelectorAll('.question-row').forEach((row, idx) => {
        row.querySelector('.input-group-text').textContent = 'Q' + (idx + 1);
    });
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.question-row');
    rows.forEach((row, idx) => {
        row.querySelector('.removeQuestion').style.display = rows.length > 1 ? 'block' : 'none';
    });
}

updateRemoveButtons();
</script>

<?php include '../../includes/footer.php'; ?>