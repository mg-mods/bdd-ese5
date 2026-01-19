<?php
// Fichier : api_tuteurs_search.php


header('Content-Type: text/html; charset=utf-8');
require_once 'config.php'; 
// LA CONNEXION $pdo EST MAINTENANT DISPONIBLE GRÂCE À config.php

$search_name = $_GET['search_name'] ?? ''; 

$where_clauses = ["est_tuteur = 1"]; // <-- MODIFICATION CLÉ : Filtre pour n'afficher que les Tuteurs actifs
$params = [];

// Filtre par nom
if (!empty($search_name)) {
    $where_clauses[] = "(nom LIKE :search OR prenom LIKE :search)";
    $params[':search'] = '%' . $search_name . '%';
}

$where_sql = 'WHERE ' . implode(' AND ', $where_clauses);

$tutor_list_html = '';
// Remplacez 'tutor_item.html' par le code HTML brut de l'élément de liste si vous n'utilisez pas de gabarit externe.
// Si vous utilisez un gabarit, assurez-vous que ce fichier existe ! 
$item_template_file = 'tutor_item.html'; 

// Si vous n'utilisez PAS de fichier externe, utilisez ceci à la place:
$item_template = "<li class='convention-list-item'>
                    <div style='width: 50%; font-weight: bold;'>{PROF_NOM_PRENOM}</div>
                    <div style='width: 25%; text-align: center;' data-count='{NB_ETUDIANT}'>{NB_ETUDIANT}</div>
                    <div style='width: 25%; text-align: center;'><button class='action-button blue-bg view-tutor' data-id='{PROF_ID}' data-name='{PROF_NOM_PRENOM}' data-count='{NB_ETUDIANT}'>Voir Étudiants</button></div>
                </li>";


try {
    $sql = "SELECT id, nom, prenom, est_tuteur, nb_etudiant 
            FROM professeurs 
            {$where_sql} 
            ORDER BY nom ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params); 
    $professeurs = $stmt->fetchAll();

    if ($professeurs) {
        // Si vous utilisez un fichier externe (tutor_item.html):
        if (file_exists($item_template_file)) {
            $item_template = file_get_contents($item_template_file);
        } else {
             // Si le fichier n'existe pas, on utilise le gabarit intégré (voir ci-dessus)
             // Vous n'avez pas besoin de die("Erreur de gabarit") si vous utilisez un gabarit par défaut.
        }

        foreach ($professeurs as $prof) {
            $current_item = $item_template;
            
            $prof_id = htmlspecialchars($prof['id']);
            $nom_complet = htmlspecialchars($prof['prenom'] . ' ' . $prof['nom']);
            $nb = htmlspecialchars($prof['nb_etudiant']);
            // Le rôle sera toujours 'Tuteur' ici grâce au filtre WHERE est_tuteur = 1
            $role = 'Tuteur'; 

            $current_item = str_replace('{PROF_ID}', $prof_id, $current_item);
            $current_item = str_replace('{PROF_NOM_PRENOM}', $nom_complet, $current_item);
            $current_item = str_replace('{NB_ETUDIANT}', $nb, $current_item);
            $current_item = str_replace('{EST_TUTEUR}', $role, $current_item);

            $tutor_list_html .= $current_item;
        }
    } else {
        $tutor_list_html = "<li style='padding: 20px; text-align: center;'>Aucun tuteur trouvé correspondant aux critères.</li>";
    }

} catch (PDOException $e) {
    $tutor_list_html = "<li style='color: red;'>Erreur BDD.</li>";
}

echo $tutor_list_html;
exit();
?>