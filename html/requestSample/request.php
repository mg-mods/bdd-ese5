<?php

    // The params from the JS request
    $paramName = $_POST['paramName'] ?? ""; 

    try {

        $sql = file_get_contents("request.SQL"); // SQL file for the request
        // Register parameters, duplicate if needed
        $sql = str_replace(
            "{{ paramName.value }}", // Param name in SQL request
            $pdo->quote($paramName), // Param name in PHP request
            $sql
        );

        // ***********************************
        // DO NOT EDIT BELLOW
        // ***********************************
        $pdo = new PDO("mysql:host=localhost;dbname=Master;charset=utf8", "admin", "admin");
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // ***********************************
        // END
        // ***********************************

        // Response if success
        echo json_encode([
            "success" => true, // Add response parameters bellow
            "sqlRow" => $row['sqlRow']
        ]);
    } catch (Exception $e) {
        // Response if failed
        echo json_encode([
            "success" => true, // Add response parameters bellow, but they should be null
            "sqlRow" => null
        ]);
    }
?>