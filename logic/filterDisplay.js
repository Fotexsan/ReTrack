//variablen für Category Display
const categorySelect = document.getElementById("category");

const artistDiv = document.getElementById("artistInputDiv");
const artistInput = document.getElementById("artist");

const albumDiv = document.getElementById("albumInputDiv");
const albumInput = document.getElementById("album");

//variablen für Metric Display
const metricSelect = document.getElementById("metric");

const total = document.getElementById("total");

const sortOrderDiv = document.getElementById("sortOrderDiv");
const sortOrderRadio = document.querySelectorAll("input[name='sortOrder']");
    
const subMetricDiv = document.getElementById("subMetricDiv");
const subMetricRadio = document.querySelectorAll("input[name='subMetric']");

const minPlaysDiv = document.getElementById("minPlaysDiv");
    
//varibalen für date Range Display
const dateRangeCheck = document.getElementById("dateRange");
const dateRangeDiv = document.getElementById("dateRangeDiv");
    
//varibalen für Season Display
const seasonCheck = document.getElementById("season");
const seasonDiv = document.getElementById("seasonDiv");

//varibalen für Weekday Display
const weekdayCheck = document.getElementById("weekday");
const weekdayDiv = document.getElementById("weekdayDiv");

//varibalen für time Display
const timeCheck = document.getElementById("time");
const timeDiv = document.getElementById("timeDiv");

//funktion, die Anzeige von Category abhängigen Filtern steuert
function updateCategoryDisplay(){
    const category = categorySelect.value;

    switch(category){
        case "song":
            //zeige die artist und album eingabe
            artistDiv.classList.remove("hidden");
            albumDiv.classList.remove("hidden");
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
            artistDiv.classList.add("hidden");
            albumDiv.classList.add("hidden");

            //setze Werte beider Felder zurück
            albumInput.value ="";
            artistInput.value ="";
            break;
            
        case "album":
            //verstecke album und zeige artist eingabe
            artistDiv.classList.remove("hidden");
            albumDiv.classList.add("hidden");

            //schalte artist eingabe frei
            artistInput.disabled = false;
            //setze album eingabe zurück
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
        subMetricDiv.classList.add("hidden");
        minPlaysDiv.classList.add("hidden");
        sortOrderDiv.classList.add("hidden")

        //setze subMetrix zurück
        total.checked = true;
    }
    else{
        //zeige subMetric radios
        subMetricDiv.classList.remove("hidden");
        sortOrderDiv.classList.remove("hidden");

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

function updateDateRangeDisplay(){
    if(dateRangeCheck.checked){
        dateRangeDiv.classList.remove("hidden");
    }
    else{
        dateRangeDiv.classList.add("hidden");    
    }
}

function updateSeasonDisplay(){
    if(seasonCheck.checked){
        seasonDiv.classList.remove("hidden");
    }
    else{
        seasonDiv.classList.add("hidden");    
    }
}

function updateWeekdayDisplay(){
    if(weekdayCheck.checked){
        weekdayDiv.classList.remove("hidden");
    }
    else{
        weekdayDiv.classList.add("hidden");    
    }
}

function updateTimeDisplay(){
    if(timeCheck.checked){
        timeDiv.classList.remove("hidden");
    }
    else{
        timeDiv.classList.add("hidden");    
    }
}

categorySelect.addEventListener("change", updateCategoryDisplay);
artistInput.addEventListener("change", updateCategoryDisplay);
albumInput.addEventListener("change", updateCategoryDisplay);

metricSelect.addEventListener("change", updateMetricDisplay);

sortOrderRadio.forEach(radio =>{
    radio.addEventListener("change", updateMetricDisplay)
})

subMetricRadio.forEach(radio =>{
    radio.addEventListener("change", updateMetricDisplay)
})

dateRangeCheck.addEventListener("change", updateDateRangeDisplay);
seasonCheck.addEventListener("change", updateSeasonDisplay);
weekdayCheck.addEventListener("change", updateWeekdayDisplay);
timeCheck.addEventListener("change", updateTimeDisplay);

//initialisierung
updateCategoryDisplay();
updateMetricDisplay();
updateDateRangeDisplay();
updateSeasonDisplay();
updateWeekdayDisplay();
updateTimeDisplay();   