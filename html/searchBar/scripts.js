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

    // Search bar
    const searchBar = document.getElementById("convSearch");
    searchBar.addEventListener("input", async (e) => {
        e.preventDefault();

        const value = searchBar.value.trim();

        const formData = new FormData();
        formData.append("searchValue", value);

        // Request
        const result = await fetch("search.php", {
            method: "POST",
            body: formData // Data
        }).then(r => r.json());

        // Process results
        if (result.success === true) {
            // If success

        } else {
            // If failed

        }
    });

});
