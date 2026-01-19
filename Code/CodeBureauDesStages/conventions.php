<?php

// Chemins vers les fichiers gabarits (templates)
$template_file = 'conventions.html'; 
$item_template_file = 'convention_item.html'; 

$convention_list_html = ''; 
$no_results_message = '';   



// Assemblage du gabarit principal et affichage
if (!file_exists($template_file)) {
    die("Erreur: Le fichier gabarit principal '{$template_file}' est introuvable.");
}
$final_page = file_get_contents($template_file);

// Remplacement des marqueurs du gabarit principal
$final_page = str_replace('{CONVENTION_LIST_PLACEHOLDER}', $convention_list_html, $final_page);
$final_page = str_replace('{NO_RESULTS_MESSAGE}', $no_results_message, $final_page);
$final_page = str_replace('{SEARCH_FILTER_PLACEHOLDER}', '<p>Zone de recherche/filtres sera ajoutée ici...</p>', $final_page); 

echo $final_page;