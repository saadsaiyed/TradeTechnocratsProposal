<?php
    include "DBConnection.php";
    
    $search = $_GET["search"];
    
    $query = "SELECT * FROM Location WHERE Location_ID LIKE '$search%' LIMIT 10";
    $result = runQuery($query);

    $Location = array();
    $Location_Barcode = array();
    if($result->num_rows > 0){
        $i = 0;
        while($row = mysqli_fetch_array($result)){
            $Barcode_ID[$i] = array();
            $Location[$i] = $row['Location_ID'];
            $result1 = runQuery("SELECT * FROM B_Location_Barcode WHERE Location_ID='$Location[$i]'");
            while($row1 = mysqli_fetch_array($result1)){
                array_push($Barcode_ID[$i], $row1['Barcode_ID']);
            }
            $i++;
        }
    }

    $DATA = array($Location, $Barcode_ID);
    echo json_encode($DATA);