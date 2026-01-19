<?php
header("Content-Type: application/json");

// --- Connexion BDD ---
$pdo = new PDO("mysql:host=localhost;dbname=bowl;charset=utf8", "root", "");

// --- Exemple : on récupère une entreprise précise ---
$CompanyID = $_GET["id"] ?? 1;

$stmt = $pdo->prepare("SELECT CompanyAddress, CompanyPostal, CompanyCity FROM company WHERE CompanyID = ?");
$stmt->execute([$entrepriseID]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo json_encode(["error" => "Entreprise introuvable"]);
    exit;
}

// --- Construire l'adresse complète ---
$adresse = $row["OfferAddress"] . ", " . $row["OfferPostal"] . " " . $row["OfferCity"];

// --- Encoder pour URL ---
$adresseEncoded = urlencode($adresse);

// --- Appel à Nominatim ---
$url = "https://nominatim.openstreetmap.org/search?format=json&q=" . $adresseEncoded;

// Obligatoire : User-Agent sinon Nominatim --> erreur 403
$opts = [
    "http" => [
        "method" => "GET",
        "header" => "User-Agent: BowlStudentApp/1.0"
    ]
];

$context = stream_context_create($opts);
$response = file_get_contents($url, false, $context);

$data = json_decode($response, true);

// Vérifications
if (!$data || !isset($data[0])) {
    echo json_encode(["error" => "Adresse non localisable"]);
    exit;
}

// Extraire lat/lon
$lat = $data[0]["lat"];
$lon = $data[0]["lon"];

// --- Réponse JSON ---
echo json_encode([
    "adresse" => $adresse,
    "lat" => floatval($lat),
    "lon" => floatval($lon)
]);
