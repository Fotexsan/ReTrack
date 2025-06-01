<?php
function saveSongData($filename, $conn, $accountId){
    // Datei einlesen
    $json = file_get_contents($filename);
    $data = json_decode($json, true);

    //alle Einträge der JSON durchgehen
    foreach ($data as $entry) {

        //Podcasts und Songs ohne Namen werden gefiltert
        if (!empty($entry['master_metadata_track_name']) && $entry["ms_played"] != 0){

            //timestamp in richtiges Format bringen und offline Timestamp bevorzugen
            if (!empty($entry["offline_timestamp"])) {

                //spotify hat das Timestamp format zwischendrin geändert... (Wenn 13 Stellen in ms sonst in s)
                if (strlen((string)$entry["offline_timestamp"]) >= 13)
                    $oTs = $entry["offline_timestamp"]/1000;
                else{
                    $oTs = $entry["offline_timestamp"];
                }

                $ts = date('Y-m-d H:i:s', $oTs);
            } 
            else {
                $timestamp = new DateTime($entry["ts"]);
                $ts = $timestamp->format('Y-m-d H:i:s');
            }


            //restliche Daten werden aus der JSON extrahiert
            $platform = $conn->real_escape_string($entry["platform"]);
            $ms = $entry["ms_played"];
            $country = $entry["conn_country"];
            $song = $conn->real_escape_string($entry["master_metadata_track_name"]);
            $artist = $conn->real_escape_string($entry["master_metadata_album_artist_name"]);
            $album = $conn->real_escape_string($entry["master_metadata_album_album_name"]);
            $uri = $entry["spotify_track_uri"];
            $start = $entry["reason_start"];
            $end = $entry["reason_end"];
            $shuffle = (int)$entry["shuffle"];
            $skipped = (int)$entry["skipped"];
            $offline = (int)$entry["offline"];
            $incognito = (int)$entry["incognito_mode"];
                
            //Daten in Datenbank eintragen
            $sql = "INSERT IGNORE INTO songData (
            accountId, ts, platform, ms_played, conn_country, master_metadata_track_name, master_metadata_album_artist_name,
            master_metadata_album_album_name, spotify_track_uri, reason_start, reason_end, shuffle, skipped, offline, incognito_mode) 
            VALUES ('$accountId', '$ts', '$platform', $ms, '$country', '$song', '$artist', '$album', '$uri', '$start', '$end', $shuffle,  $skipped, $offline, $incognito)";

            $conn->query($sql);
            
            if ($conn->affected_rows > 0) {
                $GLOBALS["songcounter"]++;
            }
        }
    }
}
?>