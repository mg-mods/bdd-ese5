<?php

/**
 * CONFIGURATION ET ADAPTATION BDD (À modifier pour la vrai BDD car le code est adapté 0 celle de test) :
 * * Table concernée : 
 * - "professeurs" : table des professeurs
 * * Colonnes concernées :
 * - "est_tuteur"  : Le flag (1/0) qui définit si le prof est un tuteur
 * - "nom" : Nom du professeur.
 * - "prenom" : Prenom du professeur.
 * - "id" : La clé primaire
 */


// Retourne du HTML (les <li>)

header('Content-Type: text/html; charset=utf-8');
require_once 'config.php'; // La variable $pdo 

// Récupération de la recherche envoyée par le champ de texte
$search_name = $_GET['search_name'] ?? ''; 

$where_clauses = ["est_tuteur = 0"]; // <-- Filtre clé pour les non-tuteurs
$params = [];

// Si l'utilisateur a tapé quelque chose dans la barre de recherche
if (!empty($search_name)) {
    // Si l'utilisateur recherche, on cherche dans le nom ou prénom
    $where_clauses[] = "(nom LIKE :search OR prenom LIKE :search)";
    $params[':search'] = '%' . $search_name . '%'; // Le % permet de trouver le nom même si on ne tape qu'une partie (ex: "Du" trouvera "Dupont")
}

// On assemble les morceaux pour créer la clause WHERE finale
$where_sql = 'WHERE ' . implode(' AND ', $where_clauses);

$list_html = '';

try {
   // On récupère les profs triés par ordre alphabétique
    $sql = "SELECT id, nom, prenom
            FROM professeurs 
            {$where_sql} 
            ORDER BY nom ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params); 
    $professeurs = $stmt->fetchAll();

    if ($professeurs) {
        foreach ($professeurs as $prof) {
            // Sécurité : on nettoie les données pour éviter les failles XSS
            $prof_id = htmlspecialchars($prof['id']);
            $nom_complet = htmlspecialchars($prof['prenom'] . ' ' . $prof['nom']);

            // Important : On ajoute la classe 'add-tutor-link' et l'attribut 'data-id'
            // C'est ce qui permet au JavaScript de détecter le clic et de connaître l'ID du prof
            $list_html .= "
                <li class='add-tutor-link' data-id='{$prof_id}' style='padding: 10px; border-bottom: 1px dashed #eee; cursor: pointer; display: flex; justify-content: space-between;'>
                    <span>{$nom_complet}</span>
                </li>
            ";
        }
    } else {
        // Message si la recherche ne donne rien ou si tout le monde est déjà tuteur
        $list_html = "<li style='padding: 20px; text-align: center;'>Aucun professeur trouvé ou éligible.</li>";
    }

} catch (PDOException $e) {
    // Si erreur
    $list_html = "<li style='color: red;'>Erreur BDD: Impossible de charger la liste.</li>";
}

echo $list_html;
exit();
?>