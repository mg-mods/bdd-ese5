document.addEventListener("DOMContentLoaded", async () => {

    const grid = document.getElementById("applicationsGrid");
    const modal = document.getElementById("courseModal");

    const modalTitle = document.getElementById("modal-title");
    const modalSubtitle = document.getElementById("modal-subtitle");
    const modalDesc = document.getElementById("modal-description");
    const modalResume = document.getElementById("modal-resume");

    const btnReject = document.getElementById("modal-reject");
    const btnAccept = document.getElementById("modal-accept");
    const btnContact = document.getElementById("modal-contact");

    let currentApplicationID = null;
    let currentStatus = null;

    /* =====================
       STATUS CONFIG
    ===================== */
    const STATUS = {
        0: { color: "white", label: "Nouvelle" },
        1: { color: "grey", label: "En cours de lecture" },
        3: { color: "red", label: "Rejetée" },
        4: { color: "blue", label: "Contactée" },
        5: { color: "green", label: "Acceptée" }
    };

    /* =====================
       LOAD APPLICATIONS
    ===================== */
    const loadApplications = async () => {
        grid.innerHTML = "";

        const res = await fetch("application_list.php");
        const data = await res.json();

        if (!data.success) return;

        data.applications.forEach(app => {
            const card = document.createElement("div");
            card.className = "course-card";
            card.dataset.id = app.ApplicationID;
            card.dataset.status = app.ApplicationStatus;

            const status = STATUS[app.ApplicationStatus];

            card.innerHTML = `
                <div class="status-dot"
                     style="background:${status.color}"
                     title="${status.label}">
                </div>

                <div class="course-info">
                    <p class="course-title">${app.OfferName}</p>
                    <p class="course-subtitle">
                        ${app.StudentFirstName} ${app.StudentLastName}
                    </p>
                </div>

                <div class="course-extra">⋮
                    <div class="course-menu">
                        <a class="details-course">Détails</a>
                        <a class="reject">Rejeter</a>
                        <a class="contact">Contacter</a>
                        <a class="accept">Accepter</a>
                    </div>
                </div>
            `;

            grid.appendChild(card);

            card.addEventListener("click", () => openModal(app.ApplicationID));
            card.querySelector(".details-course").onclick = e => {
                e.stopPropagation();
                openModal(app.ApplicationID);
            };

            card.querySelector(".reject").onclick = e => action(e, app.ApplicationID, "reject");
            card.querySelector(".contact").onclick = e => action(e, app.ApplicationID, "contact");
            card.querySelector(".accept").onclick = e => action(e, app.ApplicationID, "accept");
        });
    };

    /* =====================
       MODAL
    ===================== */
    const openModal = async (id) => {
        const res = await fetch(`application_modal.php?id=${id}`);
        const data = await res.json();

        if (!data.success) return;

        currentApplicationID = id;
        currentStatus = data.ApplicationStatus;

        modalTitle.textContent = data.OfferName;
        modalSubtitle.textContent =
            `${data.StudentFirstName} ${data.StudentLastName}`;
        modalDesc.textContent = data.ApplicationText;

        if (data.ResumePath) {
            modalResume.href = data.ResumePath;
            modalResume.style.display = "inline-block";
        }

        modal.style.display = "flex";

        if (currentStatus === 0) {
            fetch("application_action.php", {
                method: "POST",
                body: new URLSearchParams({
                    id,
                    action: "open"
                })
            });
        }
    };

    /* =====================
       ACTIONS
    ===================== */
    const action = async (e, id, type) => {
        e.stopPropagation();

        await fetch("application_action.php", {
            method: "POST",
            body: new URLSearchParams({
                id,
                action: type
            })
        });

        if (type !== "open") loadApplications();
        modal.style.display = "none";
    };

    btnReject.onclick = () => action(event, currentApplicationID, "reject");
    btnAccept.onclick = () => action(event, currentApplicationID, "accept");
    btnContact.onclick = () => action(event, currentApplicationID, "contact");

    document.querySelector(".modal-close").onclick = () => {
        modal.style.display = "none";
    };

    modal.onclick = e => {
        if (e.target === modal) modal.style.display = "none";
    };

    await loadApplications();
});
