<?php
include_once("../config/database.php");
include_once("../config/config.php");
include_once("../classes/FunctionLibrary.php");
include_once("../classes/DataInsertLibrary.php");
if (mkdir('../tmp/phplock.lock', 0700)) {
    //let's get the files
    $files = glob("$uploadLocation/*.csv");
    //let's check if there is a table to upload to and if not create one
    $functionLib = new FunctionLibrary();
    $dataLib = new DataInsertLibrary();
    $tableCheck = $dataLib->checkEventsTable($mysqli);
    if ($tableCheck) {
        foreach ($files as $file) {
            if (($handle = fopen($file, "r")) !== FALSE) {
                syslog(6, "Filename: " . basename($file) . " is in progress");
                $header = fgetcsv($handle);
                while (($data = fgetcsv($handle, 4096, ",")) !== FALSE) {
                    $allRows = [];
                    if (sizeof($header) == sizeof($data) && sizeof($header)>1 && sizeof($data)>1) {
                        //The following three rows allow us to insert the header and the data in the right order
                        $commaSeparatedHeaders = $functionLib->renderHeaderString($header);
                        $commaSeparatedValues = $functionLib->renderDataString($data);
                        $dataLib->insertEvent($commaSeparatedHeaders, $commaSeparatedValues, $mysqli);
                    }
                    else{
                        syslog(6, "Data in: " . basename($file) . " is invalid due to empty file or lack of proper formatting");
                    }
                }
                fclose($handle);
            } else {
                syslog(3, "Could not open file: " . $file);
            }
            syslog(6, "Filename: " . basename($file) . " has finished transferring its data");
        }
    }
    rmdir('../tmp/phplock.lock');

} else {
    syslog(3, "Another instance of this php script is running ");

}