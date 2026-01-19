<?php
// Fichier : api_tuteurs_students.php (Version Finale Corrigée)

header('Content-Type: application/json');
require_once 'config.php'; 

$tutor_id = $_GET['tutor_id'] ?? 0;
if (!is_numeric($tutor_id) || $tutor_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de tuteur manquant ou invalide.']);
    exit;
}

try {
    // *** CORRECTION MAJEURE : Utilisation d'une jointure (JOIN) ***
    $sql = "SELECT e.nom AS nom_etudiant, e.prenom AS prenom_etudiant, c.statut
            FROM conventions c
            JOIN eleves e ON c.eleve_id = e.id  -- Jointure sur l'ID de l'élève
            WHERE c.id_tuteur = :tutor_id 
            ORDER BY e.nom, e.prenom";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':tutor_id' => $tutor_id]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $students
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    $error_message = 'Erreur BDD (API Students): ' . $e->getMessage();
    echo json_encode([
        'success' => false, 
        'message' => $error_message,
        'tutor_id_received' => $tutor_id
    ]);
}
?>