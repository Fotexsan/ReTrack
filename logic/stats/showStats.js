document.querySelector("form").addEventListener("submit", async function (event) {

    event.preventDefault(); // Verhindert Neuladen der Seite

    const form = event.target;
    const formData = new FormData(form);
    
    document.getElementById("results").innerHTML = "Loading...";
    const response = await fetch('./logic/stats/showStats.php', {
        method: 'POST',
        body: formData
    });

    const resultHTML = await response.text();

    document.getElementById("results").innerHTML = resultHTML;
});