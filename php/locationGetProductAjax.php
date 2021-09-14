<?php
    include "DBConnection.php";
    
    $Barcode_ID = $_GET["BarcodeID"];
    $Location_ID = $_GET["Location"];
    
    $query = "SELECT * FROM Barcode WHERE Barcode_ID='$Barcode_ID'";
    $result = runQuery($query);

    if($result->num_rows > 0){
        $row = mysqli_fetch_array($result);
        $Product_ID = $row["Product_ID"];
        $Type = $row["Type"];
        $Total_Production = (float)$row["Total_Production"];
        $Total_Sold = (float)$row["Total_Sold"];
        $Adjustment = (float)$row["Adjustment"];
        $Stock = $Total_Production - $Total_Sold + $Adjustment;

        $result = runQuery("SELECT Location_ID FROM B_Location_Barcode WHERE Barcode_ID='$Barcode_ID'");
        $Location = '';
        while($row = mysqli_fetch_array($result)){
            $Location .= $row['Location_ID'] . ', ';
        }
        $Count = 'NULL';
        if($Location_ID != ""){
            $Count = mysqli_fetch_assoc(runQuery("SELECT Count FROM B_Location_Barcode WHERE Barcode_ID='$Barcode_ID' AND Location_ID = '$Location_ID'"))["Count"];
        }

        $query = "SELECT * FROM Product WHERE Product_ID='$Product_ID'";
        $result = runQuery($query);
        $row = mysqli_fetch_array($result);
        $Name = $row["Name"];
        $Zone = $row["Zone"];
    }

    $DATA = array($Name, $Location, $Zone, $Type, $Stock, $Count);

    echo json_encode($DATA);