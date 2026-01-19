document.addEventListener('DOMContentLoaded', function() {
    
    // --- Éléments Communs ---
    const resultList = document.getElementById('result-list');
    const searchForm = document.getElementById('filter-form');
    
    // --- Élément spécifique à la page Convention (le filtre statut) ---
    const statusSelect = document.getElementById('statut-select'); 

    // --- Éléments de la Modale (Communs) ---
    const modal = document.getElementById('convention-modal');
    const closeButton = modal ? modal.querySelector('.close-button') : null; 
    const modalTitle = document.getElementById('modal-title');
    const modalIdSpan = document.getElementById('modal-convention-id'); 
    
    const pdfContainer = document.getElementById('pdf-viewer-container');
    const studentContainer = document.getElementById('student-list-container');
    const actionButtons = document.getElementById('modal-actions');
    
    // Boutons d'action spécifiques
    const btnAccept = document.getElementById('btn-accept');
    const btnRefuse = document.getElementById('btn-refuse');
    const btnAssignTutor = document.getElementById('btn-assign-tutor');
    const btnAssignTutor2 = document.getElementById('btn-assign-tutor2'); // Nouveau bouton "Changer Tuteur"

    // NOUVEL ÉLÉMENT : Le bouton de retrait du tuteur
    const btnRemoveTutor = document.getElementById('btn-remove-tutor'); 

    // --- Éléments de la MODALE DE SÉLECTION DE TUTEUR (Communs aux deux pages) ---
    const selectTutorModal = document.getElementById('select-tutor-modal');
    const closeSelectTutorModal = document.getElementById('close-select-tutor-modal');
    const selectTutorForm = document.getElementById('select-tutor-form');
    const nonTutorList = document.getElementById('non-tutor-list'); // UL qui contient la liste
    const tutorSearchInput = document.getElementById('tutor-search-input');
    
    let currentConventionId = null; // Stocke l'ID de la convention pour l'attribution

    // --- Fonction de nettoyage (Cache tous les boutons) ---
    function hideAllActionButtons() {
        if(btnAccept) btnAccept.style.display = 'none';
        if(btnRefuse) btnRefuse.style.display = 'none';
        if(btnAssignTutor) btnAssignTutor.style.display = 'none';
        if(btnAssignTutor2) btnAssignTutor2.style.display = 'none';
        if(btnRemoveTutor) btnRemoveTutor.style.display = 'none';
        if(actionButtons) actionButtons.style.display = 'none';
    }

    // =========================================================
    // FONCTION GLOBALE : Chargement des Tuteurs (pour rafraîchir les comptes)
    // =========================================================
    function loadTutors(e) {
        if(e) e.preventDefault();
        // S'assure que nous sommes sur la page Tuteurs pour effectuer le rechargement
        if (!resultList || statusSelect) {
            return; 
        }
        
        resultList.innerHTML = "<li>Chargement des tuteurs...</li>";
        const input = searchForm.querySelector('input[name="search_name"]');
        const val = input ? input.value : '';
        
        fetch('api_tuteurs_search.php?search_name=' + encodeURIComponent(val))
            .then(r => r.text())
            .then(html => resultList.innerHTML = html)
            .catch(e => console.error(e));
    }
    // =========================================================


    // --- Gestion fermeture Modale Convention ---
    if(closeButton) {
        closeButton.addEventListener('click', () => {
            modal.style.display = 'none';
            hideAllActionButtons(); // Nettoyage
        });
        window.addEventListener('click', (e) => { 
            if(e.target == modal) {
                modal.style.display = 'none';
                hideAllActionButtons(); // Nettoyage
            }
        });
    }

    // --- Gestion fermeture Modale de Sélection de Tuteur ---
    if (closeSelectTutorModal) {
        closeSelectTutorModal.addEventListener('click', function() {
            selectTutorModal.style.display = 'none';
        });
    }
    window.addEventListener('click', function(e) {
        if (e.target == selectTutorModal) {
            selectTutorModal.style.display = 'none';
        }
    });

    // ============================================================
    // CAS 1 : PAGE CONVENTIONS (Si le filtre statut existe)
    // ============================================================
    if (statusSelect && resultList) {
        
        const selectTutorModalTitle = document.getElementById('select-tutor-modal-title');
        
        hideAllActionButtons(); // S'assurer que les boutons Tuteurs sont cachés

        function loadConventions(e) {
            if(e) e.preventDefault();
            resultList.innerHTML = "<li>Chargement...</li>";
            
            const formData = new FormData(searchForm);
            const queryString = new URLSearchParams(formData).toString();

            fetch('api_search.php?' + queryString) 
                .then(r => r.text())
                .then(html => resultList.innerHTML = html)
                .catch(e => console.error(e));
        }

        // --- Fonction pour charger la liste des Tuteurs actifs pour l'attribution ---
        function loadActiveTutors(e) {
            if (e) e.preventDefault();
            nonTutorList.innerHTML = "<li style='text-align: center; padding: 10px;'>Chargement des Tuteurs...</li>";
            
            const searchVal = tutorSearchInput ? tutorSearchInput.value : '';

            fetch('api_tuteurs_only.php?search_name=' + encodeURIComponent(searchVal))
                .then(r => r.text())
                .then(html => nonTutorList.innerHTML = html)
                .catch(err => nonTutorList.innerHTML = `<li style='color: red; padding: 10px;'>Erreur: ${err}</li>`);
        }

        searchForm.addEventListener('submit', loadConventions);
        statusSelect.addEventListener('change', loadConventions);
        loadConventions(); // Chargement initial

        // --- Clic sur "Voir" (Convention) ---
        resultList.addEventListener('click', function(e) {
            const link = e.target.closest('.view-convention');
            
            if(link) {
                e.preventDefault();
                
                const id = link.dataset.id;
                const status = link.dataset.status.toLowerCase().trim();
                const pdfPath = link.dataset.pdf;
                
                hideAllActionButtons(); // Reset des boutons

                // 1. Mise à jour Titre & ID
                if(modalTitle) modalTitle.textContent = "Convention #" + id;
                if(modalIdSpan) modalIdSpan.textContent = id;
                
                // 2. Afficher PDF
                if(pdfContainer) {
                    pdfContainer.style.display = 'block';
                    if (pdfPath) {
                        pdfContainer.innerHTML = `<embed src="${pdfPath}" type="application/pdf" width="100%" height="100%">`;
                    } else {
                        pdfContainer.innerHTML = '<p style="text-align: center; color: red;">Aucun PDF associé.</p>';
                    }
                }

                // 3. Cacher la liste étudiant (spécifique tuteur)
                if(studentContainer) studentContainer.style.display = 'none';
                
                // 4. Afficher le conteneur des boutons
                if(actionButtons) actionButtons.style.display = 'block';

                // 5. LOGIQUE DES BOUTONS (Spécifiques aux Conventions)
                if (btnAccept && btnRefuse && btnAssignTutor && btnAssignTutor2) {
                    
                    // Logique d'affichage selon le statut
                    if (status === 'en_attente_de_validation') {
                        btnAccept.style.display = 'inline-block';
                        btnRefuse.style.display = 'inline-block';
                    } else if (status === 'en_attente_de_tuteur') {
                        btnRefuse.style.display = 'inline-block';
                        btnAssignTutor.style.display = 'inline-block';
                    } else if (status === 'refusé') {
                        btnAccept.style.display = 'inline-block';
                    } else if (status === 'validé') {
                        btnAssignTutor2.style.display = 'inline-block'; // Afficher "Changer Tuteur"
                    }

                    // Mise à jour des ID pour les boutons
                    btnAccept.dataset.id = id;
                    btnRefuse.dataset.id = id;
                    btnAssignTutor.dataset.conventionId = id; 
                    btnAssignTutor2.dataset.conventionId = id;
                }

                modal.style.display = 'block';
            }
        });

        // --- Gestion du clic sur les boutons d'action (AJAX UPDATE) ---
        if(actionButtons) {
            actionButtons.addEventListener('click', function(e) {
                const target = e.target;
                let newStatus = '';
                
                // --- 1. GESTION DU BOUTON ATTRIBUER TUTEUR / CHANGER TUTEUR (Ouvrir la pop-up) ---
                if (target === btnAssignTutor || target === btnAssignTutor2) { // Inclut le nouveau bouton
                    currentConventionId = target.dataset.conventionId; 
                    modal.style.display = 'none'; 
                    
                    selectTutorModalTitle.textContent = "Sélectionner un Tuteur pour Convention #" + currentConventionId;
                    selectTutorModal.style.display = 'block';
                    loadActiveTutors(); 
                    return;
                }

                // --- 2. GESTION ACCEPT / REFUSE (Changement de statut) ---
                if (target === btnAccept) {
                    newStatus = 'En_attente_de_tuteur'; 
                } else if (target === btnRefuse) {
                    newStatus = 'Refusé'; 
                } else {
                    return; 
                }
                
                // Ne pas exécuter si on clique sur le bouton "Retirer Tuteur" sur la page Convention
                if (target === btnRemoveTutor) {
                    return;
                }


                if (confirm(`Confirmer le changement de statut en : ${newStatus} ?`)) {
                    const conventionId = target.dataset.id;
                    
                    fetch('update_status.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `id=${conventionId}&status=${newStatus}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Statut mis à jour !');
                            modal.style.display = 'none';
                            loadConventions(); 
                        } else {
                            alert('Erreur : ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        alert('Erreur réseau.');
                    });
                }
            });
        }

        // --- Événements de Recherche dans la Modale de Sélection (pour Conventions) ---
        if(selectTutorForm) {
            selectTutorForm.addEventListener('submit', loadActiveTutors);
        }

        // --- Clic sur un Tuteur pour l'attribuer à la convention (Page Conventions) ---
        if(nonTutorList) {
            nonTutorList.addEventListener('click', function(e) {
                const link = e.target.closest('.select-tutor-link'); 
                
                if (link && currentConventionId) {
                    const tutorId = link.dataset.id;
                    const tutorName = link.dataset.name;

                    if (confirm(`Attribuer ${tutorName} comme Tuteur à la convention #${currentConventionId} ?`)) {
                        
                        fetch('api_assign_tutor.php', { 
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: `convention_id=${currentConventionId}&tutor_id=${tutorId}`
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                alert(`Tuteur attribué : ${tutorName}.`);
                                selectTutorModal.style.display = 'none';
                                currentConventionId = null; 
                                loadConventions(); 
                                
                                // APPEL CRITIQUE : Mise à jour du compteur sur la page Tuteurs
                                loadTutors(); 
                            } else {
                                alert("Échec de l'attribution : " + data.message);
                            }
                        })
                        .catch(err => alert("Erreur de connexion au serveur lors de l'attribution."));
                    }
                }
            });
        }
    }
    
    // --------------------------------------------------------------------------------------
    
    // ============================================================
    // CAS 2 : PAGE TUTEURS (Si pas de statut, mais liste présente)
    // ============================================================
    else if (!statusSelect && resultList && searchForm) {
        
        // --- ÉLÉMENTS DOM SPÉCIFIQUES À LA GESTION DES PROFESSEURS ---
        const btnOpenAdd = document.getElementById('btn-open-add');
        const selectTutorModalTitle = document.getElementById('select-tutor-modal-title');


        // --- A. LOGIQUE D'AFFICHAGE ET DE RECHERCHE DES TUTEURS ---
        // loadTutors est maintenant globale
        
        
        searchForm.addEventListener('submit', loadTutors);
        loadTutors(); // Chargement initial des tuteurs

        // --- B. LOGIQUE D'AJOUT DE NOUVEAUX TUTEURS (PAGE TUTEURS) ---

        // 1. Fonction pour charger la liste des professeurs non-tuteurs
        function loadNonTutors(e) {
            if (e) e.preventDefault();
            nonTutorList.innerHTML = "<li style='text-align: center; padding: 10px;'>Chargement des professeurs...</li>";
            
            const searchVal = tutorSearchInput ? tutorSearchInput.value : '';

            fetch('api_prof_non_tuteur.php?search_name=' + encodeURIComponent(searchVal))
                .then(r => r.text())
                .then(html => nonTutorList.innerHTML = html)
                .catch(err => nonTutorList.innerHTML = `<li style='color: red; padding: 10px;'>Erreur: ${err}</li>`);
        }

        // 2. Événement : Clic sur le bouton "+ Ajouter un Tuteur"
        if(btnOpenAdd && selectTutorModal) {
            btnOpenAdd.addEventListener('click', function() {
                if(selectTutorModalTitle) selectTutorModalTitle.textContent = "Ajouter un Professeur comme Tuteur";

                selectTutorModal.style.display = 'block';
                loadNonTutors(); 
            });
        }

        // 3. Événement : Recherche dans la modale (pour la page Tuteurs)
        if(selectTutorForm) {
            selectTutorForm.addEventListener('submit', loadNonTutors);
        }

        // 4. Événement : Clic sur un nom pour l'ajouter comme Tuteur (Page Tuteurs)
        if(nonTutorList) {
            nonTutorList.addEventListener('click', function(e) {
                const link = e.target.closest('.add-tutor-link'); 
                if (link) {
                    const profId = link.dataset.id;
                    const profName = link.textContent.trim(); 

                    if (confirm(`Voulez-vous vraiment ajouter ${profName} comme Tuteur ?`)) {
                        
                        fetch('api_set_tuteur.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: `id=${profId}`
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                alert(`${profName} est maintenant Tuteur !`);
                                selectTutorModal.style.display = 'none';
                                loadTutors(); // Recharger la liste des tuteurs
                            } else {
                                alert("Échec : " + data.message);
                            }
                        })
                        .catch(err => alert("Erreur de connexion au serveur."));
                    }
                }
            });
        }

        // --- C. LOGIQUE DU BOUTON RETIRER TUTEUR ---
        if (btnRemoveTutor) {
            btnRemoveTutor.addEventListener('click', function() {
                const tutorId = btnRemoveTutor.dataset.tutorId;
                const tutorName = modalTitle.textContent.split('(')[0].trim();
                
                const confirmationMessage = `Êtes-vous sûr de vouloir retirer le rôle de tuteur à ${tutorName} ? Cela va retirer tous ses étudiants et changer le statut de leurs conventions à "En_attente_de_tuteur".`;
                
                if (!tutorId || !confirm(confirmationMessage)) {
                    return;
                }
                
                fetch('api_remove_tutor.php', { 
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `tutor_id=${tutorId}`
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert(`${tutorName} n'est plus tuteur et ${data.conventions_updated || 0} conventions ont changé de statut.`);
                        modal.style.display = 'none';
                        hideAllActionButtons(); 
                        loadTutors(); // Recharger la liste pour mettre à jour les comptes
                    } else {
                        alert("Échec de la désassociation : " + data.message);
                    }
                })
                .catch(err => alert("Erreur de connexion au serveur lors de la désactivation du tuteur."));
            });
        }
        
        // --- D. LOGIQUE (Clic "Voir Étudiants" dans la liste des Tuteurs) ---
        resultList.addEventListener('click', function(e) {
            const link = e.target.closest('.view-tutor');
            if(link) {
                e.preventDefault();
                const profId = link.dataset.id;
                const profName = link.dataset.name;
                const studentCount = parseInt(link.dataset.count); 
                
                hideAllActionButtons(); // Reset des boutons
                
                modalTitle.textContent = profName + " (" + studentCount + " étudiants)";
                
                if(pdfContainer) pdfContainer.style.display = 'none';
                
                // Affichage du conteneur des boutons
                if (actionButtons) actionButtons.style.display = 'block';

                if(btnRemoveTutor) {
                    btnRemoveTutor.style.display = 'inline-block';
                    btnRemoveTutor.dataset.tutorId = profId; 
                }  
                if(studentContainer) {
                    studentContainer.style.display = 'block';
                    studentContainer.innerHTML = "Chargement...";
                    
                    fetch('api_tuteurs_students.php?tutor_id=' + profId)
                        .then(r => r.json())
                        .then(data => {
                            if(data.success && data.data.length > 0) {
                                let html = '<p style="font-weight: bold;">Conventions supervisées :</p><ul style="padding-left:20px;">';
                                data.data.forEach(etu => {
                                    html += `<li><strong>${etu.prenom_etudiant} ${etu.nom_etudiant}</strong> </li>`;
                                });
                                html += '</ul>';
                                studentContainer.innerHTML = html;
                            } else if (data.success && data.data.length === 0) {
                                studentContainer.innerHTML = "<p>Aucun étudiant supervisé actuellement.</p>";
                            } else {
                                studentContainer.innerHTML = `<p style='color:red'>Erreur de l'API : ${data.message || "Erreur inconnue."}</p>`;
                            }
                        })
                        .catch((err) => {
                            console.error(err);
                            studentContainer.innerHTML = "<p style='color:red'>Erreur chargement ou connexion API.</p>";
                        });
                }

                modal.style.display = 'block';
            }
        });
    } // Fin du CAS 2 TUTEURS
}); // Fin de DOMContentLoaded