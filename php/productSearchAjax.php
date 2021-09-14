<?php
    include "DBConnection.php";
    $Typee = "ASC";
    $search = $_GET["search"];
    if ($search[0] == '-' && $search[1] == '-') {
        $Typee = "DESC";
        $search = substr($search, 2, -1);
    }
    
    $DATA = array();

    $Product_Name = array();
    $Product_ID= array();
    $Barcode_ID = array();
    $Type = array();
    
    $query = "SELECT Name, Product_ID FROM Product WHERE Name LIKE '%$search%' ORDER BY Name LIMIT 10";
    $result = runQuery($query);
    $i=0;
    if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            $Product_ID[$i] = $row["Product_ID"];
            $Product_Name[$i++] = $row["Name"];
        }
    }

    for($i = 0; $i < count($Product_Name); $i++) {
        $query = "SELECT Barcode_ID, Type FROM Barcode WHERE Product_ID='$Product_ID[$i]' Order By Type $Typee";
        $result1 = runQuery($query);
        $row = $result1->fetch_assoc();
        $Barcode_ID[$i] = $row["Barcode_ID"];
        $Type[$i] = $row["Type"];
    }

    array_push($DATA, $Product_Name);                     //Product_Name - 0
    array_push($DATA, $Barcode_ID);                     //Barcode_ID - 1
    array_push($DATA, $Type);                     //Barcode_ID - 1

    echo json_encode($DATA);
    $mysqli->close();
?>