const candidatures = [
    {
        entreprise: "Entreprise A",
        offre: "Electronique",
        date: "02/02/2025",
        statut: "en cours",
        dossiers: ["CV", "Lettre"],
        tuteur: "M. Dupont"
    },
    {
        entreprise: "Entreprise B",
        offre: "Automatisme",
        date: "25/01/2025",
        statut: "acceptée",
        dossiers: ["CV"],
        tuteur: "Mme Martin"
    },
    {
        entreprise: "Entreprise C",
        offre: "Energie",
        date: "15/01/2025",
        statut: "vue",
        dossiers: ["CV", "LM"],
        tuteur: "Aucun"
    }
];

const table = document.getElementById("tableCandidatures");
const searchInput = document.getElementById("searchInput");
const filtreStatut = document.getElementById("filtreStatut");

function afficherCandidatures(liste) {
    table.innerHTML = "";

    liste.forEach((candidature, index) => {
        const tr = document.createElement("tr");

        tr.innerHTML = `
            <td>${candidature.entreprise}</td>
            <td>${candidature.offre}</td>
            <td>${candidature.date}</td>
            <td class="statut-${candidature.statut}">${candidature.statut}</td>
            <td>${candidature.dossiers.join(", ")}</td>
            <td>${candidature.tuteur}</td>
            <td>
                ${candidature.statut === "acceptée" || candidature.statut === "refusée"
                ? `<button class="btn-bloque">Verrouillé</button>`
                : `<button class="btn-retirer" onclick="retirerCandidature(${index})">❌ Retirer</button>`
                }
            </td>
        `;

        table.appendChild(tr);
    });
}

function retirerCandidature(index) {
    candidatures.splice(index, 1);
    afficherCandidatures(candidatures);
}

// 🔍 Recherche
searchInput.addEventListener("input", () => {
    filtrer();
});

// 🎯 Filtre par statut
filtreStatut.addEventListener("change", () => {
    filtrer();
});

function filtrer() {
    const recherche = searchInput.value.toLowerCase();
    const statut = filtreStatut.value;

    const resultat = candidatures.filter(c => {
        const matchNom = c.entreprise.toLowerCase().includes(recherche);
        const matchStatut = statut === "tous" || c.statut === statut;
        return matchNom && matchStatut;
    });

    afficherCandidatures(resultat);
}

// Bouton retour sécurisé
const btnRetour = document.getElementById("btnRetour");
if (btnRetour) {
    btnRetour.addEventListener("click", () => {
        window.history.back();
    });
}

// Affichage initial
afficherCandidatures(candidatures);