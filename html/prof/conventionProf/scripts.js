document.addEventListener("DOMContentLoaded", () => {
    // Toggle description
    const convCards = document.querySelectorAll(".conv-card");
    convCards.forEach(card => {
        card.addEventListener("click", (e) => {
            if (!e.target.classList.contains("btn")) {
                card.classList.toggle("expanded");
            }
        });
    });

    // Filtre de recherche
    const convSearch = document.getElementById("convSearch");
    convSearch.addEventListener("input", () => {
        const query = convSearch.value.toLowerCase();
        convCards.forEach(card => {
            const student = card.querySelector(".student-name").textContent.toLowerCase();
            const company = card.querySelector(".company-name").textContent.toLowerCase();
            const title = card.querySelector(".stage-title").textContent.toLowerCase();

            if (student.includes(query) || company.includes(query) || title.includes(query)) {
                card.style.display = "flex";
            } else {
                card.style.display = "none";
            }
        });
    });
});
