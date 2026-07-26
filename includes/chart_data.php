<?php

require_once __DIR__.'/../config/database.php';

$result = $pdo->query("
SELECT
MONTH(inspection_date) m,
COUNT(*) total
FROM inspections
GROUP BY MONTH(inspection_date)
ORDER BY MONTH(inspection_date)
");

$months=[];
$totals=[];

while($row=$result->fetch(PDO::FETCH_ASSOC))
{
    $months[]=$row['m'];
    $totals[]=$row['total'];
}
