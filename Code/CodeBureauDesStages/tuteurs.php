<?php
// Fichier : tuteur.php

// 1. Définition du fichier gabarit
$template_file = 'GestionDesTuteurs.html'; 

// 2. Vérification et chargement du gabarit
if (!file_exists($template_file)) {
    die("Erreur: Le fichier gabarit principal '{$template_file}' est introuvable.");
}

$final_page = file_get_contents($template_file);

// 3. Affichage
echo $final_page;
?>