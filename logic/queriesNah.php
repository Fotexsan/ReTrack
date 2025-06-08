<?php
function getQueries(){
    $queries = array (
        array("SELECT SUM(ms_played), %s FROM songdata WHERE accountId = %u GROUP BY %s ORDER BY `SUM(ms_played)` DESC","Top Songs"),
        array("SELECT * FROM songdata WHERE accountId = %u ORDER BY 'ts' ASC","All songs")   
        );
    return $queries;
}
?>