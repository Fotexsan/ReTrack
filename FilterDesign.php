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
    <title>ReTrack - Stats</title>
    <link rel="stylesheet" href="./css/reTrack.css">
    <style>
    /* Zusätzliche CSS-Regeln für die Stats-Seite */
  
    </style>
    <script src="./logic/filterDisplay.js" defer></script>
    <script src="./logic/searchSuggestions/autocomplete.js" defer></script>
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

    
    <div class="filter-container">
        <div id="filterForm">
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
                        <label for="artist">Artist</label>
                        <div class="search-container">
                            <input type="text" name="artist" id="artist" autocomplete="off" placeholder="Start typing..." list="artistSuggestions">
                            <datalist id="artistSuggestions"></datalist>
                        </div>
                    </div>
                    <div id="albumInputDiv">
                        <label for="album">Album</label>
                        <div class="search-container">
                            <input type="text" name="album" id="album" autocomplete="off" placeholder="Start typing..." list="albumSuggestions">
                            <datalist id="albumSuggestions"></datalist>
                        </div>
                    </div>
                </div>

                <div class="filter-group">
                    <h3>Time Filter Mode</h3>
                    <label for="simple">Simple</label>
                    <input type="radio" name="timeFilter" id="simple" value="simple" checked/>

                    <label for="advanced">Advanced</label>
                    <input type="radio" name="timeFilter" id="advanced" value="advanced"/>
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
                        <h3>Sort Order</h3>
                        <label for="desc">Descending</label>
                        <input type="radio" name="sortOrder" id="desc" value="desc" checked/>
                        <label for="asc">Ascending</label>
                        <input type="radio" name="sortOrder" id="asc" value="asc"/>
                    </div>
                    <div class="filter-group">
                        <h3>Sub-Metric</h3>
                        <label for="total">Total</label>
                        <input type="radio" name="subMetric" id="total" value="total" checked/>
                        <label for="percent">Percentage-Based</label>
                        <input type="radio" name="subMetric" id="percent" value="percent"/>
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
                        <label for="dateRange">Date range</label>
                        <input type="checkbox" name="dateRange" id="dateRange" value="dateRange"><br>

                        <label for="startDate">Start date</label>
                        <input type="date" name="startDate" id="startDate">
                        <label for="endDate">End date</label>
                        <input type="date" name="endDate" id="endDate">

                    </div>

                    <div class="filter-group">
                        <label for="season">Seasons</label>
                        <input type="checkbox" name="season" id="season" value="season"><br>
                        <select id="seasonSelect">
                            <option>Winter</option>
                        </select>

                        <label for="weekday">Weekdays</label>
                        <input type="checkbox" name="weekday" id="weekday">
                        <select id="weekdaySelect">
                            <option>Monday</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="time">Time frame</label>
                        <input type="checkbox" name="time" id="time"><br>

                        <label for="startTime">Start time</label>
                        <input type="time" id="startTime" name="startTime">
                        <label for="endTime">End time</label>
                        <input type="time" id="endTime" name="endTime">
                    </div>

                </div>
            </div>

            <input type="hidden" id="accountId" name="accountId" value="<?php echo $accountId; ?>">
            <button type="submit" class="apply-btn">Apply Filters</button>
        </div>
    </div>

                    
    
    <div class="results-container">
        <div class="results-header">
            <h2>Results</h2>
            <div>25 songs found</div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Artist</th>
                    <th>Album</th>
                    <th>Plays</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Blinding Lights</td>
                    <td>The Weeknd</td>
                    <td>After Hours</td>
                    <td>127</td>
                    <td>3:20</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Save Your Tears</td>
                    <td>The Weeknd</td>
                    <td>After Hours</td>
                    <td>98</td>
                    <td>3:35</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Starboy</td>
                    <td>The Weeknd, Daft Punk</td>
                    <td>Starboy</td>
                    <td>87</td>
                    <td>3:50</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Take My Breath</td>
                    <td>The Weeknd</td>
                    <td>Dawn FM</td>
                    <td>76</td>
                    <td>3:42</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Die For You</td>
                    <td>The Weeknd</td>
                    <td>Starboy</td>
                    <td>65</td>
                    <td>4:20</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>