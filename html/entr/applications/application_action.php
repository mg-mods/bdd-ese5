<?php
session_start();

$pdo = new PDO("mysql:host=localhost;dbname=Master;charset=utf8", "admin", "admin");

$id = (int)$_POST['id'];
$action = $_POST['action'];

$statusMap = [
    "open"    => 1,
    "reject"  => 3,
    "contact" => 4,
    "accept"  => 5
];

if (!isset($statusMap[$action])) exit;

$sql = "UPDATE Applications
        SET ApplicationStatus = {$statusMap[$action]},
            ApplicationCreationDate = IF($statusMap[$action]=3, NOW(), ApplicationCreationDate)
        WHERE ApplicationID = $id";

$pdo->exec($sql);
