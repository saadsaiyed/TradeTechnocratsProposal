<?php
    include "DBConnection.php";
    
    $Barcode_ID = $_GET["BarcodeID"];
    
    $query = "SELECT * FROM Barcode WHERE Barcode_ID='$Barcode_ID'";
    $result = runQuery($query);

    if($result->num_rows > 0){
        $row = mysqli_fetch_array($result);
        $Product_ID = $row["Product_ID"];
        $Online_Price = $row["Online_Price"];
        $Bulk_Price = $row["Bulk_Price"];
        $Type = $row["Type"];
        $Total_Production = (float)$row["Total_Production"];
        $Total_Sold = (float)$row["Total_Sold"];
        $Adjustment = (float)$row["Adjustment"];
        $Stock = $Total_Production - $Total_Sold + $Adjustment;

        $query = "SELECT * FROM Product WHERE Product_ID='$Product_ID'";
        $result = runQuery($query);
        $row = mysqli_fetch_array($result);
        $Name = $row["Name"];
        $Item_Code = $row["Item_Code"];
        $Zone = $row["Zone"];
    }

    $DATA = array($Name, $Online_Price, $Zone, $Type, $Item_Code, $Total_Production, $Total_Sold, $Adjustment, $Stock);

    echo json_encode($DATA);