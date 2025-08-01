<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    //hole accountId und username aus der Session
    $accountId = $_SESSION['id'];
    $username = $_SESSION['username'];
} else {
    //wenn Nutzer nicht eingeloggt wird dieser zur login Seite weitergeleitet
    $_SESSION['info'] = "Stats";
    header("Location: login.php");
    die();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReTrack - Stats</title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/filter.css">
    <script src="./logic/stats/filterDisplay.js" defer></script>
    <script src="./logic/stats/searchSuggestions/autocomplete.js" defer></script>
    <script src="./logic/stats/showStats.js" defer></script>
</head>
<body>
    <!--Navigationsbar -->
    <nav class="navbar">
        <div class="nav-left">
            <a href="homepage.php" class="nav-link">Get Started</a>
            <a href="stats.php" class="nav-link active">Stats</a>
            <a href="fileUpload.php" class="nav-link">File Upload</a>
        </div>
        <div class="nav-right">
            <?php
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
                    //Wenn User eingeloggt zeige Accountname mit Dropdown menü
                    echo
                    "<div class='dropdown'>
                        <button class='dropbtn'>$username</button>
                        <div class='dropdown-content'>
                            <a href='logic/account/logout.php' class='logout'>Log out</a>
                        </div>
                    </div>";
                }
                else{
                    echo "<a href='login.php' class='nav-link'>Log in</a>";
                }
            ?>
        </div>
    </nav>

    
    <div class="filter-container">
        <div id="filterForm">
            <form action="stats.php" method="POST">
                <div class="filter-header">
                    <h1>Filter</h1>
                </div>

                <div class="filter-grid">
                    <div class="filter-group">
                        <label for="category">Category</label>
                        <select name="category" id="category">
                            <option value="song">Songs</option>
                            <option value="artist">Artist</option>
                            <option value="album">Album</option>
                        </select>

                        <label for="metric">Filter by</label>
                        <select name="metric" id="metric">
                            <option value="mPlayed">Most played</option>
                            <option value="mTime">Most listening time</option>
                            <option value="mfPlayed">Most fully listened to</option>
                            <option value="mSkipped">Most skipped</option>
                            <option value="mShuffle">Most played via shuffle</option>
                            <option value="mDirect">Most direct plays</option>
                        </select>
                    </div>


                    <div class="filter-group">
                        <div id="artistInputDiv">
                            <label for="artist">Artist (optional)</label>
                            <div class="search-container">
                                <input type="text" name="artist" id="artist" autocomplete="off" placeholder="Start typing...">
                                <div id="artistSuggestions" class="suggestions-box"></div>
                            </div>
                        </div>

                        <div id="albumInputDiv">
                            <label for="album">Album (optional)</label>
                            <div class="search-container">
                                <input type="text" name="album" id="album" autocomplete="off" placeholder="Start typing...">
                                <div id="albumSuggestions" class="suggestions-box"></div>
                            </div>
                        </div>
                    </div>

                    <div class="filter-group">
                        <h3>Time filter mode</h3>
                        <input type="radio" name="timeFilter" id="simple" value="simple" checked/>
                        <label for="simple">Simple</label>
                        <input type="radio" name="timeFilter" id="advanced" value="advanced"/>
                        <label for="advanced">Advanced</label>
                        <br>

                        <label for="simpleTimeSelect">Time range</label>
                        <select name="simpleTimeSelect" id="simpleTimeSelect">
                            <option value="allTime">All time</option>
                            <option value="twoWeeks">Last 2 weeks</option>
                            <option value="oneMonth">Last month</option>
                            <option value="threeMonth">Last 3 months</option>
                            <option value="sixMonth">Last 6 months</option>
                            <option value="year">Last year</option>
                        </select>
                    </div>

                </div>

                <div id="sortSettings">
                    <div class="filter-header">
                        <h1>Sort Settings</h1>
                    </div>

                    <div class="filter-grid">
                        <div class="filter-group">
                            <h3>Sort order</h3>
                            <input type="radio" name="sortOrder" id="asc" value="asc"/>
                            <label for="asc">Ascending</label>
                            <input type="radio" name="sortOrder" id="desc" value="desc" checked/>
                            <label for="desc">Descending</label>
                        </div>
                        <div class="filter-group">
                            <h3>Filter mode</h3>
                            <input type="radio" name="subMetric" id="total" value="total" checked/>
                            <label for="total">Total</label>
                            <input type="radio" name="subMetric" id="percent" value="percent"/>
                            <label for="percent">Percentage based</label>
                        </div>
                        <div class="filter-group" id="minPlaysDiv">
                            <label for="minPlays">Minimum plays</label>
                            <input type="number" name="minPlays" id="minPlays" min="0"/>
                        </div>
                    </div>
                </div>

                <div id="advancedTime">
                    <div class="filter-header">
                        <h1>Advanced Time</h1>
                    </div>

                    <div class="filter-grid">
                        <div class="filter-group">
                            <input type="checkbox" name="dateRange" id="dateRange" value="dateRange">
                            <label for="dateRange">Date range</label><br>

                            <label for="startDate">Start date</label>
                            <input type="date" name="startDate" id="startDate">
                            <label for="endDate">End date</label>
                            <input type="date" name="endDate" id="endDate">

                        </div>

                        <div class="filter-group">
                            <input type="checkbox" name="month" id="month" value="month">
                            <label for="month">Months</label><br>

                            <div class="dropdown-checklist" id="monthDropdown">
                                <div class="dropdown-button">Select Months</div>
                                <div class="dropdown-options">
                                    <label><input type="checkbox" name="months[]" value="1">January</label>
                                    <label><input type="checkbox" name="months[]" value="2">February</label>
                                    <label><input type="checkbox" name="months[]" value="3">March</label>
                                    <label><input type="checkbox" name="months[]" value="4">April</label>
                                    <label><input type="checkbox" name="months[]" value="5">May</label>
                                    <label><input type="checkbox" name="months[]" value="6">June</label>
                                    <label><input type="checkbox" name="months[]" value="7">July</label>
                                    <label><input type="checkbox" name="months[]" value="8">August</label>
                                    <label><input type="checkbox" name="months[]" value="9">September</label>
                                    <label><input type="checkbox" name="months[]" value="10">October</label>
                                    <label><input type="checkbox" name="months[]" value="11">November</label>
                                    <label><input type="checkbox" name="months[]" value="12">December</label>
                                </div>
                            </div>
                            <br>
                            <br>
                            <input type="checkbox" name="weekday" id="weekday" value="weekday">
                            <label for="weekday">Weekdays</label><br>

                            <div class="dropdown-checklist" id="weekdayDropdown">
                                <div class="dropdown-button">Select Weekdays</div>
                                <div class="dropdown-options">
                                    <label><input type="checkbox" name="weekdays[]" value="0">Monday</label>
                                    <label><input type="checkbox" name="weekdays[]" value="1">Tuesday</label>
                                    <label><input type="checkbox" name="weekdays[]" value="2">Wednesday</label>
                                    <label><input type="checkbox" name="weekdays[]" value="3">Thursday</label>
                                    <label><input type="checkbox" name="weekdays[]" value="4">Friday</label>
                                    <label><input type="checkbox" name="weekdays[]" value="5">Saturday</label>
                                    <label><input type="checkbox" name="weekdays[]" value="6">Sunday</label>
                                </div>
                            </div>
                        </div>

                        <div class="filter-group">
                            <input type="checkbox" name="time" id="time">
                            <label for="time">Time frame</label><br>

                            <label for="startTime">Start time</label>
                            <input type="time" id="startTime" name="startTime">
                            <label for="endTime">End time</label>
                            <input type="time" id="endTime" name="endTime">
                        </div>
                    </div>
                </div>
                <input type="hidden" id="accountId" name="accountId" value=<?php echo "$accountId"?>>
                <input type="submit" class="form-submit filter-btn" value="Submit Query"></input>
            </form>
        </div>
    </div>
        
    <div class="results-container" id="results">
        No filters applied yet
    </div>



</body>
</html>