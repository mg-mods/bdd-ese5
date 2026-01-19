document.addEventListener("DOMContentLoaded", () => {

    /* ------------------------------
       DROPDOWN MENU LOGIC
    ------------------------------ */

    const extras = document.querySelectorAll(".course-extra");

    extras.forEach(extra => {
        extra.addEventListener("click", (e) => {
            e.stopPropagation();

            // Close other open menus
            document.querySelectorAll(".course-extra.active").forEach(open => {
                if (open !== extra) open.classList.remove("active");
            });

            // Toggle this one
            extra.classList.toggle("active");
        });
    });


    /* ------------------------------
       MODAL LOGIC
    ------------------------------ */

    const modal = document.getElementById("courseModal");
    const closeBtn = document.querySelector(".modal-close");

    const modalTitle       = document.getElementById("modal-title");
    const modalSubtitle    = document.getElementById("modal-subtitle");
    const modalDescription = document.getElementById("modal-description");
    const modalButton    = document.getElementById("modal-button");

    // Every "Details" button inside course menus
    const detailsButtons = document.querySelectorAll(".details-course");

    detailsButtons.forEach(btn => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();

            const card = btn.closest(".course-card");

            modalTitle.textContent       = card.dataset.title;
            modalSubtitle.textContent    = card.dataset.subtitle;
            modalDescription.textContent = card.dataset.description;
            modalButton.textContent    = card.dataset.button;

            modal.style.display = "flex";

            // Close dropdown
            card.querySelector(".course-extra").classList.remove("active");
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


    /* ------------------------------
       GLOBAL CLICK = CLOSE MENUS
    ------------------------------ */

    document.addEventListener("click", () => {
        document.querySelectorAll(".course-extra.active")
            .forEach(el => el.classList.remove("active"));
    });

});
