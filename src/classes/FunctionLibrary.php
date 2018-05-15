<?php

class FunctionLibrary
{
    /**
     * @param array $dataArray
     * @return bool|string
     */
    public function renderDataString(array $dataArray)
    {
        $dataString = "";
        if (!empty($dataArray)) {
            foreach ($dataArray as $key => $dataElement) {
                $dataString .= is_numeric($dataElement) ? $dataElement . "," : "'" . $dataElement . "',";
            }
            return substr($dataString, 0, -1);
        }
        return false;
    }

    /**
     * @param array $headerArray
     * @return bool|string
     */
    public function renderHeaderString(array $headerArray)
    {
        if (!empty($headerArray)) {
            $commaSeparatedHeaders = implode(",", $headerArray);
            return $commaSeparatedHeaders;
        }
        return false;
    }
}
