<?php
    include "DBConnection.php";
    
    $search = $_GET["search"];
    $data = array();

    $query = "SELECT * FROM Customer WHERE Name LIKE '$search%' ORDER BY Name LIMIT 10";
    $result = runQuery($query);
    $i=0;
    if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            $temp = $row["Name"] . " - " . $row["Company_Name"];
            $data[$i++] = $temp;
        }
    }
    else{
        $query = "SELECT * FROM Customer WHERE Company_Name LIKE '$search%' ORDER BY Name LIMIT 10";
        $result = runQuery($query);
        if($result->num_rows > 0){
            while ($row = $result->fetch_assoc()) {
                $temp = $row["Name"] . " - " . $row["Company_Name"];
                $data[$i++] = $temp;
            }
        }
    }

    $mysqli->close();
    echo json_encode($data);
?>