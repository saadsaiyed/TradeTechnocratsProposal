<?php
    include "DBConnection.php";
    
    $search = $_GET["search"];
    $DATA = array();

    $query = "SELECT * FROM Product WHERE Name LIKE '$search%' OR Item_Code LIKE '$search%' ORDER BY Name LIMIT 10";
    $result = runQuery($query);

    $Name = array();
    $Product_ID = array();
    $Item_Code = array();
    $Barcode_A = array();
    $Barcode_B = array();
    $Zone = array();
    $i=0;
    if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            $Name[$i] = $row["Name"];
            $Zone[$i] = $row["Zone"];
            $Item_Code[$i] = $row["Item_Code"];
            $Product_ID[$i++] = $row["Product_ID"];
        }
    }
    
    $i=0;
    foreach ($Product_ID as $Pid) {
        $query = "SELECT Barcode_ID FROM Barcode WHERE Product_ID='$Pid' ORDER BY Type Asc";
        $result = runQuery($query);
        if($result->num_rows > 0){
            $check = true;
            while ($row = $result->fetch_assoc()) {
                if ($check) {
                    $Barcode_A[$i] = $row['Barcode_ID'];
                    $check = false;
                }
                else {
                    $Barcode_B[$i] = $row['Barcode_ID'];
                }
            }
        }
        $i++;
    }

    array_push($DATA, $Name);               //Name - 0
    array_push($DATA, $Barcode_A);          //Barcode_A - 1
    array_push($DATA, $Barcode_B);          //Barcode_B - 2
    array_push($DATA, $Zone);               //Zone - 3
    array_push($DATA, $Item_Code);          //Item_Code - 4
    array_push($DATA, $Product_ID);         //Product_ID - 5

    echo json_encode($DATA);
    $mysqli->close();
?>