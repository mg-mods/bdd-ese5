<?php
$pdo = new PDO("mysql:host=localhost;dbname=Master;charset=utf8", "admin", "admin");

$sql = "SELECT ApplicationID, ApplicationFilePath
        FROM Applications
        WHERE ApplicationStatus = 3
          AND ApplicationCreationDate < DATE_SUB(NOW(), INTERVAL 12 WEEK)";

$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $r) {
    if (file_exists($r['ApplicationFilePath'])) {
        unlink($r['ApplicationFilePath']);
    }
    $pdo->exec("DELETE FROM Applications WHERE ApplicationID = {$r['ApplicationID']}");
}
