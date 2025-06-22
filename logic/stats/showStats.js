//wird verwendet, damit beim abschicken der Filterdaten nicht die gesamte Seite neugeladen wird
//wird bei klicken auf submit button ausgelöst
document.querySelector("form").addEventListener("submit", async function (event) {
    //normales neuladen verhindern
    event.preventDefault();

    //formularfeld
    const form = event.target;
    //formular Daten werden geholt
    const formData = new FormData(form);
    
    //wird angezeigt während Daten verarbeitet werden
    document.getElementById("results").innerHTML = "This could take a moment...";

    //Daten werden an showStats.php gesendet
    const response = await fetch('./logic/stats/showStats.php', {
        method: 'POST',
        body: formData
    });

    //auf Antwort von showStats.php warten
    const resultHTML = await response.text();

    //Antwort von showStats.php in Div schreiben
    document.getElementById("results").innerHTML = resultHTML;
});