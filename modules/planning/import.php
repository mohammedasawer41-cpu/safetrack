<?php
require_once '../../config/database.php';
include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    $filename = $_FILES['csv_file']['name'];
    
    if (!file_exists($file)) {
        $error = 'File upload failed.';
    } elseif (pathinfo($filename, PATHINFO_EXTENSION) !== 'csv') {
        $error = 'Please upload a CSV file.';
    } else {
        try {
            $pdo->beginTransaction();
            
            $handle = fopen($file, 'r');
            $header = fgetcsv($handle); // Skip header row
            $inserted = 0;
            $skipped = 0;
            
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < 4) continue;
                
                $inspection_date = $row[0];
                $inspector_name = trim($row[1]);
                $template_name = trim($row[2]);
                $status_label = trim($row[3]);
                $anomalies = isset($row[4]) ? trim($row[4]) : '';
                
                // Validate date
                if (!strtotime($inspection_date)) {
                    $skipped++;
                    continue;
                }
                
                // Map status: French labels to system states
                $status_map = [
                    'effectuée' => 'Completed',
                    'en cours' => 'In Progress',
                    'planifiée' => 'Planned',
                    '' => 'Planned'
                ];
                $status = $status_map[strtolower($status_label)] ?? 'Planned';
                
                // Skip if no inspector or template
                if (empty($inspector_name) || empty($template_name)) {
                    $skipped++;
                    continue;
                }
                
                // Get inspector ID
                $insp_stmt = $pdo->prepare("SELECT id FROM users WHERE fullname LIKE ? AND status='Active'");
                $insp_stmt->execute(["%$inspector_name%"]);
                $inspector_id = $insp_stmt->fetchColumn();
                
                if (!$inspector_id) {
                    $skipped++;
                    continue;
                }
                
                // Get template ID
                $tmpl_stmt = $pdo->prepare("SELECT id FROM checklist_templates WHERE template_name LIKE ?");
                $tmpl_stmt->execute(["%$template_name%"]);
                $template_id = $tmpl_stmt->fetchColumn();
                
                if (!$template_id) {
                    $skipped++;
                    continue;
                }
                
                // Get site ID (CCTV Facility)
                $site_stmt = $pdo->prepare("SELECT id FROM sites WHERE site_name LIKE '%CCTV%' LIMIT 1");
                $site_stmt->execute();
                $site_id = $site_stmt->fetchColumn();
                
                if (!$site_id) {
                    $skipped++;
                    continue;
                }
                
                // Insert inspection schedule
                $insert_stmt = $pdo->prepare(
                    "INSERT INTO inspection_schedule (inspection_date, inspector_id, template_id, site_id, location, status, remarks, created_at) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, NOW())"
                );
                $insert_stmt->execute([
                    $inspection_date,
                    $inspector_id,
                    $template_id,
                    $site_id,
                    'CCTV Facility',
                    $status,
                    $anomalies
                ]);
                $inserted++;
            }
            
            fclose($handle);
            $pdo->commit();
            $message = "Import successful! Inserted: $inserted, Skipped: $skipped";
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Import error: ' . $e->getMessage();
        }
    }
}
?>

<div class="content-wrapper">
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6"><h1><i class="fas fa-upload"></i> Bulk Import Schedules</h1></div>
<div class="col-sm-6 text-end"><a href="monthly.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
</div>
</section>
<section class="content">
<div class="container-fluid">

<?php if ($message): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="fas fa-check-circle"></i> <?= htmlspecialchars($message) ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card">
<div class="card-header"><h3 class="card-title">Import CSV File</h3></div>
<form method="POST" enctype="multipart/form-data">
<div class="card-body">
<div class="form-group mb-3">
<label>CSV File</label>
<input type="file" name="csv_file" class="form-control" accept=".csv" required>
<small class="text-muted">Expected columns: Date, Inspector, Checklist Template, Status, Anomalies</small>
</div>
<div class="alert alert-info">
<strong>CSV Format:</strong>
<pre>Date,Inspector,Checklist,Status,Anomalies
6/2/2026,Hatem Ben Brahim,Gerbeurs/Tracteurs,effectuée,
6/5/2026,Ahlem Lellahom,Zones de Stockage Extérieurs,effectuée,4 anomalies</pre>
<strong>Status values:</strong> effectuée (Completed), en cours (In Progress), planifiée (Planned), blank (Planned)
</div>
</div>
<div class="card-footer">
<button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Import</button>
<a href="monthly.php" class="btn btn-secondary">Cancel</a>
</div>
</form>
</div>

</div>
</section>
</div>
<?php include '../../includes/footer.php'; ?>