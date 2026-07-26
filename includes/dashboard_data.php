<?php
require_once __DIR__.'/../config/database.php';

/* Total Inspections */
$totalInspections = $pdo->query("SELECT COUNT(*) FROM inspections")->fetchColumn();

/* Open Anomalies */
$openAnomalies = $pdo->query("SELECT COUNT(*) FROM anomalies WHERE status='Open'")->fetchColumn();

/* Completed CAPA */
$completedCAPA = $pdo->query("SELECT COUNT(*) FROM corrective_actions WHERE status='Completed'")->fetchColumn();

/* Compliance */
$totalAnswers = $pdo->query("SELECT COUNT(*) FROM inspection_answers")->fetchColumn();
$yesAnswers = $pdo->query("SELECT COUNT(*) FROM inspection_answers WHERE answer='Yes'")->fetchColumn();
$compliance = 0;

if($totalAnswers > 0){
    $compliance = round(($yesAnswers/$totalAnswers)*100);
}
