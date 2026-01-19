<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bowl – Candidatures</title>

    <!-- Common -->
    <link rel="stylesheet" href="/Common/styles.css">
    <script src="/Common/scripts.js" defer></script>

    <!-- Page -->
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js" defer></script>
</head>
<body>

<header>
    <div class="header-container">
        <div class="header-left">
            <div class="header-logo">Bowl</div>
            <nav>
                <a href="#">Offres</a>
                <a href="#">Candidatures</a>
            </nav>
        </div>
        <button class="header-user">Déconnexion</button>
    </div>
</header>

<main>
    <div class="courses-grid" id="applicationsGrid"></div>
</main>

<footer>
    <div class="footer-container">
        <div class="footer-logo">Bowl</div>
    </div>
</footer>

<!-- Modal -->
<div class="modal" id="courseModal">
    <div class="modal-content">
        <span class="modal-close">&times;</span>

        <h1 class="modal-title" id="modal-title"></h1>
        <h2 class="modal-subtitle" id="modal-subtitle"></h2>
        <p class="modal-desc" id="modal-description"></p>

        <a id="modal-resume" class="modal-button" target="_blank" style="display:none;">
            Télécharger le CV
        </a>

        <div class="modal-actions">
            <button id="modal-reject" class="modal-button">Rejeter</button>
            <button id="modal-contact" class="modal-button">Contacter</button>
            <button id="modal-accept" class="modal-button">Accepter</button>
        </div>
    </div>
</div>

</body>
</html>
