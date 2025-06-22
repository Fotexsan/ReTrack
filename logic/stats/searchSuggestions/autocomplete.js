//Diese js Funktion ist nicht von uns selbst geschrieben, da wir dafür js nicht gut genug können
//Wir fanden diese Art der implementierung aber essentiell, um den Album/Künstler Filter angenehm nutzbar zu machen
//Kommentare sind von uns vom nachvollziehen
function setupCustomAutocomplete(inputId, suggestionBoxId, fetchUrl, isAlbumMode = false) {
    //hole eingabefeld
    const input = document.getElementById(inputId);
    //hole Vorschlagsbox
    const suggestionBox = document.getElementById(suggestionBoxId);

    let currentRequest = 0;
    let selectedIndex = -1;
    let currentSuggestions = [];

    //auslöser, wenn etwas getippt wird
    input.addEventListener("input", async () => {
        //entfernt vorne und hinten leerzeichen
        const term = input.value.trim();

        //löscht inhalt der vorschlagsbox
        suggestionBox.innerHTML = "";
        //versteckt vorschlagsbox
        suggestionBox.style.display = "none";
        //setzt Keyboard Control zurück
        selectedIndex = -1;

        //abbruch wenn weniger als 2 Zeichen eingegeben sind
        if (term.length < 2) return;

        //es soll nur die letzte Anfrage angezeigt werden 
        //bei schnellem tippen läuft sonst die Vorschlagsbox mit identischen Vorschlägen voll
        //deshalb müssen Requests unterschieden werden
        currentRequest++;
        const thisRequest = currentRequest;

        try {
            //auf Antwort von getAlbumSuggestions.php oder getArtistSuggestions.php warten
            const response = await fetch(`${fetchUrl}?term=${encodeURIComponent(term)}`);
            //Antwort entschlüsseln
            const suggestions = await response.json();

            //abrrechen wenn request nicht die neueste ist
            if (thisRequest !== currentRequest) return;

            //für Keyboard Control
            currentSuggestions = suggestions;
            
            //abrrechen wenn suggestions leer ist
            if (suggestions.length === 0) return;

            //Vorschlagsliste aufbauen
            suggestions.forEach((suggestion) => {
                //für jeden Eintrag wird ein Div mit Klasse suggestion-item erstellt
                const item = document.createElement("div");
                item.classList.add("suggestion-item");

                let displayText;

                if (isAlbumMode){
                    //Für Alben wird Albumsname und Künstlername angeziegt
                    displayText = suggestion.album + " - " + suggestion.artist;;
                }
                else{
                    //Für Künstler wird nur der Künstlername angezeigt
                    displayText = suggestion;
                }

                //Inhalt des erstellten Divs wird auf Vorschlag gesetzt
                item.textContent = displayText;

                //Beim klicken auf einen Vorschlag wird dieser in das Feld eingefügt (Bei Album nur den Albumsnamen)
                //Vorschlagsliste wird dann geschlossen
                item.addEventListener("click", () => {
                    input.value = isAlbumMode ? suggestion.album : suggestion;
                    suggestionBox.innerHTML = "";
                    suggestionBox.style.display = "none";
                });
                //Vorschlag wird zur Vorschlagsliste hinzugefügt
                suggestionBox.appendChild(item);
            });
            //Vorschlagsliste anzeigen
            suggestionBox.style.display = "block";
        } catch (error) {
            console.error("Autocomplete-Fehler:", error);
        }
    });

    //Keyboard Control für search Suggestions
    input.addEventListener("keydown", (e) => {
        //hole Liste mit allen momentan angeziegten Vorschlägen
        const items = suggestionBox.querySelectorAll(".suggestion-item");

        //Macht die seach Suggestions mit Arrowkeys scrollbar
        if (e.key === "ArrowDown") {
            //abrrechen, wenn Liste leer ist
            if (items.length === 0) return;
            //cursor soll nicht mitspringen
            e.preventDefault();
            //erhöt selectedIndex, ist dabei durch % items.length zyklisch
            selectedIndex = (selectedIndex + 1) % items.length;
            updateHighlight(items);
        }

        else if (e.key === "ArrowUp") {
            //abrrechen, wenn Liste leer ist
            if (items.length === 0) return;
            //cursor soll nicht mitspringen
            e.preventDefault();
            //verringert selectedIndex, ist dabei durch % items.length zyklisch
            selectedIndex = (selectedIndex - 1 + items.length) % items.length;
            updateHighlight(items);
        }

        //Enter wählt Ergebnis automatisch aus
        else if (e.key === "Enter") {
            if (items.length > 0 && selectedIndex >= 0) {
                //es gibt vorschläge und es ist einer mit Arrowkeys markiert
                e.preventDefault();
                //wähle ausgewählten Vorschlag aus
                const selected = currentSuggestions[selectedIndex];

                //setze Eingabefeld auf Wert von Vorschlag
                if (isAlbumMode){
                    input.value = selected.album;
                }
                else{
                    input.value = selected;
                }

                //Vorschlagsbox leeren und ausblenden
                suggestionBox.innerHTML = "";
                suggestionBox.style.display = "none";
                //selectedIndec zurücksetzen
                selectedIndex = -1;
            } else if (items.length > 0) {
                e.preventDefault();
                const selected = currentSuggestions[0];
                //setze Eingabefeld auf Wert von Vorschlag
                if (isAlbumMode){
                    input.value = selected.album;
                }
                else{
                    input.value = selected;
                }
                //Vorschlagsbox leeren und ausblenden
                suggestionBox.innerHTML = "";
                suggestionBox.style.display = "none";
                //selectedIndec zurücksetzen
                selectedIndex = -1;
            }
        }
    });

    //Markiert von Arrowkeys ausgewähltes Ergebnis
    //scrollt mit ausgewähltem Ergebnis mit
    function updateHighlight(items) {
        items.forEach((item, index) => {
            if (index === selectedIndex) {
                //wenn ausgewählt hinscrollen und highlight hinzufügen
                item.classList.add("highlight");
                item.scrollIntoView({ block: "nearest" });
            } else {
                //wenn nicht ausgewählt, highlight entfernen
                item.classList.remove("highlight");
            }
        });
    }

    //Schließt Vorschlagsliste, wenn woanderst hingeklickt wird
    document.addEventListener("click", (e) => {
        if (!input.contains(e.target) && !suggestionBox.contains(e.target)) {
            suggestionBox.style.display = "none";
            selectedIndex = -1;
        }
    });
}

//aufrufen von setupCustomAutocomplete Funktion
window.addEventListener("DOMContentLoaded", () => {
    setupCustomAutocomplete("artist", "artistSuggestions", "./logic/stats/searchSuggestions/getArtistSuggestions.php");
    setupCustomAutocomplete("album", "albumSuggestions", "./logic/stats/searchSuggestions/getAlbumSuggestions.php", true);
});