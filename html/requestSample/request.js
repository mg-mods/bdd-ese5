// Detect page load
document.addEventListener("DOMContentLoaded", () => {

    // Element to target using its HTML ID
    const elementName = document.getElementById("elementIdInHtml");

    // Wait for event
    elementName.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Use if form: transform data
        const formData = new FormData(elementName);

        // Request
        const result = await fetch("request.php", {
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
