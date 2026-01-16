<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Content-Type: application/json");

    $id = $_POST['loginInput'] ?? "";
    $pw = $_POST['passwordInput'] ?? "";

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=Master;charset=utf8", "admin", "admin");

        $sql = file_get_contents("getLogin.SQL");

        $sql = str_replace("{{ loginInput.value }}", $pdo->quote($id), $sql);
        $sql = str_replace("{{ passwordInput.value }}", $pdo->quote($pw), $sql);

        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && isset($row["UnivID"], $row["Pass"])) {
            session_start();
            $_SESSION['sessionToken'] = $row["Token"];
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['loginType'] = "stud";
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false]);
    }
    exit;
}
?>
