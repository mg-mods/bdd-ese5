<?php
// api_search.php - Script dédié à retourner la liste filtrée

// 1. Connexion à la base de données
require_once 'config.php';

// ----------------------------------------------------------------------
// GESTION DES FILTRES ET CONSTRUCTION DE LA REQUÊTE PRÉPARÉE
// ----------------------------------------------------------------------

// Les données arrivent via GET depuis le JavaScript
$search_name = $_GET['search_name'] ?? '';
$selected_status = $_GET['status'] ?? '';

$where_clauses = [];
$params = [];

// A) FILTRE PAR NOM
if (!empty($search_name)) {
    $searchTerm = '%' . $search_name . '%';
    $where_clauses[] = "(e.nom LIKE :search_term OR e.prenom LIKE :search_term)";
    $params[':search_term'] = $searchTerm;
}

// B) FILTRE PAR STATUT
if (!empty($selected_status)) {
    $where_clauses[] = "c.statut = :selected_status";
    $params[':selected_status'] = $selected_status;
}

// Construction de la clause WHERE finale
$where_sql = '';
if (!empty($where_clauses)) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
}

// Initialisation des résultats (HTML à retourner)
$convention_list_html = '';
$item_template_file = 'convention_item.html';


try {
    // 2. Requête SQL: Insère la clause WHERE construite
    $sql = "
        SELECT 
            c.id AS id_convention, 
            c.statut, 
            e.nom AS nom_eleve, 
            e.prenom AS prenom_eleve,
            c.pdf_chemin
        FROM conventions c
        JOIN eleves e ON c.eleve_id = e.id
        {$where_sql}
        ORDER BY c.id DESC
    ";
    
    // Exécution sécurisée
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params); 
    $conventions = $stmt->fetchAll();

    // 3. Traitement et génération du HTML
   // 3. Traitement et génération du HTML
if ($conventions) {
    // ... vérification du fichier template ...
    $item_template = file_get_contents($item_template_file);

    foreach ($conventions as $convention) {
        $current_item = $item_template;

        // --- 1. GÉNÉRATION DES DONNÉES ET DES LIENS ---

        // Données principales
        $convention_id = htmlspecialchars($convention['id_convention']);
        $nom_complet = htmlspecialchars($convention['nom_eleve'] . ' ' . $convention['prenom_eleve']);
        $statut = htmlspecialchars($convention['statut']);

        // Logique et Remplacement du STATUT
        $statut_min = strtolower($statut);
$statut_display = $statut; // Par défaut, affichera le statut exact de la BDD

// Statuts d'ATTENTE (Orange)
if (in_array($statut_min, [
    'en_attente_de_validation', 
    'en_attente_de_tuteur'
])) {
    $statut_display = "<span style='color: orange; font-weight: bold;'>{$statut}</span>";
} 
// Statut VALIDÉ (Vert)
elseif (in_array($statut_min, [
    'validé'
])) {
    $statut_display = "<span style='color: green; font-weight: bold;'>{$statut}</span>";
} 
// Statut REFUSÉ (Rouge)
elseif (in_array($statut_min, [
    'refusé'
])) {
    $statut_display = "<span style='color: red; font-weight: bold;'>{$statut}</span>";
}

// Remplacement du marqueur dans le gabarit d'item
$current_item = str_replace('{STATUT_CONVENTION}', $statut_display, $current_item);

        // Génération du LIEN VOIR (avec les data-* nécessaires au JS)
        // NOTE: N'oubliez pas d'inclure c.pdf_chemin dans votre requête SELECT !
        $pdf_path = htmlspecialchars($convention['pdf_chemin'] ?? ''); 
        $voir_link = "<a href='#' 
                      class='view-convention' 
                      data-id='{$convention_id}'
                      data-status='{$statut}'
                      data-pdf='{$pdf_path}'
                      style='color: #ff6600; text-decoration: none; font-weight: bold;'>Voir</a>";

        // --- 2. REMPLACEMENT DES MARQUEURS ---
        
        $current_item = str_replace('{CONVENTION_ID}', $convention_id, $current_item);
        $current_item = str_replace('{ELEVE_NOM_PRENOM}', $nom_complet, $current_item);
        $current_item = str_replace('{STATUT_CONVENTION}', $statut_display, $current_item);
        $current_item = str_replace('{LIEN_VOIR}', $voir_link, $current_item); // <-- Maintenant le LIEN_VOIR est remplacé ici

        // --- 3. CONCATÉNATION DE L'ITEM FINAL ---
        $convention_list_html .= $current_item; }
// ... le reste du try/catch ...
    } else {
        // Aucune convention trouvée
        $convention_list_html = "<li style='padding: 10px; text-align: center; color: #555;'>Aucune convention trouvée.</li>";
    }

} catch (PDOException $e) {
    // Erreur de base de données
    http_response_code(500); // Code d'erreur serveur
    $convention_list_html = "<li style='padding: 10px; color: red;'>Erreur BDD: Impossible de charger les données.</li>";
}

// 4. Sortie: Renvoyer le HTML généré
header('Content-Type: text/html');
echo $convention_list_html;
exit();
?>