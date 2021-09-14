<?php
    include "DBConnection.php";

    $Location_ID = $_GET["Location"];
    $Barcode_ID = $_GET["Barcode"];
    if(!$Barcode_ID){
        echo "ERROR : No Barcode Found!";
    }
    else{
        $result = runQuery("DELETE FROM B_Location_Barcode WHERE Barcode_ID = '$Barcode_ID' AND Location_ID = '$Location_ID'");
        if(!$result) 
            echo "Something Went Wrong!";
        else 
            echo "Succesful";
    }
?>