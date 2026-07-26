<?php
require_once '../../config/database.php';
require_once '../../config/auth.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) die('Invalid schedule.');

$stmt = $pdo->prepare("SELECT * FROM inspection_schedule WHERE id=?");
$stmt->execute([$id]);
$schedule = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$schedule) die('Schedule not found.');

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inspection_date = trim($_POST['inspection_date'] ?? '');
    $inspector_id = (int)($_POST['inspector_id'] ?? 0);
    $template_id = (int)($_POST['template_id'] ?? 0);
    $site_id = (int)($_POST['site_id'] ?? 0);
    $location = trim($_POST['location'] ?? '');
    $remarks = trim($_POST['remarks'] ?? '');
    
    if (empty($inspection_date)) {
        $error = 'Inspection date is required.';
    } else {
        try {
            $upd_stmt = $pdo->prepare(
                "UPDATE inspection_schedule SET inspection_date=?, inspector_id=?, template_id=?, site_id=?, location=?, remarks=? WHERE id=?"
            );
            $upd_stmt->execute([
                $inspection_date,
                $inspector_id ?: null,
                $template_id ?: null,
                $site_id ?: null,
                $location,
                $remarks,
                $id
            ]);
            $message = 'Schedule updated successfully!';
            $stmt->execute([$id]);
            $schedule = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $error = 'Error updating schedule: ' . $e->getMessage();
        }
    }
}

$inspectors = $pdo->query("SELECT id, fullname FROM users WHERE role_id=3 AND status='Active' ORDER BY fullname")->fetchAll(PDO::FETCH_ASSOC);
$templates = $pdo->query("SELECT id, template_name FROM checklist_templates ORDER BY template_name")->fetchAll(PDO::FETCH_ASSOC);
$sites = $pdo->query("SELECT id, site_name FROM sites ORDER BY site_name")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1>Edit Inspection Schedule</h1></div>
<div class="col-sm-6 text-end"><a href="schedule_details.php?id=<?= $id ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
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
<div class="card-header"><h3 class="card-title">Schedule Details</h3></div>
<form method="POST">
<div class="card-body">
<div class="form-group mb-3">
<label>Inspection Date</label>
<input type="date" name="inspection_date" value="<?= htmlspecialchars($schedule['inspection_date']) ?>" class="form-control" required>
</div>
<div class="form-group mb-3">
<label>Inspector</label>
<select name="inspector_id" class="form-control">
<option value="">-- Select Inspector --</option>
<?php foreach ($inspectors as $insp): $sel = $schedule['inspector_id'] == $insp['id'] ? 'selected' : ''; ?>
<option value="<?= $insp['id'] ?>" <?= $sel ?>><?= htmlspecialchars($insp['fullname']) ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="form-group mb-3">
<label>Checklist Template</label>
<select name="template_id" class="form-control">
<option value="">-- Select Template --</option>
<?php foreach ($templates as $tmpl): $sel = $schedule['template_id'] == $tmpl['id'] ? 'selected' : ''; ?>
<option value="<?= $tmpl['id'] ?>" <?= $sel ?>><?= htmlspecialchars($tmpl['template_name']) ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="form-group mb-3">
<label>Site</label>
<select name="site_id" class="form-control">
<option value="">-- Select Site --</option>
<?php foreach ($sites as $site): $sel = $schedule['site_id'] == $site['id'] ? 'selected' : ''; ?>
<option value="<?= $site['id'] ?>" <?= $sel ?>><?= htmlspecialchars($site['site_name']) ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="form-group mb-3">
<label>Location</label>
<input type="text" name="location" value="<?= htmlspecialchars($schedule['location']) ?>" class="form-control">
</div>
<div class="form-group mb-3">
<label>Remarks</label>
<textarea name="remarks" rows="3" class="form-control"><?= htmlspecialchars($schedule['remarks'] ?? '') ?></textarea>
</div>
</div>
<div class="card-footer">
<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
<a href="schedule_details.php?id=<?= $id ?>" class="btn btn-secondary">Cancel</a>
</div>
</form>
</div>

</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>