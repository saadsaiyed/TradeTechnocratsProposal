<?php
    include "DBConnection.php";
    
    $Invoice_Num = $_POST["invoice_num"];
    $Tracking_Company = $_POST["tracking_company"];
    //$Pickup = $_POST[""];
    $info = '';
    if($Invoice_Num == "" || $Invoice_Num == " "){
        $info = "Please enter Invoice Number, No empty invoices are allowed";
    }
    else{
        $Boxes = $_POST["Boxes"];

        for ($i=1; $i <= $Boxes;  $i++) { 
            $Tracking_Num = $_POST["tracking_num_".$i];
            if ($Tracking_Company == "Canada_Post") {
                $Tracking_Num = substr($Tracking_Num, 7, -5);
            }
            // if (strpos($Invoice_Num),'/') {
            //     while ($pos = strpos($Invoice_Num)) {
            //         substr($Invoice_Num, $pos, );
            //     }
            // }
            $query = "SELECT * FROM Tracking WHERE Tracking_ID='$Tracking_Num'";
            $result = runQuery($query);
            if ($result->num_rows == 0) {
                $query="SELECT * FROM Invoice WHERE Invoice_Num='$Invoice_Num'";
                $result= runQuery($query);
                if($result->num_rows > 0){
                    $Invoice_ID = mysqli_fetch_assoc($result)["Invoice_ID"];
                    $query = "INSERT INTO Tracking (Tracking_ID, Invoice_ID, Courier, Status) VALUES ('$Tracking_Num', '$Invoice_ID', '$Tracking_Company', '0')";
                    $result = runQuery($query);
                    
                    $info .= "Tracking Item Saved Succesfully with Tracking_Num = " . $Tracking_Num . "\n";
                }
                else {
                    $info .= "Invoice Nunmber Not Found. You Typed - $Invoice_Num";
                }
            }
        }
    }
    header("Location: ../tracking.php?info=$info");