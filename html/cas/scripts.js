document.addEventListener("DOMContentLoaded", () => {
    // password reveal control
    const pwd = document.getElementById("passwordInput");
    const revealBtn = document.getElementById("revealBtn");

    revealBtn.onmousedown = () => pwd.type = "text";
    revealBtn.onmouseup = () => pwd.type = "password";
    revealBtn.onmouseleave = () => pwd.type = "password";

    // AJAX login handling
    const form = document.getElementById("loginForm");
    const errorMsg = document.getElementById("errorMsg");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        const result = await fetch("login-handler.php", {
            method: "POST",
            body: formData
        }).then(r => r.json());

        if (result.success === true) {

            const params = new URLSearchParams(window.location.search);
            const service = params.get("service");
            if (service) window.location.href = service;

        } else {
            errorMsg.textContent = "Identifiant ou mot de passe incorrect.";
        }
    });
});
