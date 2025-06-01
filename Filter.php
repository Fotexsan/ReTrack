<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $accountId = $_SESSION['id'];
    $username = $_SESSION['username'];
} else {
    $_SESSION['info'] = "Stats";
    header("Location: login.php");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReTrack</title>
    <link rel="stylesheet" href="./css/reTrack.css">

    <script src="./logic/searchSuggestions/autocomplete.js" defer></script>
    <script src="./logic/filterDisplay.js" defer></script>
</head>
<body>
    <nav class="navbar">
        <div class="nav-left">
            <a href="homepage.php" class="nav-link">Home</a>
            <a href="stats.php" class="nav-link active">Stats</a>
            <a href="fileUpload.php" class="nav-link">File Upload</a>
            <a href="help.php" class="nav-link">Help</a>
        </div>
        <div class="nav-right">
            <?php
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
                    echo
                    "<div class='dropdown'>
                        <button class='dropbtn'>$username</button>
                        <div class='dropdown-content'>
                            <a href='logic/logout.php' class='logout'>Log out</a>
                        </div>
                    </div>";
                }
                else{
                    echo "<a href='login.php' class='nav-link'>Log in</a>";
                }
            ?>
        </div>
    </nav>

    <div class="content">
        <div class="filters">
            <h1>Filter</h1>
            <form>
                <label for="category">Category</label>
                <select name="category" id="category">
                    <option value="song">Songs</option>
                    <option value="artist">Artist</option>
                    <option value="album">Album</option>
                </select>
                <br><br>
                <label for="metric">Filter by:</label>
                <select name="metric" id="metric">
                    <option value="mPlayed">Most played</option>
                    <option value="mTime">Most listening time</option>
                    <option value="mfPlayed">Most fully listened to</option>
                    <option value="mSkipped">Most skipped</option>
                    <option value="mShuffle">Most played via shuffle</option>
                    <option value="mDirect">Most direct plays</option>
                </select>
                <br><br>
                <div id="sortOrderDiv">
                    <label for="desc">Descending</label>
                    <input type="radio" name="sortOrder" id="desc" value="desc" checked/><br>
                    <label for="asc">Ascending</label>
                    <input type="radio" name="sortOrder" id="asc" value="asc"/><br>
                </div>

                <div id="subMetricDiv">
                    <label for="total">Total</label>
                    <input type="radio" name="subMetric" id="total" value="total" checked/><br>
                    <label for="percent">Percentage-Based</label>
                    <input type="radio" name="subMetric" id="percent" value="percent"/><br>
                </div>
                <br>
                <div id="minPlaysDiv">
                    <label for="minPlays">Minimum plays:</label>
                    <input type="int" name="minPlays" id="minPlays" min="0"/>
                </div>
                <br>
                <br>

                <!--werden beide zu Suchzeilen-->
                <div id="artistInputDiv">
                    <label for="artist">Artist (optional)</label>
                    <input type="text" name="artist" id="artist" autocomplete="off" list="artistSuggestions">
                    <datalist id="artistSuggestions"></datalist>
                </div>
                <br><br>
                <div id="albumInputDiv">
                    <label for="album">Album (optional)</label>
                    <input type="text" name="album" id="album" autocomplete="off" list="albumSuggestions">
                    <datalist id="albumSuggestions"></datalist>
                </div>
                <br><br>

                Time Filters (optional):
                <label for="dateRange">Date range</label>
                <input type="checkbox" name="dateRange" id="dateRange" value="dateRange"><br><br>

                <div id="dateRangeDiv">
                    <label for="startDate">Start date</label>
                    <input type="date" name="startDate" id="startDate">
                    <label for="endDate">End date</label>
                    <input type="date" name="endDate" id="endDate">
                </div>
                <br><br>

                <label for="season">Season</label>
                <input type="checkbox" name="season" id="season" value="season">
                <br><br>

                <div id="seasonDiv">
                    <label for="spring">Spring</label>
                    <input type="checkbox" name="spring" id="spring" value="spring"><br>
                    <label for="summer">Summer</label>
                    <input type="checkbox" name="summer" id="summer" value="summer"><br>
                    <label for="fall">Fall</label>
                    <input type="checkbox" name="fall" id="fall" value="fall"><br>
                    <label for="winter">Winter</label>
                    <input type="checkbox" name="winter" id="winter" value="winter"><br>
                </div>
                <br>


                <label for="weekday">Weekdays</label>
                <input type="checkbox" name="weekday" id="weekday">

                <div id="weekdayDiv">
                    <label for="monday">Monday</label>
                    <input type="checkbox" name="monday" id="monday" value="monday"><br>
                    <label for="tuesday">Tuesday</label>
                    <input type="checkbox" name="tuesday" id="tuesday" value="tuesday"><br>
                    <label for="wednesday">Wednesday</label>
                    <input type="checkbox" name="wednesday" id="wednesday" value="wednesday"><br>
                    <label for="thursday">Thursday</label>
                    <input type="checkbox" name="thursday" id="thursday" value="thursday"><br>
                    <label for="friday">Friday</label>
                    <input type="checkbox" name="friday" id="friday" value="friday"><br>
                    <label for="saturday">Saturday</label>
                    <input type="checkbox" name="saturday" id="saturday" value="saturday"><br>
                    <label for="sunday">Sunday</label>
                    <input type="checkbox" name="sunday" id="sunday" value="sunday"><br>
                </div>


                <label for="time">Time frame</label>
                <input type="checkbox" name="time" id="time">

                <div id="timeDiv">
                    <label for="startTime">Start time</label>
                    <input type="time" id="startTime" name="startTime">
                    <label for="endTime">End time</label>
                    <input type="time" id="endTime" name="endTime">
                </div>
                <br><br>
                <input type="hidden" id="accountId" name="accountId" value="<?php echo $accountId; ?>">
                <input type="submit" value="Apply filters">
            </form>
        </div>
    </div>
</body>
</html>