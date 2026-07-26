<?php
// Determine the base URL
$pathDepth = substr_count($_SERVER['PHP_SELF'], '/') - 2;
$basePath = str_repeat('../', $pathDepth);
if($pathDepth < 0) $basePath = '';
?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
<ul class="navbar-nav">
<li class="nav-item">
<a class="nav-link" data-widget="pushmenu" href="#">
<i class="fas fa-bars"></i>
</a>
</li>
</ul>
<ul class="navbar-nav ml-auto">
<li class="nav-item">
<span class="nav-link">
<?= isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : 'User' ?>
</span>
</li>
<li>
<a href="<?= $basePath ?>logout.php" class="nav-link">
Logout
</a>
</li>
</ul>
</nav>
