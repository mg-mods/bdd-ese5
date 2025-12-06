<?php
// 1. Connexion à la BDD A ADAPTER A LA BDD
$pdo = new PDO('mysql:host=localhost;dbname=ton_database;charset=utf8', 'root', '');

//  On Récupère l'id étudiant
$id_etudiant = $_POST['id_etudiant'];

// 3. Vérifier si un fichier a été envoyé
if (!isset($_FILES['cv'])) {
    die("Aucun fichier reçu.");
}

$file = $_FILES['cv'];

// Vérifier le type de fichier (uniquement PDF)
if ($file['type'] !== 'application/pdf') {
    die("Le fichier doit être un PDF.");
}

// Créer un nom unique pour éviter les collisions
$nomFichier = "cv_" . $id_etudiant . "_" . time() . ".pdf";

// Dossier de stockage (le créer si nécessaire)
$dossier = "uploads/cv/";
if (!is_dir($dossier)) {
    mkdir($dossier, 0777, true);
}

$destination = $dossier.$nomFichier;

//Déplacer le fichier dans le dossier
if (!move_uploaded_file($file['tmp_name'], $destination)) {
    die("Erreur lors de l'upload du fichier.");
}

// Sauvegarde dans la BDD
$req = $pdo->prepare("
    INSERT INTO cv_etudiant (id_etudiant, cv_nom, cv_chemin, date_upload)
    VALUES (:id_etudiant, :cv_nom, :cv_chemin, NOW())
");

$req->execute([
    ':id_etudiant' => $id_etudiant,
    ':cv_nom'      => $nomFichier,
    ':cv_chemin'   => $destination
]);

echo "CV envoyé avec succès !";
?>