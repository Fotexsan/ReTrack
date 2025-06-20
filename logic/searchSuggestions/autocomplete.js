function setupCustomAutocomplete(inputId, suggestionBoxId, fetchUrl, isAlbumMode = false) {
    const input = document.getElementById(inputId);
    const suggestionBox = document.getElementById(suggestionBoxId);
    let currentRequest = 0;
    let selectedIndex = -1;
    let currentSuggestions = [];

    input.addEventListener("input", async () => {
        const term = input.value.trim();

        suggestionBox.innerHTML = "";
        suggestionBox.style.display = "none";
        selectedIndex = -1;

        if (term.length < 2) return;

        currentRequest++;
        const thisRequest = currentRequest;

        try {
            const response = await fetch(`${fetchUrl}?term=${encodeURIComponent(term)}`);
            const suggestions = await response.json();

            if (thisRequest !== currentRequest) return;

            // Für Album: Objekte, für Artist: Strings
            const seen = new Set();
            const uniqueSuggestions = suggestions.filter(s => {
                const key = isAlbumMode ? `${s.album} – ${s.artist}` : s;
                if (seen.has(key)) return false;
                seen.add(key);
                return true;
            });

            currentSuggestions = uniqueSuggestions;

            if (uniqueSuggestions.length === 0) return;

            uniqueSuggestions.forEach((suggestion, index) => {
                const item = document.createElement("div");
                item.classList.add("suggestion-item");

                const displayText = isAlbumMode
                    ? `${suggestion.album} – ${suggestion.artist}`
                    : suggestion;

                item.textContent = displayText;

                item.addEventListener("click", () => {
                    input.value = isAlbumMode ? suggestion.album : suggestion;
                    suggestionBox.innerHTML = "";
                    suggestionBox.style.display = "none";
                });

                suggestionBox.appendChild(item);
            });

            suggestionBox.style.display = "block";
        } catch (error) {
            console.error("Autocomplete-Fehler:", error);
        }
    });

    input.addEventListener("keydown", (e) => {
        const items = suggestionBox.querySelectorAll(".suggestion-item");

        if (e.key === "ArrowDown") {
            if (items.length === 0) return;
            e.preventDefault();
            selectedIndex = (selectedIndex + 1) % items.length;
            updateHighlight(items);
        }

        else if (e.key === "ArrowUp") {
            if (items.length === 0) return;
            e.preventDefault();
            selectedIndex = (selectedIndex - 1 + items.length) % items.length;
            updateHighlight(items);
        }

        else if (e.key === "Enter") {
            if (items.length > 0 && selectedIndex >= 0) {
                e.preventDefault();
                const selected = currentSuggestions[selectedIndex];
                input.value = isAlbumMode
                    ? selected.album
                    : selected;
                suggestionBox.innerHTML = "";
                suggestionBox.style.display = "none";
                selectedIndex = -1;
            } else if (items.length > 0) {
                e.preventDefault();
                const selected = currentSuggestions[0];
                input.value = isAlbumMode
                    ? selected.album
                    : selected;
                suggestionBox.innerHTML = "";
                suggestionBox.style.display = "none";
                selectedIndex = -1;
            }
        }
    });

    document.addEventListener("click", (e) => {
        if (!input.contains(e.target) && !suggestionBox.contains(e.target)) {
            suggestionBox.style.display = "none";
            selectedIndex = -1;
        }
    });

    function updateHighlight(items) {
        items.forEach((item, index) => {
            if (index === selectedIndex) {
                item.classList.add("highlight");
                item.scrollIntoView({ block: "nearest" });
            } else {
                item.classList.remove("highlight");
            }
        });
    }
}


window.addEventListener("DOMContentLoaded", () => {
    setupCustomAutocomplete("artist", "artistSuggestions", "./logic/searchSuggestions/getArtistSuggestions.php");
    setupCustomAutocomplete("album", "albumSuggestions", "./logic/searchSuggestions/getAlbumSuggestions.php", true);
});