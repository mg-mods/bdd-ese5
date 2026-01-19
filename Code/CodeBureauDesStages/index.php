<?php
session_start();

if ((!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) && $_SESSION['loginType'] == "stud") {
    header("Location: /cas?service=http://192.168.56.101/testStud/");
    exit;
}

try {
    $sessionToken = $_SESSION['sessionToken'];

    $pdo = new PDO("mysql:host=localhost;dbname=Master;charset=utf8", "admin", "admin");

    $sql = file_get_contents("getUserInfo.SQL");

    $sql = str_replace("{{ hashInput.value }}", $pdo->quote($sessionToken), $sql);

    $stmt = $pdo->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,

    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "StudentLastName" => null,
         ]);
}


?>