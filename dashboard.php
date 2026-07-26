<?php
require_once 'config/database.php';
require_once 'config/auth.php';
include 'includes/header.php';
include 'includes/dashboard_data.php';
include 'includes/chart_data.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';
?>

<div class="content-wrapper">

    <!-- Page Header -->
    <section class="content-header">
        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div>

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="dashboard.php">Home</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Dashboard
                        </li>
                    </ol>

                </div>

            </div>

        </div>
    </section>

    <!-- Main Content -->
    <section class="content">

        <div class="container-fluid">

            <!-- KPI Cards -->
            <div class="row">

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-primary">

                        <div class="inner">

                            <h3><?= $totalInspections ?></h3>

                            <p>Total Inspections</p>

                        </div>

                        <div class="icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>

                    </div>

                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-warning">

                        <div class="inner">

                            <h3><?= $openAnomalies ?></h3>

                            <p>Open Anomalies</p>

                        </div>

                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>

                    </div>

                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-success">

                        <div class="inner">

                            <h3><?= $completedCAPA ?></h3>

                            <p>Completed CAPA</p>

                        </div>

                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>

                    </div>

                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-info">

                        <div class="inner">

                            <h3><?= $compliance ?>%</h3>

                            <p>Compliance</p>

                        </div>

                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>

                    </div>

                </div>

            </div>

            <?php
            $recent = $pdo->query("
                SELECT inspection_no, inspection_date, status
                FROM inspections
                ORDER BY id DESC
                LIMIT 5
            ");

            $recentHazards = $pdo->query("
                SELECT title, severity, status
                FROM anomalies
                ORDER BY id DESC
                LIMIT 5
            ");
            ?>

            <!-- Tables -->
            <div class="row">

                <!-- Recent Inspections -->
                <div class="col-lg-6">

                    <div class="card">

                        <div class="card-header">

                            <h3 class="card-title">
                                Recent Inspections
                            </h3>

                        </div>

                        <div class="card-body table-responsive p-0">

                            <table class="table table-hover">

                                <thead>

                                <tr>
                                    <th>No</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>

                                </thead>

                                <tbody>

                                <?php if($recent->rowCount()): ?>

                                    <?php while($row=$recent->fetch(PDO::FETCH_ASSOC)): ?>

                                    <tr>

                                        <td><?= htmlspecialchars($row['inspection_no']) ?></td>

                                        <td><?= htmlspecialchars($row['inspection_date']) ?></td>

                                        <td>

                                            <?php
                                            $badge='secondary';

                                            if($row['status']=='Completed')
                                                $badge='success';

                                            if($row['status']=='Draft')
                                                $badge='warning';
                                            ?>

                                            <span class="badge bg-<?= $badge ?>">
                                                <?= htmlspecialchars($row['status']) ?>
                                            </span>

                                        </td>

                                    </tr>

                                    <?php endwhile; ?>

                                <?php else: ?>

                                    <tr>

                                        <td colspan="3" class="text-center">
                                            No inspections found.
                                        </td>

                                    </tr>

                                <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

                <!-- Latest Anomalies -->
                <div class="col-lg-6">

                    <div class="card">

                        <div class="card-header">

                            <h3 class="card-title">
                                Latest Anomalies
                            </h3>

                        </div>

                        <div class="card-body table-responsive p-0">

                            <table class="table table-hover">

                                <thead>

                                <tr>

                                    <th>Title</th>

                                    <th>Severity</th>

                                    <th>Status</th>

                                </tr>

                                </thead>

                                <tbody>

                                <?php if($recentHazards->rowCount()): ?>

                                    <?php while($row=$recentHazards->fetch(PDO::FETCH_ASSOC)): ?>

                                    <?php

                                    switch($row['severity']){

                                        case 'Low':
                                            $color='success';
                                            break;

                                        case 'Medium':
                                            $color='warning';
                                            break;

                                        case 'High':
                                            $color='danger';
                                            break;

                                        case 'Critical':
                                            $color='dark';
                                            break;

                                        default:
                                            $color='secondary';
                                    }

                                    ?>

                                    <tr>

                                        <td><?= htmlspecialchars($row['title']) ?></td>

                                        <td>

                                            <span class="badge bg-<?= $color ?>">
                                                <?= htmlspecialchars($row['severity']) ?>
                                            </span>

                                        </td>

                                        <td><?= htmlspecialchars($row['status']) ?></td>

                                    </tr>

                                    <?php endwhile; ?>

                                <?php else: ?>

                                    <tr>

                                        <td colspan="3" class="text-center">
                                            No anomalies found.
                                        </td>

                                    </tr>

                                <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Chart -->
            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header">

                            <h3 class="card-title">
                                Monthly Inspection Trend
                            </h3>

                        </div>

                        <div class="card-body">

                            <canvas id="inspectionChart" height="90"></canvas>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const ctx = document.getElementById('inspectionChart');

    if(ctx){

        new Chart(ctx,{

            type:'bar',

            data:{

                labels:<?= json_encode($months) ?>,

                datasets:[{

                    label:'Inspections',

                    data:<?= json_encode($totals) ?>,

                    backgroundColor:'#007bff',

                    borderRadius:5

                }]

            },

            options:{

                responsive:true,

                maintainAspectRatio:false,

                scales:{
                    y:{
                        beginAtZero:true,
                        ticks:{
                            precision:0
                        }
                    }
                }

            }

        });

    }

});

</script>

<?php include 'includes/footer.php'; ?>
