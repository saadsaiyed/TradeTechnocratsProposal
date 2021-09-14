<?php
    include "DBConnection.php";

    $Barcode_ID = $_GET['barcode'];
    if($Barcode_ID){
        $query = "SELECT * FROM Barcode WHERE Barcode_ID='$Barcode_ID'";
        $row = mysqli_fetch_assoc(runQuery($query));
        $Product_ID = $row['Product_ID'];
        $Count = $Garbage = $Processed = $Weight = 0;
        
        $result = runQuery("SELECT * FROM B_Warehouse_Product WHERE Product_ID='$Product_ID'");
        while ($row = mysqli_fetch_assoc($result)) {
            $Count += $row['Count'];
            $Garbage += $row['Garbage'];
            $Processed += $row['Processed'];
            $Weight += $row['Weight'];
        }

        $Data = array(
            "Count" => $Count,
            "Garbage" => $Garbage,
            "Processed" => $Processed,
            "Weight" => $Weight
        );
        echo json_encode($Data);
    }
?>