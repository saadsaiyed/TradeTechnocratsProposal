<?php
    include "DBConnection.php";
    
    $search = $_GET["search"];
    $data = array();
    $i=0;

    
    $query = "SELECT Name FROM Vendor WHERE Name LIKE '$search%' ORDER BY Name LIMIT 10";
    $result = runQuery($query);
    if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            $data[$i] = $row["Name"];
            $i++;
        }
    }

    $query = "SELECT Name FROM Product WHERE Name LIKE '$search%' ORDER BY Name LIMIT 10";
    $result = runQuery($query);
    if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            $data[$i] = $row["Name"];
            $i++;
        }
    }

    // $query = "SELECT Item_Code FROM Barcode WHERE Barcode_ID LIKE '$search%' LIMIT 10";
    // $result = runQuery($query);
    // if($result->num_rows > 0){
    //     while ($row = $result->fetch_assoc()) {
    //         $data[$i] = $row["Barcode_ID"];
    //         $i++;
    //     }
    // }
    echo json_encode($data);
    $mysqli->close();
?>