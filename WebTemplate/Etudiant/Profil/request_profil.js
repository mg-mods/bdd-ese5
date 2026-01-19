// Detect page load
console.log("JS OK2");

document.addEventListener("DOMContentLoaded", () => {
    
    async function chargerProfil() {
        try {
            // Appel au PHP
            const response = await fetch("request_profil.php");
            const data = await response.json();

            if (data.success) {
                // Injection des données dans le HTML
                document.getElementById("nom").textContent = data.StudentLastName;
                document.getElementById("prenom").textContent = data.StudentFirstName;
                document.getElementById("num_etudiant").textContent = data.StudentUnivID;
                document.getElementById("codeINE").textContent = data.StudentIneCode;
                document.getElementById("parcours").textContent = data.StudentCourse;
                document.getElementById("email").textContent = data.StudentEmail;
                
                // Formatage téléphone (Indicateur + Numéro)
                document.getElementById("telephone").textContent = 
                    (data.StudentPhoneIndicator || "") + " " + (data.StudentPhone || "");
            }
        } catch (error) {
            console.error("Erreur de chargement :", error);
        }
    }

    chargerProfil();
});
