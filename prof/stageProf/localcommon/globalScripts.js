document.addEventListener("DOMContentLoaded", () => {

    /* ------------------------------
       MODAL LOGIC
    ------------------------------ */

    const modal = document.getElementById("stageModal");
    const closeBtn = document.querySelector(".modal-close");

    const modalTitle = document.getElementById("modal-title");
    const modalSubtitle = document.getElementById("modal-subtitle");
    const modalDescription = document.getElementById("modal-description");
    const modalButton = document.getElementById("modal-button");

    // Every "Details" button inside stage menus
    const detailsButtons = document.querySelectorAll(".details-stage");

    detailsButtons.forEach(btn => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();

            const card = btn.closest(".stage-card");

            modalTitle.textContent = card.dataset.title;
            modalSubtitle.textContent = card.dataset.subtitle;
            modalDescription.textContent = card.dataset.description;
            modalButton.textContent = card.dataset.button;

            modal.style.display = "flex";

        });
    });

    // Close modal by X
    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Close modal by clicking outside
    modal.addEventListener("click", (e) => {
        if (e.target === modal) modal.style.display = "none";
    });


});

