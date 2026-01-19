<?php
// Fichier : api_tuteurs_only.php
// Retourne du HTML (les <li>) pour les professeurs qui sont TUTEURS (est_tuteur = 1)

header('Content-Type: text/html; charset=utf-8');
require_once 'config.php'; 

$search_name = $_GET['search_name'] ?? ''; 

// FILTRE CLÉ : Sélectionne uniquement ceux qui sont TUTEURS
$where_clauses = ["est_tuteur = 1"];
$params = [];

if (!empty($search_name)) {
    $where_clauses[] = "(nom LIKE :search OR prenom LIKE :search)";
    $params[':search'] = '%' . $search_name . '%';
}

$where_sql = 'WHERE ' . implode(' AND ', $where_clauses);

$list_html = '';

try {
    $sql = "SELECT id, nom, prenom 
            FROM professeurs 
            {$where_sql} 
            ORDER BY nom ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params); 
    $professeurs = $stmt->fetchAll();

    if ($professeurs) {
        foreach ($professeurs as $prof) {
            $prof_id = htmlspecialchars($prof['id']);
            $nom_complet = htmlspecialchars($prof['prenom'] . ' ' . $prof['nom']);
            
            // On utilise la classe 'select-tutor-link' pour l'action d'attribution de convention
            $list_html .= "
                <li class='select-tutor-link' data-id='{$prof_id}' data-name='{$nom_complet}' style='padding: 10px; border-bottom: 1px dashed #eee; cursor: pointer;'>
                    <span>{$nom_complet}</span>
                </li>
            ";
        }
    } else {
        $list_html = "<li style='padding: 20px; text-align: center;'>Aucun tuteur actif trouvé.</li>";
    }

} catch (PDOException $e) {
    $list_html = "<li style='color: red;'>Erreur BDD: Impossible de charger la liste des tuteurs.</li>";
}

echo $list_html;
exit();
?>