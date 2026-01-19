<?php
session_start();

if ($_SESSION['loginType'] !== "comp") exit;

$pdo = new PDO("mysql:host=localhost;dbname=Master;charset=utf8", "admin", "admin");

$companyHash = $_SESSION['sessionToken'];

$sql = file_get_contents("SQL/applications_list.sql");
$sql = str_replace("{{CompanyHash}}", $pdo->quote($companyHash), $sql);

$applications = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "success" => true,
    "applications" => $applications
]);
