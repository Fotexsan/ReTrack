<?php
session_start();
include "dbConnection.php";

if (isset($_POST["accountId"])){

    $where = [];

    $id = $_POST["accountId"];
    $where[] = "accountId = '$id'";

    $category = $_POST["category"];
    
    switch ($category){
        case "song":
            $groupString = "master_metadata_track_name, master_metadata_album_artist_name";
            $selectString = "master_metadata_track_name, master_metadata_album_artist_name, master_metadata_album_album_name";
            break;
        case "album":
            $groupString = "master_metadata_album_album_name, master_metadata_album_artist_name";
            $selectString = $groupString;
            break;
        case "artist":
            $groupString = "master_metadata_album_artist_name";
            $selectString = $groupString;
            break;
    }

    if (isset($_POST["album"]) &&$_POST["album"] != ""){
        //geht erstmal nicht
        $album = $_POST["album"];
        //$where[] = $album;
    }
    if (isset($_POST["artist"]) && $_POST["artist"] != ""){
        $artist = $_POST["artist"];

        $where[] = "master_metadata_album_artist_name = '$artist'";
    }

    $sortOrder = "DESC";
    //Sort Settingszeug
    if ($_POST["metric"] != "mPlayed" && $_POST["metric"] != "mTime"){
        $minimumPlaysFlag = false;

        if ($_POST["sortOrder"] == "asc"){
            $minimumPlaysFlag = true;
            $sortOrder = "ASC";
        }

        if ($_POST["subMetric"] == "percent"){
            $minimumPlaysFlag = true;
        }
        else{

        }

        if($minimumPlaysFlag){
            $minimumPlays = $_POST["minPlays"];
        }
    }



    switch($_POST["metric"]){
        case "mPlayed":
            $where[] = "ms_played >= 30000";
            break;
        case "mTime":
            break;
        case "mfPlayed":
            $where[] = "reason_end = 'trackdone'";
            break;

        case "mSkipped":
            $where[] = "(skipped = 1 OR reason_end = 'fwdbtn')";
            break;

        case "mShuffle":
            $where[] = "shuffle = 1 AND reason_start != 'clickrow'";
            break;

        case "mDirect":
            $where[] = "reason_start = 'clickrow'";
            break;
    }

    //Zeit zeug
    if ($_POST["timeFilter"] == "simple" || !isset($_POST["dateRange"])){

        if ($_POST["simpleTimeSelect"] != "allTime"){
            switch($_POST["simpleTimeSelect"]){
                case "twoWeeks":
                    $timestamp = strtotime("-2 Weeks");
                    break;
                case "oneMonth":
                    $timestamp = strtotime("-1 Month");
                    break;
                case "threeMonth":
                    $timestamp = strtotime("-3 Month");
                    break;
                case "sixMonth":
                    $timestamp = strtotime("-6 Month");
                    break;
                case "year":
                    $timestamp = strtotime("-1 Year");
                    break;
            }
            $date = date("Y-m-d H:i:s", $timestamp);
            $where[] = "ts >='$date'";
        }
    }
    elseif (isset($_POST["startDate"]) && isset($_POST["endDate"])){
        $startDate = $_POST["startDate"];

        $endDate = $_POST["endDate"];
        $endDate .= " 23:59:59"; //damit endDate inkludiert ist

        $where[] = "ts BETWEEN '$startDate' AND '$endDate'";
    }


    if ($_POST["timeFilter"] == "advanced"){

        if (isset($_POST["month"])){
            $months = $_POST["months"];

            if (count($months) > 0){
                $monthsSql = "(";
                $monthCount = count($months) - 1;

                for($i = 0; $i < $monthCount; $i++){

                    $currentMonth = $months[$i];
                    $monthsSql .= "MONTH(ts) = $currentMonth OR ";
                }
                $lastMonth = $months[$monthCount];
                $monthsSql .= "MONTH(ts) = $lastMonth)";

                $where[] = $monthsSql;
            }
        }

        if (isset($_POST["weekday"])){
            $weekdays = $_POST["weekdays"];

            if (count($weekdays) > 0){
                $daysSql = "(";
                $dayCount = count($weekdays) - 1;

                for($i = 0; $i < $dayCount; $i++){

                    $currentDay = $weekdays[$i];
                    $daysSql .= "WEEKDAY(ts) = $currentDay OR ";
                }
                $lastDay = $weekdays[$dayCount];
                $daysSql .= "WEEKDAY(ts) = $lastDay)";

                $where[] = $daysSql;
            }
        }

        if(isset($_POST["time"])){
            $startTime = $_POST["startTime"];
            $endTime = $_POST["endTime"];

            if ($startTime > $endTime){
                $where[] = "(TIME(ts) BETWEEN '$startTime' AND '23:59:59' OR TIME(ts) BETWEEN '00:00:00' AND '$endTime')";
            }
            else{
                $where[] = "TIME(ts) BETWEEN '$startTime' AND '$endTime'";
            }
        }
    }


    $whereString = implode(" AND ", $where);

    $sql = "SELECT $selectString, COUNT(*) as playCount, SUM(ms_played) as totalTime
            FROM songData
            WHERE $whereString GROUP BY $groupString
            ORDER BY playCount $sortOrder;";

    echo "$sql<br><br>";
    $conn = connect();

    $result = $conn->query($sql);

    $result = $result->fetch_all();

    $conn->close();

    for ($i = 0; $i<count($result); $i++){
        echo "$i. ";
        for ($j = 0; $j < count($result[$i]); $j++){
            $entry = $result[$i][$j];
            echo " $entry; ";
        }
        echo "<br><br>";
    }
}
?>