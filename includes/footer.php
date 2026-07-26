<?php
// Determine the base URL for scripts
$pathDepth = substr_count($_SERVER['PHP_SELF'], '/') - 2;
$basePath = str_repeat('../', $pathDepth);
if($pathDepth < 0) $basePath = '';
?>
<footer class="main-footer">
<strong>SafeTrack HSE V3</strong>
</footer>
</div>
<script src="<?= $basePath ?>assets/adminlte/plugins/jquery/jquery.min.js"></script>
<script src="<?= $basePath ?>assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= $basePath ?>assets/adminlte/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
