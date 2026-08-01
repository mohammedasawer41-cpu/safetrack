<?php
require_once '../../config/database.php';
require_once '../../config/auth.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $severity = trim($_POST['severity'] ?? '');
    $reported_date = trim($_POST['reported_date'] ?? date('Y-m-d'));
    
    if (empty($title)) {
        $error = 'Title is required.';
    } elseif (empty($severity)) {
        $error = 'Severity is required.';
    } else {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO anomalies (title, description, severity, status, reported_by, reported_date, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, NOW())"
            );
            $stmt->execute([
                $title,
                $description,
                $severity,
                'Open',
                $_SESSION['user_id'],
                $reported_date
            ]);
            $anomaly_id = $pdo->lastInsertId();
            header("Location: view.php?id=$anomaly_id&created=1");
            exit;
        } catch (Exception $e) {
            $error = 'Error creating anomaly: ' . $e->getMessage();
        }
    }
}
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1>Create Anomaly</h1></div>
<div class="col-sm-6 text-end"><a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">

<?php if ($error): ?>
<div class="alert alert-danger alert-dismissible fade show">
<i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card card-primary">
<div class="card-header"><h3 class="card-title">Anomaly Information</h3></div>
<form method="POST">
<div class="card-body">
<div class="form-group mb-3">
<label>Title *</label>
<input type="text" name="title" class="form-control" placeholder="Brief anomaly description" required>
</div>
<div class="form-group mb-3">
<label>Description</label>
<textarea name="description" rows="4" class="form-control" placeholder="Detailed description of the anomaly"></textarea>
</div>
<div class="form-group mb-3">
<label>Severity *</label>
<select name="severity" class="form-control" required>
<option value="">-- Select Severity --</option>
<option value="Low">Low</option>
<option value="Medium">Medium</option>
<option value="High">High</option>
<option value="Critical">Critical</option>
</select>
</div>
<div class="form-group mb-3">
<label>Reported Date</label>
<input type="date" name="reported_date" value="<?= date('Y-m-d') ?>" class="form-control">
</div>
</div>
<div class="card-footer">
<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create Anomaly</button>
<a href="index.php" class="btn btn-secondary">Cancel</a>
</div>
</form>
</div>

</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>