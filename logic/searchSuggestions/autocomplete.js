function getArtistSuggestions(searchTerm, accountId) {
    return fetch(`./logic/searchSuggestions/getArtistSuggestions.php?term=${encodeURIComponent(searchTerm)}&accountId=${encodeURIComponent(accountId)}`)
        .then(response => response.json());
}

function getAlbumSuggestions(searchTerm, accountId) {
    return fetch(`./logic/searchSuggestions/getAlbumSuggestions.php?term=${encodeURIComponent(searchTerm)}&accountId=${encodeURIComponent(accountId)}`)
        .then(response => response.json());
}

function setupAutocomplete(inputElement, datalistElement, fetchFunction, additionalParam = null) {
    inputElement.addEventListener('input', async function() {
        const searchTerm = this.value.trim();
        if (searchTerm.length < 2) {
            datalistElement.innerHTML = '';
            return;
        }
        try {
            const suggestions = additionalParam 
                ? await fetchFunction(searchTerm, additionalParam) 
                : await fetchFunction(searchTerm);
            datalistElement.innerHTML = '';
            suggestions.forEach(suggestion => {
                const option = document.createElement('option');
                option.value = suggestion;
                datalistElement.appendChild(option);
            });
        } catch (error) {
            console.error('Error fetching suggestions:', error);
        }
    });


    // Neue Enter-Handler für Autocomplete
    inputElement.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Verhindert Formular-Absendung
            
            // Holt den ersten Vorschlag aus der Datalist
            const options = datalistElement.querySelectorAll('option');
            if (options.length > 0) {
                this.value = options[0].value;
                datalistElement.innerHTML = ''; // Leert die Vorschläge
            }
        }
    });
}

// Initialisierung der Autocomplete-Funktionen
document.addEventListener('DOMContentLoaded', function() {
    const artistInput = document.getElementById('artist');
    const artistSuggestions = document.getElementById('artistSuggestions');
    const albumInput = document.getElementById('album');
    const albumSuggestions = document.getElementById('albumSuggestions');
    const accountIdInput = document.getElementById('accountId');
    
    if (artistInput && artistSuggestions && accountIdInput) {
        setupAutocomplete(artistInput, artistSuggestions, getArtistSuggestions, accountIdInput.value);
    }
    
    if (albumInput && albumSuggestions && accountIdInput) {
        setupAutocomplete(albumInput, albumSuggestions, getAlbumSuggestions, accountIdInput.value);
    }
});
