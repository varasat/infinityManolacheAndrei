<?php

class DataInsertLibrary
{
    private $table = "events";

    public function insertEvent($header, $data, $mysqli)
    {
        if (!empty($header) && !empty($data)) {
            $dataInsertSQL = "INSERT INTO $this->table ($header) VALUES ($data)";
            if ($mysqli->query($dataInsertSQL)) {
                return true;
            } else {
                syslog(6, "Data was not inserted succesfully:" . $mysqli->error);
                return false;
            }
        }

    }

    public function checkEventsTable($mysqli)
    {
        $tableName = $this->table;
        $result = $mysqli->query("SHOW TABLES LIKE '" . $tableName . "'");
        $tableSQL = "CREATE TABLE $tableName (
            `id` int(11) NOT NULL,
            `eventDatetime` datetime NOT NULL,
            `eventAction` varchar(20) NOT NULL,
            `callRef` int(11) NOT NULL,
            `eventValue` FLOAT(12,2),
            `eventCurrencyCode` varchar(3) 
            ) ;
            ALTER TABLE `$tableName`
            ADD PRIMARY KEY (`id`);
            ALTER TABLE `$tableName`
            MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
            ";
        if ($result->num_rows < 1) {
            if ($mysqli->multi_query($tableSQL)) {
                do {
                    /* store first result set */
                    if ($result = $mysqli->store_result()) {
                        $result->free();
                    }
                    /* print divider */
                    if ($mysqli->more_results()) {
                        syslog(6, "next result");
                    }
                } while ($mysqli->next_result());
                return true;
            } else {
                syslog(6, "Table was not created  succesfully:" . $mysqli->error);
                echo $mysqli->error;
                return false;
            }
        }
        return true;
    }
}
