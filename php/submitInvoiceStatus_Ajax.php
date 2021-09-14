<?php
    include "DBConnection.php";

    $Invoice_Num = $_GET["invoice-num"];
    if(!$Invoice_Num){
        echo "ERROR : No Invoice Number Entered!";
    }
    else{
        $result = runQuery("SELECT * FROM Invoice WHERE Invoice_Num = '$Invoice_Num'");
        if ($result->num_rows == 0) {
            echo "ERROR : Invoice #$Invoice_Num NOT FOUND!";
        }
        else{
            $Invoice_ID = mysqli_fetch_assoc($result)["Invoice_ID"];
    
            $Status_ID = mysqli_fetch_assoc(runQuery("SELECT * FROM Invoice_Status WHERE Invoice_ID = '$Invoice_ID'"))["Status_ID"];
            if(!$Status_ID)
            echo "ERROR : Invoice #$Invoice_Num was not set for PICKUP! <br/>";
            else
            $result = runQuery("UPDATE Invoice_Status SET `Status` = '1' WHERE `Status_ID` = '$Status_ID'");
            
            echo "Invoice Picked Up Successfully!";
        }
    }
?>