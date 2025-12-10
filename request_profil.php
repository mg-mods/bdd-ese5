<?php
session_start();

// 1. VÉRIFICATION DE LA SESSION
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true || $_SESSION['loginType'] !== "stud") {
    // Si l'utilisateur n'est pas connecté ou n'est pas un étudiant, on renvoie une erreur JSON.
    // NOTE: Si le fichier est appelé directement, la redirection originale peut être conservée.
    // Si c'est un appel AJAX, on renvoie une erreur pour que le JS puisse la gérer.
    http_response_code(401); // Unauthorized
    echo json_encode(["success" => false, "message" => "Non autorisé. Session expirée ou invalide."]);
    exit;
}

try {
    $sessionToken = $_SESSION['sessionToken'];

    // 2. CONNEXION À LA BASE DE DONNÉES
    $pdo = new PDO("mysql:host=localhost;dbname=Master;charset=utf8", "admin", "admin");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Activation des exceptions PDO

    // 3. PRÉPARATION DE LA REQUÊTE SQL (avec le fichier getUserInfo.SQL)
    $sql = file_get_contents("getUserInfo.SQL"); // Assurez-vous que getUserInfo.SQL est dans le même dossier

    // Sécurisation et remplacement du placeholder
    $sql = str_replace("{{ hashInput.value }}", $pdo->quote($sessionToken), $sql);

    // 4. EXÉCUTION DE LA REQUÊTE
    $stmt = $pdo->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // 5. ENVOI DE LA RÉPONSE JSON AVEC LES DONNÉES
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
            // Remarque : 'Token' n'est pas inclus pour la sécurité
        ]);
    } else {
        // Token de session valide mais aucune donnée trouvée dans la BDD
        echo json_encode(["success" => false, "message" => "Aucun étudiant trouvé avec ce jeton de session."]);
    }


} catch (Exception $e) {
    // Gestion des erreurs (DB ou autres)
    http_response_code(500); // Internal Server Error
    error_log("DB Error: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Erreur interne du serveur."]);
}

?>