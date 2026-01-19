// =======================
// === Chargement PHP ====
// =======================

fetch("offres.php")
    .then(res => res.json())
    .then(data => {
        let offres = [];

        // Si le PHP renvoie une liste d’offres valide
        if (Array.isArray(data) && data.length > 0) {
            offres = data;
        } else {
            // 👇 SIMULATION SI AUCUNE DONNÉE BDD
            offres = [
                {
                    entreprise: "Entreprise Démo",
                    adresse: "Lyon",
                    contact: "demo@entreprise.fr",
                    domaine: "exemple"
                }
            ];
        }

        afficherOffres(offres);
        afficherMarkers(offres);
    })
    .catch(err => {
        console.error("Erreur PHP :", err);

        // 👇 SIMULATION SI ERREUR
        const offres = [
            {
                entreprise: "Entreprise Démo",
                adresse: "Lyon",
                contact: "demo@entreprise.fr",
                domaine: "exemple"
            }
        ];

        afficherOffres(offres);
        afficherMarkers(offres);
    });


// =======================
// ======= OFFRES ========
// =======================

const offresList = document.getElementById("offresList");

function afficherOffres(liste) {
    offresList.innerHTML = "";
    liste.forEach(offre => {
        const div = document.createElement("div");
        div.className = "offre";
        div.innerHTML = `
            <h3>${offre.entreprise}</h3>
            <p>Adresse : ${offre.adresse}</p>
            <p>Contact RH : ${offre.contact}</p>
            <p>Domaine : ${offre.domaine}</p>
            <small> Toujours disponible</small><br>
            <button>Candidater</button>
        `;
        offresList.appendChild(div);
    });
}

// Bouton Retour
const btnRetour = document.getElementById("btnRetour");
if (btnRetour) {
    btnRetour.addEventListener("click", () => window.history.back());
}


// =======================
// =======  MAP  =========
// =======================

// Coordonnées connues (tu peux en ajouter plus)
const coordonnees = {
    "Paris": [48.8566, 2.3522],
    "Lyon": [45.7640, 4.8357],
    "Marseille": [43.2965, 5.3698],
    "Toulouse": [43.6047, 1.4442],
    "Nice": [43.7102, 7.2620]
};

// Carte
const map = L.map('map').setView([46.5, 2.5], 6);

// Tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);


// ===== AJOUT DES MARQUEURS =====

function afficherMarkers(offres) {
    offres.forEach(offre => {
        const coords = coordonnees[offre.adresse]; // Converti automatiquement si ville connue

        if (coords) {
            L.marker(coords).addTo(map).bindPopup(`
                <strong>${offre.entreprise}</strong><br>
                ${offre.adresse}<br>
                ${offre.contact}<br>
                Domaine : ${offre.domaine}
            `);
        }
    });
}
