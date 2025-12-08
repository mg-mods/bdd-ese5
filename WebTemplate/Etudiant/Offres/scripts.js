const offres = [
    { entreprise: "Entreprise A", adresse: "Paris", contact: "rh@A.fr", domaine: "electronique" },
    { entreprise: "Entreprise B", adresse: "Lyon", contact: "rh@B.fr", domaine: "automatisme" },
    { entreprise: "Entreprise C", adresse: "Marseille", contact: "rh@C.fr", domaine: "energie" },
    { entreprise: "Entreprise D", adresse: "Toulouse", contact: "rh@D.fr", domaine: "electronique" },
    { entreprise: "Entreprise E", adresse: "Nice", contact: "rh@E.fr", domaine: "automatisme" }
];

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

afficherOffres(offres);

const btnRetour = document.getElementById("btnRetour");

if (btnRetour) {
    btnRetour.addEventListener("click", () => {
        window.history.back();
    });
}


// =======================
// =======  MAP  =========
// =======================

// Coordonnées bidon associées aux villes
const coordonnees = {
    "Paris": [48.8566, 2.3522],
    "Lyon": [45.7640, 4.8357],
    "Marseille": [43.2965, 5.3698],
    "Toulouse": [43.6047, 1.4442],
    "Nice": [43.7102, 7.2620]
};

// Initialisation de la carte
const map = L.map('map').setView([46.5, 2.5], 6); // Vue centrée sur la France

// Fond de carte
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

// Ajout des points des entreprises
offres.forEach(offre => {
    const coords = coordonnees[offre.adresse];

    if (coords) {
        L.marker(coords).addTo(map)
            .bindPopup(`
                <strong>${offre.entreprise}</strong><br>
                ${offre.adresse}<br>
                ${offre.contact}<br>
                Domaine : ${offre.domaine}
            `);
    }
});