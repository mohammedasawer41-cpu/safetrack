<?php
require_once '../../config/database.php';
require_once '../../config/auth.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $anomaly_id = (int)($_POST['anomaly_id'] ?? 0);
    $action_required = trim($_POST['action_required'] ?? '');
    $assigned_to = (int)($_POST['assigned_to'] ?? 0);
    $target_date = trim($_POST['target_date'] ?? '');
    
    if (!$anomaly_id) {
        $error = 'Anomaly is required.';
    } elseif (empty($action_required)) {
        $error = 'Action required is required.';
    } else {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO corrective_actions (anomaly_id, action_required, assigned_to, target_date, status) 
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $anomaly_id,
                $action_required,
                $assigned_to ?: null,
                $target_date ?: null,
                'Open'
            ]);
            $action_id = $pdo->lastInsertId();
            header("Location: view.php?id=$action_id&created=1");
            exit;
        } catch (Exception $e) {
            $error = 'Error creating action: ' . $e->getMessage();
        }
    }
}

$anomalies = $pdo->query("SELECT a.id, a.title FROM anomalies a WHERE a.status='Open' ORDER BY a.reported_date DESC")->fetchAll(PDO::FETCH_ASSOC);
$users = $pdo->query("SELECT id, fullname FROM users WHERE status='Active' ORDER BY fullname")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1>Create CAPA Action</h1></div>
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
<div class="card-header"><h3 class="card-title">Action Information</h3></div>
<form method="POST">
<div class="card-body">
<div class="form-group mb-3">
<label>Anomaly *</label>
<select name="anomaly_id" class="form-control" required>
<option value="">-- Select Anomaly --</option>
<?php foreach ($anomalies as $anom): ?>
<option value="<?= $anom['id'] ?>"><?= htmlspecialchars($anom['title']) ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="form-group mb-3">
<label>Action Required *</label>
<textarea name="action_required" rows="4" class="form-control" placeholder="Describe the corrective/preventive action" required></textarea>
</div>
<div class="form-group mb-3">
<label>Assigned To</label>
<select name="assigned_to" class="form-control">
<option value="">-- Select User --</option>
<?php foreach ($users as $user): ?>
<option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['fullname']) ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="form-group mb-3">
<label>Target Date</label>
<input type="date" name="target_date" class="form-control">
</div>
</div>
<div class="card-footer">
<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create Action</button>
<a href="index.php" class="btn btn-secondary">Cancel</a>
</div>
</form>
</div>

</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>