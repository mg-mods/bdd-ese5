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
        "StudentID" => $row['StudentID'],
        "StudentUnivID" => $row['StudentUnivID'],
        "StudentLastName" => $row['StudentLastName'],
        "StudentFirstName" => $row['StudentFirstName'],
        "StudentGender" => $row['StudentGender'],
        "StudentIneCode" => $row['StudentIneCode'],
        "StudentEmail" => $row['StudentEmail'],
        "StudentPhone" => $row['StudentPhone'],
        "StudentPhoneIndicator" => $row['StudentPhoneIndicator'],
        "StudentCourse" => $row['StudentCourse'],
        "StudentStartYear" => $row['StudentStartYear']
    ]);


} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "StudentID" => null,
        "StudentUnivID" => null,
        "StudentLastName" => null,
        "StudentFirstName" => null,
        "StudentGender" => null,
        "StudentIneCode" => null,
        "StudentEmail" => null,
        "StudentPhone" => null,
        "StudentPhoneIndicator" => null,
        "StudentCourse" => null,
        "StudentStartYear" => null
    ]);
}


?>