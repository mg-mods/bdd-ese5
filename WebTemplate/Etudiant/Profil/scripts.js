console.log("JS OK");

// ✅ OBJET PROFIL (simulation en attendant la BDD)
let profil = {
    nom: "Durant",
    prenom: "Anne-Sophie",
    age: 22,
    numero: "P2307582",
    codeINE: "123664526BG",
    parcours: "BUT3 GEII ESE ",
    adresse: "12 avenue victor HUGO 69002 Lyon",
    amenagement: "Tiers-temps",
    telephone: "06 12 34 56 78",
    email: "anne.durant@etu.univ-lyon1.fr"
};

// ✅ FONCTION QUI AFFICHE LES INFOS DANS LA CARTE
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

// ✅ PREMIER AFFICHAGE AU CHARGEMENT
afficherProfil();

// ✅ OUVERTURE DU POPUP
document.getElementById("open-modifier").addEventListener("click", function (e) {
    e.preventDefault();

    document.getElementById("popup").style.display = "flex";

    // Pré-remplissage du formulaire
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

// ✅ FERMETURE DU POPUP
document.getElementById("close").addEventListener("click", function () {
    document.getElementById("popup").style.display = "none";
});

// ✅ SAUVEGARDE DES MODIFICATIONS
document.getElementById("save").addEventListener("click", function () {

    // Mise à jour de l'objet profil
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

    // Réaffichage dans la carte
    afficherProfil();

    // Fermeture du popup
    document.getElementById("popup").style.display = "none";
});