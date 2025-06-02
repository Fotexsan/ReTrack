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

//variablen für simple vs advanced time filter
const simpleTimeSelect = document.getElementById("simpleTimeSelect");

const timeFilterRadio = document.querySelectorAll("input[name='timeFilter']");
const advancedTimeDiv = document.getElementById("advancedTime");
    
//varibalen für date Range Display
const dateRangeCheck = document.getElementById("dateRange");
const dateRangeStart = document.getElementById("startDate");
const dateRangeEnd = document.getElementById("endDate");
    
//varibalen für Season Display
const seasonCheck = document.getElementById("season");
const season = document.getElementById("seasonSelect");

//varibalen für Weekday Display
const weekdayCheck = document.getElementById("weekday");
const weekday = document.getElementById("weekdaySelect");

//varibalen für time Display
const timeCheck = document.getElementById("time");
const startTime = document.getElementById("startTime");
const endTime = document.getElementById("endTime")

//funktion, die Anzeige von Category abhängigen Filtern steuert
function updateSearchInput(){
    const category = categorySelect.value;

    switch(category){
        case "song":
            //blockiere artist oder album eingabe
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

            albumInput.value ="";
            artistInput.value ="";
            break;
            
        case "album":
            //verstecke album und zeige artist eingabe
            artistInput.disabled = false;
            albumInput.disabled = true;

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

        //setze subMetrix zurück
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
        }
        else{
           minPlaysDiv.classList.add("hidden");
        }
    }
}

function updateTimeFilterDisplay(){
    const timeFilter = document.querySelector("input[name='timeFilter']:checked").value;
    if(timeFilter == "simple"){
        advancedTimeDiv.classList.add("hidden");
        simpleTimeSelect.disabled = false;
    }
    else{
        advancedTimeDiv.classList.remove("hidden");
    }
}

function updateDateRangeDisplay(){
    if(dateRangeCheck.checked){
        dateRangeStart.disabled = false;
        dateRangeEnd.disabled = false;

        simpleTimeSelect.disabled = true;
    }
    else{
        dateRangeStart.disabled = true;
        dateRangeEnd.disabled = true;

        simpleTimeSelect.disabled = false; 
    }
}

function updateSeasonDisplay(){
    if(seasonCheck.checked){
        season.disabled = false;
    }
    else{
        season.disabled = true;   
    }
}

function updateWeekdayDisplay(){
    if(weekdayCheck.checked){
        weekday.disabled = false;
    }
    else{
        weekday.disabled = true;    
    }
}

function updateTimeDisplay(){
    if(timeCheck.checked){
        startTime.disabled = false;
        endTime.disabled = false;
    }
    else{
        startTime.disabled = true;
        endTime.disabled = true;  
    }
}

categorySelect.addEventListener("change", updateSearchInput);
artistInput.addEventListener("change", updateSearchInput);
albumInput.addEventListener("change", updateSearchInput);

metricSelect.addEventListener("change", updateMetricDisplay);

sortOrderRadio.forEach(radio =>{
    radio.addEventListener("change", updateMetricDisplay)
})

subMetricRadio.forEach(radio =>{
    radio.addEventListener("change", updateMetricDisplay)
})

timeFilterRadio.forEach(radio =>{
    radio.addEventListener("change", updateTimeFilterDisplay)
})

dateRangeCheck.addEventListener("change", updateDateRangeDisplay);
seasonCheck.addEventListener("change", updateSeasonDisplay);
weekdayCheck.addEventListener("change", updateWeekdayDisplay);
timeCheck.addEventListener("change", updateTimeDisplay);

//Schließ/öffnen animationen werden erst nach vollständigem laden hinzugefügt
document.addEventListener("DOMContentLoaded", () => {
  // Erst nach dem Laden die Transition aktivieren
  advancedTimeDiv.classList.add("animate");
  sortSettingsDiv.classList.add("animate");
  minPlaysDiv.classList.add("animate");
});

//initialisierung
updateSearchInput();
updateMetricDisplay();
updateTimeFilterDisplay(); 
updateDateRangeDisplay();
updateSeasonDisplay();
updateWeekdayDisplay();
updateTimeDisplay();