<?php
//Funktion, die die eingestellten Filter zu einem sql query macht und einen array mit den gefilterten Daten zurückgibt
function query(){
    //where SQL array
    $where = [];

    $id = $_POST["accountId"];
    //accountId zu where array hinzufügen
    $where[] = "accountId = '$id'";

    $category = $_POST["category"];
    
    //Category Select
    switch ($category){
        //select, grouping und sekundäres Sortieren bestimmen
        case "song":
            //für simplen sql Befehl
            $selectString = "master_metadata_track_name, master_metadata_album_artist_name, master_metadata_album_album_name";
            //soll bei gleichem Sortierkriterium nach Songname sortiert werden
            $secondarySort = "master_metadata_track_name";

            //für komplexeren sql Befehl
            $metricSelectString = "s.master_metadata_track_name, s.master_metadata_album_artist_name, s.master_metadata_album_album_name";
            $metricSubSelectString =   "s.master_metadata_track_name = sub.master_metadata_track_name 
                                    AND s.master_metadata_album_artist_name = sub.master_metadata_album_artist_name 
                                    AND s.master_metadata_album_album_name = sub.master_metadata_album_album_name";
            break;
        case "album":
            //für simplen sql Befehl
            $selectString = "master_metadata_album_album_name, master_metadata_album_artist_name";#
            //soll bei gleichem Sortierkriterium nach Albumname sortiert werden
            $secondarySort = "master_metadata_album_album_name";

            //für komplexeren sql Befehl
            $metricSelectString = "s.master_metadata_album_album_name, s.master_metadata_album_artist_name";
            $metricSubSelectString =   "s.master_metadata_album_album_name = sub.master_metadata_album_album_name
                                    AND s.master_metadata_album_artist_name = sub.master_metadata_album_artist_name";
            break;
        case "artist":
            //für simplen sql Befehl
            $selectString = "master_metadata_album_artist_name";
            //soll bei gleichem Sortierkriterium nach Künstlername sortiert werden
            $secondarySort = "master_metadata_album_artist_name";

            //für komplexeren sql Befehl
            $metricSelectString = "s.master_metadata_album_artist_name";
            $metricSubSelectString = "s.master_metadata_album_artist_name = sub.master_metadata_album_artist_name";
            break;
    }

    //künstler/Album eingabe verarbeiten
    $whereAlbum = "";
    $whereMetricAlbum = "";
    $whereArtist = "";
    $whereMetricArtist = "";

    if (isset($_POST["album"]) && $_POST["album"] != ""){
        $album = $_POST["album"];

        //bekommt eigenen sql string, für unterscheidung zwischen simple und komplex
        $whereAlbum = "AND master_metadata_album_album_name = '$album'";
        $whereMetricAlbum = "AND s.master_metadata_album_album_name = '$album'";
    }
    if (isset($_POST["artist"]) && $_POST["artist"] != ""){
        $artist = $_POST["artist"];

        //bekommt eigenen sql string, für unterscheidung zwischen simple und komplex
        $whereArtist = "AND master_metadata_album_artist_name = '$artist'";
        $whereMetricArtist = "AND s.master_metadata_album_artist_name = '$artist'";
    }

    $sortOrder = "DESC";
    //Sort Settings
    if ($_POST["metric"] != "mPlayed" && $_POST["metric"] != "mTime"){
        //nur relevant bei komplexer abfrage
        $minimumPlaysFlag = false;
        $minimumPlaysSql = "";
        $metricSort = "metricCount";

        //überprüfen, ob minimumPlays gebraucht wird
        if ($_POST["sortOrder"] == "asc"){
            $minimumPlaysFlag = true;
            $sortOrder = "ASC";
        }

        if ($_POST["subMetric"] == "percent"){
            $minimumPlaysFlag = true;
            $metricSort = "percent";
        }

        if($minimumPlaysFlag){
            $minimumPlays = $_POST["minPlays"];
            $minimumPlaysSql = "HAVING playCount >= $minimumPlays";
        }
    }

    //standard festlegen
    $order = "COUNT(*) as playCount";
    $sortBy = "playCount";

    //Filter spezifische wheres zu where array hinzufügen
    switch($_POST["metric"]){
        case "mPlayed":
            //nur Songs zählen die mind 30s gelaufen sind
            $where[] = "ms_played >= 30000";
            break;
        case "mTime":
            //standard überschreiben
            $order = "SUM(ms_played) as totalTime";
            $sortBy = "totalTime";
            break;  
        case "mfPlayed":
            $metricWhere = "reason_end = 'trackdone'";
            break;
        case "mSkipped":
            //alles was skipped, fwdbtn oder backbtn hat
            $metricWhere = "(skipped = 1 OR reason_end = 'fwdbtn' OR reason_end = 'backbtn')";
            break;
        case "mShuffle":
            //nur wenn shuffle aktiv und der Song nicht direkt angeklickt wurde
            $metricWhere = "shuffle = 1 AND reason_start != 'clickrow'";
            break;
        case "mDirect":
            $metricWhere = "reason_start = 'clickrow'";
    }

    //Zeit Filter
    if ($_POST["timeFilter"] == "simple" || !isset($_POST["dateRange"])){
        //simpler Zeitraum
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
            }
            //timestamp wird ins passende Format gebracht
            $date = date("Y-m-d H:i:s", $timestamp);
            //sql befehl wird zu where array hinzugefügt
            $where[] = "ts >='$date'";
        }
    }
    //Datums Spanne inputs
    elseif (isset($_POST["dateRange"])){
        $startDate = $_POST["startDate"];

        $endDate = $_POST["endDate"];
        $endDate .= " 23:59:59"; //damit letzter Tag inkludiert ist

        //zu where array hinzufügen
        $where[] = "ts BETWEEN '$startDate' AND '$endDate'";
    }

    //Monate input
    if ($_POST["timeFilter"] == "advanced"){

        if (isset($_POST["month"])){
            $months = $_POST["months"];

            //sql string bauen
            if (count($months) > 0){
                $monthsSql = "(";
                $monthCount = count($months) - 1;

                for($i = 0; $i < $monthCount; $i++){

                    $currentMonth = $months[$i];
                    $monthsSql .= "MONTH(ts) = $currentMonth OR ";
                }
                $lastMonth = $months[$monthCount];
                $monthsSql .= "MONTH(ts) = $lastMonth)";

                //fertiger sql string zum where array hinzufügen
                $where[] = $monthsSql;
            }
        }

        //Wochentag input
        if (isset($_POST["weekday"])){
            $weekdays = $_POST["weekdays"];

            //sql string bauen
            if (count($weekdays) > 0){
                $daysSql = "(";
                $dayCount = count($weekdays) - 1;

                for($i = 0; $i < $dayCount; $i++){

                    $currentDay = $weekdays[$i];
                    $daysSql .= "WEEKDAY(ts) = $currentDay OR ";
                }
                $lastDay = $weekdays[$dayCount];
                $daysSql .= "WEEKDAY(ts) = $lastDay)";

                //fertiger sql string zum where array hinzufügen
                $where[] = $daysSql;
            }
        }
        //Uhrzeit input
        if(isset($_POST["time"])){
            $startTime = $_POST["startTime"];
            $endTime = $_POST["endTime"];

            if ($startTime > $endTime){
                //wenn man nach Songs filtern möchte die über die Nacht gehört wurden
                $where[] = "(TIME(ts) BETWEEN '$startTime' AND '23:59:59' OR TIME(ts) BETWEEN '00:00:00' AND '$endTime')";
            }
            else{
                $where[] = "TIME(ts) BETWEEN '$startTime' AND '$endTime'";
            }
        }
    }

    //where array zu string konvertieren
    $whereString = implode(" AND ", $where);

    //Datenbank verbindung aufbauen
    $conn = connect();

    if ($_POST["metric"] == "mPlayed" || $_POST["metric"]== "mTime")
    //der zusammengesetze sql Befehl hat noch Spaß gemacht
        $sql = "SELECT $selectString, $order
                FROM songData
                WHERE $whereString $whereArtist $whereAlbum
                GROUP BY $selectString
                ORDER BY $sortBy DESC, $secondarySort ASC;";
    else{
    //der hier nicht mehr

        //alte temporäre Tabelle löschen
        $conn->query("DROP TEMPORARY TABLE IF EXISTS sub");

        //neue Temporäre Tabelle anlegen, die gesamt Anzahl an Einträgen für die Filter (ohne metric) zählt
        //macht minimumPlays und percentage Based sortieren möglich
        $subSql = "CREATE TEMPORARY TABLE sub AS
                   SELECT $selectString, COUNT(*) AS playCount
                   FROM songData
                   WHERE $whereString $whereArtist $whereAlbum
                   GROUP BY $selectString 
                   $minimumPlaysSql";

        //tabelle erstellen
        if ($conn->query($subSql) === FALSE) {
            die("Fehler beim Erstellen der Tabelle: ".$conn->error);
        }

        //die eigentliche sql Abfrage
        $sql = "SELECT $metricSelectString, sub.playCount, COUNT(*) AS metricCount,
                ROUND((COUNT(*) / sub.playCount) * 100, 2) AS percent
                FROM songData AS s
                JOIN sub ON $metricSubSelectString
                WHERE $whereString AND $metricWhere $whereMetricArtist $whereMetricAlbum
                GROUP BY $metricSelectString, sub.playCount
                ORDER BY $metricSort $sortOrder, sub.playCount DESC;";
    }

    //Ergebnis holen
    $result = $conn->query($sql);
    $result = $result->fetch_all();
    
    //Verbindung schließen
    $conn->close();

    //array mit Ergebnis zurückgeben
    return $result;
}
?>