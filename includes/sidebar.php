<?php
// Determine the base URL
$pathDepth = substr_count($_SERVER['PHP_SELF'], '/') - 2;
$basePath = str_repeat('../', $pathDepth);
if($pathDepth < 0) $basePath = '';
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
<a href="<?= $basePath ?>dashboard.php" class="brand-link">
<span class="brand-text">
SafeTrack HSE
</span>
</a>
<div class="sidebar">
<nav>
<ul class="nav nav-pills nav-sidebar flex-column">
<li class="nav-item">
<a href="<?= $basePath ?>dashboard.php" class="nav-link">
<i class="nav-icon fas fa-home"></i>
<p>Dashboard</p>
</a>
</li>
<li class="nav-item">
<a href="<?= $basePath ?>modules/planning/monthly.php" class="nav-link">
<i class="nav-icon fas fa-calendar-alt"></i>
<p>Inspection Planning</p>
</a>
</li>
<li class="nav-item">
<a href="<?= $basePath ?>modules/inspections/index.php" class="nav-link">
<i class="nav-icon fas fa-clipboard-check"></i>
<p>Inspections</p>
</a>
</li>
<li class="nav-item">
<a href="<?= $basePath ?>modules/templates/index.php" class="nav-link">
<i class="nav-icon fas fa-list-check"></i>
<p>Checklist Templates</p>
</a>
</li>
<li class="nav-item">
<a href="<?= $basePath ?>modules/anomalies/index.php" class="nav-link">
<i class="nav-icon fas fa-exclamation-triangle"></i>
<p>Anomalies</p>
</a>
</li>
<li class="nav-item">
<a href="<?= $basePath ?>modules/actions/index.php" class="nav-link">
<i class="nav-icon fas fa-tasks"></i>
<p>CAPA</p>
</a>
</li>
<li class="nav-item">
<a href="<?= $basePath ?>modules/reports/index.php" class="nav-link">
<i class="nav-icon fas fa-chart-bar"></i>
<p>Reports</p>
</a>
</li>
<li class="nav-item">
<a href="<?= $basePath ?>modules/users/index.php" class="nav-link">
<i class="nav-icon fas fa-users"></i>
<p>Users</p>
</a>
</li>
</ul>
</nav>
</div>
</aside>
