<?php
// Fichier : api_set_tuteur.php
header('Content-Type: application/json');
require_once 'config.php';

$prof_id = $_POST['id'] ?? 0;

if (empty($prof_id) || !is_numeric($prof_id)) {
    echo json_encode(['success' => false, 'message' => 'ID de professeur manquant.']);
    exit;
}

try {
    // On met 'est_tuteur' à 1 et 'nb_etudiant' à 0 (nouvellement tuteur)
    $sql = "UPDATE professeurs SET est_tuteur = 1, nb_etudiant = 0 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([':id' => $prof_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Statut Tuteur mis à jour avec succès.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Professeur non trouvé ou déjà Tuteur.']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur BDD: ' . $e->getMessage()]);
}
?>