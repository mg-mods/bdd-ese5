<?php
header('Content-Type: application/json');

// --- 1. Inclusion du fichier de configuration (Utilise $pdo, $serveur, $db_user, etc.) ---
// ASSUREZ-VOUS QUE config.php SE TROUVE DANS LE MÊME DOSSIER QUE CETTE API
include 'config.php';

// --- 2. Vérification et récupération des données POST ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['tutor_id'])) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Requête invalide ou ID de tuteur manquant."]);
    exit();
}

$tutor_id = filter_var($_POST['tutor_id'], FILTER_SANITIZE_NUMBER_INT);

if (empty($tutor_id)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "ID de tuteur non valide."]);
    exit();
}

try {
    // La variable $pdo est maintenant disponible grâce à l'inclusion de config.php

    $pdo->beginTransaction();

    // A. DÉSAFFECTATION DES ÉTUDIANTS DANS 'conventions'
    $sql_conventions = "UPDATE conventions SET id_tuteur = NULL, statut = 'En_attente_de_tuteur' WHERE id_tuteur = ?";
    $stmt_conventions = $pdo->prepare($sql_conventions);
    $stmt_conventions->execute([$tutor_id]);
    $conventions_updated = $stmt_conventions->rowCount();

    // B. DÉSACTIVATION DU RÔLE DE TUTEUR DANS 'professeurs' (est_tuteur = 0)
    // NOTE : On met aussi nb_etudiant à 0 pour être sûr que le compteur est remis à zéro.
    $sql_professeur = "UPDATE professeurs SET est_tuteur = 0, nb_etudiant = 0 WHERE id = ?";
    $stmt_professeur = $pdo->prepare($sql_professeur);
    $stmt_professeur->execute([$tutor_id]);
    
    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "Tuteur désactivé et $conventions_updated conventions mises à jour.",
        "conventions_updated" => $conventions_updated
    ]);

} catch (\Exception $e) {
    // Si une erreur survient (y compris l'échec de l'UPDATE si les colonnes n'existent pas)
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Échec de l'opération en base de données : " . $e->getMessage()]);
}

?>