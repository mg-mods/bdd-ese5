console.log("JS OK");

// Récupération depuis le HTML (déjà rempli par PHP)
let profil = {
    nom: document.getElementById("nom").textContent,
    prenom: document.getElementById("prenom").textContent,
    age: document.getElementById("age").textContent,
    numero: document.getElementById("num_etudiant").textContent,
    codeINE: document.getElementById("codeINE").textContent,
    parcours: document.getElementById("parcours").textContent,
    adresse: document.getElementById("adresse").textContent,
    amenagement: document.getElementById("amenagement").textContent,
    telephone: document.getElementById("telephone").textContent,
    email: document.getElementById("email").textContent
};

// Réaffiche dans la carte
function afficherProfil() {
    document.getElementById("nom").textContent = profil.nom;
    document.getElementById("prenom").textContent = profil.prenom;
    document.getElementById("age").textContent = profil.age;
    document.getElementById("num_etudiant").textContent = profil.numero;
    document.getElementById("codeINE").textContent = profil.codeINE;
    document.getElementById("parcours").textContent = profil.parcours;
    document.getElementById("adresse").textContent = profil.adresse;
    document.getElementById("amenagement").textContent = profil.amenagement;
    document.getElementById("telephone").textContent = profil.telephone;
    document.getElementById("email").textContent = profil.email;
}

// Ouvre popup
document.getElementById("open-modifier").addEventListener("click", function(e){
    e.preventDefault();

    document.getElementById("popup").style.display = "flex";

    document.getElementById("edit_nom").value = profil.nom;
    document.getElementById("edit_prenom").value = profil.prenom;
    document.getElementById("edit_age").value = profil.age;
    document.getElementById("edit_num").value = profil.numero;
    document.getElementById("edit_codeINE").value = profil.codeINE;
    document.getElementById("edit_parcours").value = profil.parcours;
    document.getElementById("edit_adresse").value = profil.adresse;
    document.getElementById("edit_amenagement").value = profil.amenagement;
    document.getElementById("edit_tel").value = profil.telephone;
    document.getElementById("edit_email").value = profil.email;
});

// Fermer popup
document.getElementById("close").addEventListener("click", () => {
    document.getElementById("popup").style.display = "none";
});

// Enregistrer modifs
document.getElementById("save").addEventListener("click", () => {
    profil.nom = document.getElementById("edit_nom").value;
    profil.prenom = document.getElementById("edit_prenom").value;
    profil.age = document.getElementById("edit_age").value;
    profil.numero = document.getElementById("edit_num").value;
    profil.codeINE = document.getElementById("edit_codeINE").value;
    profil.parcours = document.getElementById("edit_parcours").value;
    profil.adresse = document.getElementById("edit_adresse").value;
    profil.amenagement = document.getElementById("edit_amenagement").value;
    profil.telephone = document.getElementById("edit_tel").value;
    profil.email = document.getElementById("edit_email").value;

    afficherProfil();

    document.getElementById("popup").style.display = "none";
});
