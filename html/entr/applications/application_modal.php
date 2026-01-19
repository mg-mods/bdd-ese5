<?php
session_start();

$pdo = new PDO("mysql:host=localhost;dbname=Master;charset=utf8", "admin", "admin");

$id = (int)$_GET['id'];

$sql = file_get_contents("SQL/application_modal.sql");
$sql = str_replace("{{ApplicationID}}", $id, $sql);

$row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

echo json_encode(array_merge(["success" => true], $row));
