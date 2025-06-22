//wird verwendet, um die Filter Anzeige zu steuern
//variablen für Category Display
const categorySelect = document.getElementById("category");

const artistInput = document.getElementById("artist");

const albumInput = document.getElementById("album");

//variablen für Metric Display
const metricSelect = document.getElementById("metric");
const sortSettingsDiv = document.getElementById("sortSettings");

const total = document.getElementById("total");
const sortOrderRadio = document.querySelectorAll("input[name='sortOrder']");
    
const subMetricRadio = document.querySelectorAll("input[name='subMetric']");

const minPlaysDiv = document.getElementById("minPlaysDiv");
const minPlays = document.getElementById("minPlays");

//variablen für simple vs advanced time filter
const simpleTimeSelect = document.getElementById("simpleTimeSelect");

const timeFilterRadio = document.querySelectorAll("input[name='timeFilter']");
const advancedTimeDiv = document.getElementById("advancedTime");
    
//varibalen für date Range Display
const dateRangeCheck = document.getElementById("dateRange");
const dateRangeStart = document.getElementById("startDate");
const dateRangeEnd = document.getElementById("endDate");
    
//varibalen für Season Display
const monthCheck = document.getElementById("month");
const monthDropdown = document.getElementById("monthDropdown");
const monthButton = document.querySelector("#monthDropdown .dropdown-button");

//varibalen für Weekday Display
const weekdayCheck = document.getElementById("weekday");
const weekdayDropdown = document.getElementById("weekdayDropdown");
const weekdayButton = document.querySelector("#weekdayDropdown .dropdown-button");

//varibalen für time Display
const timeCheck = document.getElementById("time");
const startTime = document.getElementById("startTime");
const endTime = document.getElementById("endTime")

//funktion, die Anzeige von Category abhängigen Filtern steuert
function updateSearchInput(){
    //momentanen category Wert holen
    const category = categorySelect.value;

    switch(category){
        case "song":
            //blockiere artist oder album eingabe wenn in eines der beiden getippt wird
            if (artistInput.value){
                albumInput.disabled = true;
                artistInput.disabled = false;
            }
            else if (albumInput.value){
                artistInput.disabled = true;
                albumInput.disabled = false;
            }
            else{
                albumInput.disabled = false;
                artistInput.disabled = false;
            }
            break;
            
        case "artist":
            //verstecke artist und album eingabe
            albumInput.disabled = true;
            artistInput.disabled = true;

            //setzte album/artist Wert zurück
            albumInput.value ="";
            artistInput.value ="";
            break;
            
        case "album":
            //verstecke album und zeige artist eingabe
            artistInput.disabled = false;
            albumInput.disabled = true;

            //setzte album Wert zurück
            albumInput.value ="";
            break;
    }
}

//funktion, die Anzeige von Metric abhängigen Filtern steuert
function updateMetricDisplay(){
    //momentanen Wert bekommen
    const metric = metricSelect.value;

    if (metric == "mPlayed" || metric == "mTime"){
        //verstecke die Filter, die hier keinen Sinn machen
        sortSettingsDiv.classList.add("hidden");
        minPlaysDiv.classList.add("hidden");
        minPlays.required = false;

        //setze subMetric zurück
        total.checked = true;
    }
    else{
        //zeige subMetric radios
        sortSettingsDiv.classList.remove("hidden");

        const subMetric = document.querySelector("input[name='subMetric']:checked").value;
        const sortOrder = document.querySelector("input[name='sortOrder']:checked").value;

        //unterscheide ob minimum Plays angezeigt wird oder nicht
        if (subMetric == "percent" || sortOrder == "asc"){
            minPlaysDiv.classList.remove("hidden");
            minPlays.required = true;
        }
        else{
           minPlaysDiv.classList.add("hidden");
           minPlays.required = false;
        }
    }
}
//funktion, die Anzeige von Zeit abhängigen Filtern steuert
function updateTimeFilterDisplay(){
    //hole Wert von timeFilterMode
    const timeFilter = document.querySelector("input[name='timeFilter']:checked").value;

    if(timeFilter == "simple"){
        //verstecke advanced settings
        advancedTimeDiv.classList.add("hidden");

        //aktiviere simpleTimeSelect
        simpleTimeSelect.disabled = false;

        //für animation wichtig
        advancedTimeDiv.classList.remove("overflow");

        //setzte required Status von dateRange zurück
        dateRangeStart.required = false;
        dateRangeEnd.required = false;
    }
    else{
        //zeige advanced settings
        advancedTimeDiv.classList.remove("hidden");
        setTimeout(() => {
            //für animation wichtig
            advancedTimeDiv.classList.add("overflow");
        }, 300);
    }
}
//funktion für DateRange
function updateDateRangeDisplay(){
    if(dateRangeCheck.checked){
        //aktiviere dateRange inputs
        dateRangeStart.disabled = false;
        dateRangeEnd.disabled = false;

        //setzte dateRange inputs auf required
        dateRangeStart.required = true;
        dateRangeEnd.required = true;

        //deaktiviere simpleTimeSelect
        simpleTimeSelect.disabled = true;
    }
    else{
        //deaktiviere dateRange inputs
        dateRangeStart.disabled = true;
        dateRangeEnd.disabled = true;

        //setzte dateRange inputs auf nicht required
        dateRangeStart.required = false;
        dateRangeEnd.required = false;

        //aktiviere simpleTimeSelect
        simpleTimeSelect.disabled = false; 
    }
}
//funktion für Month Checklist
function updateMonthDisplay() {
    if (monthCheck.checked) {
        //aktiviere Dropdownmenü
        monthDropdown.classList.remove("disabled");
    } else {
        //deaktiviere Dropdownmenü 
        monthDropdown.classList.add("disabled");
        //schließ es, falls offen
        monthDropdown.classList.remove("open");
    }
}
//funktion für Weekday Checklist
function updateWeekdayDisplay() {
    if (weekdayCheck.checked) {
        //aktiviere Dropdownmenü
        weekdayDropdown.classList.remove("disabled");
    } else {
        //deaktiviere Dropdownmenü 
        weekdayDropdown.classList.add("disabled");
        //schließ es, falls offen
        weekdayDropdown.classList.remove("open");
    }
}
//funktion für Time inputs
function updateTimeDisplay(){
    if(timeCheck.checked){
        //aktiviere time inputs
        startTime.disabled = false;
        endTime.disabled = false;
    }
    else{
        //deaktiviere time inputs
        startTime.disabled = true;
        endTime.disabled = true;  
    }
}
//eventListeners
//updateSearchInput wird aufgerufen wenn sich etwas bei category, artist oder album ändert
categorySelect.addEventListener("change", updateSearchInput);
artistInput.addEventListener("change", updateSearchInput);
albumInput.addEventListener("change", updateSearchInput);

