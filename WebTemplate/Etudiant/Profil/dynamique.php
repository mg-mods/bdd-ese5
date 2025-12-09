<?php
// ===============================
// 🔵 CONNEXION SQL
// ===============================
require_once "../localcommon/db_connect.php"; // adapte selon ton projet

// ID étudiant (via session ou URL)
$studentID = $_SESSION['studentID'] ?? 1;

// ===============================
// 🔵 REQUÊTE SQL : récupérer profil
// ===============================
$sql = "SELECT * FROM students WHERE StudentID = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$studentID]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// ===============================
// 🔵 SI AUCUNE DONNÉE SQL → SIMULATION
// ===============================
if(!$row){
    $row = [
        'StudentLastName'   => "Durant",
        'StudentFirstName'  => "Anne-Sophie",
        'StudentGender'     => "F",
        'StudentIneCode'    => "123664526BG",
        'StudentAuthID'     => "P2307582",
        'StudentEmail'      => "anne.durant@etu.univ-lyon1.fr",
        'StudentPhone'      => "06 12 34 56 78",
        'StudentBranch'     => "BUT3 GEII ESE",
        'StudentStartYear'  => 2022,

        // ⚠️ pas dans la BDD mais présents dans ton JS
        'StudentAddress'    => "12 avenue Victor Hugo, 69002 Lyon",
        'StudentAmenagement'=> "Tiers-temps",
    ];
}

// Calcul de l'âge uniquement si tu veux l’estimer
$age = isset($row['StudentStartYear']) ? (date("Y") - $row['StudentStartYear'] + 18) : 20;
?>
