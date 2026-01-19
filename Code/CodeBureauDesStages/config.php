<?php
/**
 * 3 variables à mofifier pour l'adapté à la BDD et au pc.
 */

$serveur = "localhost";        // L'adresse du serveur 
$db_user = "root";             // Le nom d'utilisateur de MySQL 
$BaseDeDonnee = "test_conventions"; // Le nom de la base 


 // Crée une nouvelle instance de PDO avec les paramètres ci-dessus.
$pdo = new PDO(
    "mysql:host=$serveur;dbname=$BaseDeDonnee;charset=utf8",
    $db_user,
    );
    
    // Configure PDO pour qu'il signale toutes les erreurs SQL.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// L'objet $pdo est disponible pour toutes les pages qui incluent ce fichier
?>