//updateMetricDisplay wird aufgerufen wenn sich etwas bei metric, sortOrder oder subMetric ändert
metricSelect.addEventListener("change", updateMetricDisplay);
sortOrderRadio.forEach(radio =>{
    radio.addEventListener("change", updateMetricDisplay)
})
subMetricRadio.forEach(radio =>{
    radio.addEventListener("change", updateMetricDisplay)
})


timeFilterRadio.forEach(radio =>{
    //ruft beide auf, damit required und aktiviert immer stimmt
    radio.addEventListener("change", updateDateRangeDisplay)
    radio.addEventListener("change", updateTimeFilterDisplay)
})

dateRangeCheck.addEventListener("change", updateDateRangeDisplay);
monthCheck.addEventListener("change", updateMonthDisplay);
weekdayCheck.addEventListener("change", updateWeekdayDisplay);
timeCheck.addEventListener("change", updateTimeDisplay);

//funktionen, damit Dropdown Checklist angezeigt wird
monthButton.addEventListener("click", () => {
    const monthDropdown = document.getElementById("monthDropdown");
    if (!monthDropdown.classList.contains("disabled")) {
        //wenn monthDropdown button aktiviert ist, öffnet sich das Dropdown auf klick
        monthDropdown.classList.toggle("open");
    }
});
document.addEventListener("click", function (e) {
    const monthDropdown = document.getElementById("monthDropdown");
    if (!monthDropdown.contains(e.target)) {
        //schließe monthDropdown wenn es einen klick außerhalb des Dropdowns ist
        monthDropdown.classList.remove("open");
    }
});

//funktionen, damit Dropdown Checklist angezeigt wird
weekdayButton.addEventListener("click", () => {
    const weekdayDropdown = document.getElementById("weekdayDropdown");
    if (!weekdayDropdown.classList.contains("disabled")) {
        //wenn weekdayDropdown button aktiviert ist, öffnet sich das Dropdown auf klick
        weekdayDropdown.classList.toggle("open");
    }
});
document.addEventListener("click", function (e) {
    const weekdayDropdown = document.getElementById("weekdayDropdown");
    if (!weekdayDropdown.contains(e.target)) {
        //schließe weekdayDropdown wenn es einen klick außerhalb des Dropdowns ist
        weekdayDropdown.classList.remove("open");
    }
});

//Schließ/öffnen animationen werden erst hinzugefügt, wenn Seite komplett geladen ist
document.addEventListener("DOMContentLoaded", () => {
  advancedTimeDiv.classList.add("animate");
  sortSettingsDiv.classList.add("animate");
  minPlaysDiv.classList.add("animate");
});

//initialisierung
updateSearchInput();
updateMetricDisplay();
updateTimeFilterDisplay(); 
updateDateRangeDisplay();
updateTimeDisplay();
updateMonthDisplay();
updateWeekdayDisplay();