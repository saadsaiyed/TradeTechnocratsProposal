<?php
    include "DBConnection.php";
    
    $Invoice_ID = $_GET["InvoiceID"];

    $Barcode_ID = array();
    $Count = array();
    $Create_Time = array();
    $Update_Time = array();
    $Product_ID = array();
    $Product_Name= array();

    $query = "SELECT * FROM B_Invoice_Barcode WHERE Invoice_ID='$Invoice_ID'";
    $result = runQuery($query);

    if($result->num_rows > 0){
        $i = 0;
        while($row = mysqli_fetch_array($result)){
            $Barcode_ID[$i] = $row["Barcode_ID"];
            $Count[$i] = $row["Count"];
            $Create_Time[$i] = date('jS\, F Y \| h:i A \(l\)', (strtotime($row["Create_Time"])+10800));
            $Update_Time[$i] = date('jS\, F Y \| h:i A \(l\)', (strtotime($row["Update_Time"])+10800));

            $barcodeResultArray = mysqli_fetch_assoc(runQuery("SELECT * FROM Barcode WHERE Barcode_ID='$Barcode_ID[$i]'"));
            $Product_ID[$i] = $barcodeResultArray['Product_ID'];
            $Type[$i] = $barcodeResultArray["Type"];
            
            $productResultArray = mysqli_fetch_assoc(runQuery("SELECT * FROM Product WHERE Product_ID='$Product_ID[$i]'"));
            $Product_Name[$i] = $productResultArray['Name'];
            if($Type[$i] == 'B')
                $Product_Name[$i] .= ' - 114g';

            $i++;
        }
    }

    $DATA = array($Barcode_ID, $Count, $Create_Time, $Update_Time, $Product_ID, $Product_Name);
    
    echo json_encode($DATA);