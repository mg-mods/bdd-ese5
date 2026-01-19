<?php

/**
 * CONFIGURATION ET ADAPTATION BDD (À modifier pour la vrai BDD car le code est adapté 0 celle de test) :
 * * Tables concernées : table des conventions et table des professeurs
 * - "conventions"  : Nom de la table qui stocke les informations sur les conventions
 * - "professeurs"  : Nom de la table qui stocke les informations sur les enseignants
 * * Colonnes concernées :
 * - "id_tuteur"    : La clé étrangère (dans conventions) qui lie un dossier à un prof
 * - "statut"       : La colonne qui gère l'état des conventions (ex: 'Validé', 'En attente')
 * - "id"           : La clé primaire (ID unique) de la convention
 * - "nb_etudiant"  : La colonne qui compte le nombre d'étudiant attribuée dans la table professeurs
 * - "est_tuteur"   : Le flag (1/0) qui définit si le prof est un tuteur
 */

header('Content-Type: application/json');
// Import de la connexion à la base de données ($pdo)
require_once 'config.php'; 

// Récupération et validation des données POST
// Ce qu'il faut changer pour s'adapté a la vrai BDD : pensée à modifier les clés entre crochets 
$convention_id = $_POST['convention_id'] ?? 0;
$tutor_id = $_POST['tutor_id'] ?? 0;

// On vérifie que les ID sont bien des nombres et supérieurs à 0
if (!is_numeric($convention_id) || $convention_id <= 0 || !is_numeric($tutor_id) || $tutor_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de Convention ou Tuteur manquant ou invalide.']);
    exit;
}

try {
    $pdo->beginTransaction(); 

    // 1. Attribution du Tuteur et mise à jour du Statut de la Convention




    $sql_update_convention = "UPDATE conventions 
                              SET id_tuteur = :tutor_id, 
                                  statut = 'Validé'      
                              WHERE id = :convention_id";
    
    $stmt_conv = $pdo->prepare($sql_update_convention);
    $stmt_conv->execute([':tutor_id' => $tutor_id, ':convention_id' => $convention_id]);

    // 2. SYNCHRONISATION GLOBALE DES COMPTEURS 
    // Cette étape assure que TOUS les compteurs 'nb_etudiant' sont mis à jour
    // en fonction de l'état actuel de la table 'conventions'.

    // Requête 2.1: Mise à jour des compteurs pour les tuteurs ayant au moins un étudiant
    $sql_bulk_update = "
        UPDATE professeurs AS p
        JOIN (
            SELECT id_tuteur, COUNT(id) AS total_etudiants
            FROM conventions
            WHERE id_tuteur IS NOT NULL
            GROUP BY id_tuteur
        ) AS c ON p.id = c.id_tuteur
        SET p.nb_etudiant = c.total_etudiants
        WHERE p.est_tuteur = 1; -- S'assurer de ne mettre à jour que les tuteurs actifs
    ";
    $pdo->exec($sql_bulk_update);


    // Requête 2.2: Mise à jour à 0 pour les tuteurs qui n'ont plus d'étudiants
    // (Cette requête est importante pour réinitialiser les compteurs des tuteurs dont le dernier étudiant a été réattribué)
    $sql_reset_zero = "
        UPDATE professeurs
        SET nb_etudiant = 0
        WHERE est_tuteur = 1 AND id NOT IN (
            SELECT id_tuteur FROM conventions WHERE id_tuteur IS NOT NULL
        );
    ";
    $pdo->exec($sql_reset_zero);
    
    // --- FIN SYNCHRONISATION ---

    $pdo->commit(); 

    echo json_encode(['success' => true, 'message' => 'Tuteur attribué, statut mis à jour, et tous les compteurs d\'étudiants ont été synchronisés.']);

} catch (PDOException $e) {
    // En cas d'erreur, on annule tout ce qui a été fait dans la transaction (Rollback)
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    // On note l'erreur précise dans le fichier log du serveur pour le développeur
    error_log("Erreur PDO dans api_assign_tutor.php: " . $e->getMessage());
    // On renvoie un message d'erreur à l'utilisateur pour ne pas exposer la structure de la BDD
    echo json_encode(['success' => false, 'message' => 'Une erreur interne de la base de données est survenue.']);
}
?>