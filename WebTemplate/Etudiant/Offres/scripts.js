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

// Bouton retour
document.getElementById("btnRetour").addEventListener("click", () => {
    window.history.back();
});