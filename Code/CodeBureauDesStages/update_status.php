<?php
require_once 'config.php';
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

// Vérifier si les données POST sont présentes
if (isset($_POST['id']) && isset($_POST['status'])) {
    $conventionId = (int)$_POST['id'];
    $newStatus = trim($_POST['status']);

    if ($conventionId > 0 && !empty($newStatus)) {
        try {
            $sql = "UPDATE conventions SET statut = :status WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':status' => $newStatus,
                ':id' => $conventionId
            ]);

            if ($stmt->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = 'Statut mis à jour avec succès.';
            } else {
                $response['message'] = 'Aucune modification effectuée (statut déjà le même ou ID non trouvé).';
            }

        } catch (PDOException $e) {
            $response['message'] = 'Erreur de base de données : ' . $e->getMessage();
        }
    } else {
        $response['message'] = 'ID ou statut invalide.';
    }
} else {
    $response['message'] = 'Données manquantes (ID ou statut).';
}

echo json_encode($response);
exit();
?>