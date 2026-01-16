<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Content-Type: application/json");

    // The params from the JS request
    $searchValue = $_POST['searchValue'] ?? ""; 

    try {

        $pdo = new PDO("mysql:host=localhost;dbname=Master;charset=utf8", "admin", "admin");
        
        $sql = file_get_contents("search.SQL"); // SQL file for the request
        // Register parameters, duplicate if needed
        $sql = str_replace(
            "{{ searchValue.value }}", // Param name in SQL request
            $pdo->quote($searchValue), // Param name in PHP request
            $sql
        );

        // ***********************************
        // DO NOT EDIT BELLOW
        // ***********************************
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // ***********************************
        // END
        // ***********************************

        // Response if success
        echo json_encode([
            "success" => true, // Add response parameters bellow
            "ContractID" => $row["ContractID"],
            "ContractTitle" => $row["ContractTitle"],
            "ContractFileName" => $row["ContractFileName"],
            "ContractFilePath" => $row["ContractFilePath"],
            "ContractStartDate" => $row["ContractStartDate"],
            "ContractEndDate" => $row["ContractEndDate"],
            "ContractCreationDate" => $row["ContractCreationDate"],
            "StudentName" => $row["StudentName"],
            "StudentCourse" => $row["StudentCourse"],
            "StudentStartYear" => $row["StudentStartYear"],
            "TeacherName" => $row["TeacherName"],
            "TutorName" => $row["TutorName"],
            "TutorPosition" => $row["TutorPosition"],
            "CompanyName" => $row["CompanyName"],
            "CompanyDomain" => $row["CompanyDomain"],
            "CompanyCity" => $row["CompanyCity"],
            "CompanyPostal" => $row["CompanyPostal"],
            "CompanyAddress" => $row["CompanyAddress"]              
        ]);
    } catch (Exception $e) {
        // Response if failed
        echo json_encode([
            "success" => false, // Add response parameters bellow, but they should be null
            "ContractID" => null,
            "ContractTitle" => null,
            "ContractFileName" => null,
            "ContractFilePath" => null,
            "ContractStartDate" => null,
            "ContractEndDate" => null,
            "ContractCreationDate" => null,
            "StudentName" => null,
            "StudentCourse" => null,
            "StudentStartYear" => null,
            "TeacherName" => null,
            "TutorName" => null,
            "TutorPosition" => null,
            "CompanyName" => null,
            "CompanyDomain" => null,
            "CompanyCity" => null,
            "CompanyPostal" => null,
            "CompanyAddress" => null              
        ]);
    }
    exit;
}
?>