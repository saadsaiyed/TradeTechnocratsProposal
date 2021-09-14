<?php
    include "DBConnection.php";
    
    $Barcode_ID = $_GET["BarcodeID"];
    
    $Production_ID = array();
    $Total_Quantity = array();
    $Date = array();
    $Type = array();

    $i = 0;
    // Production_Machine - START
        $query = "SELECT * FROM Production_Machine WHERE Barcode_ID='$Barcode_ID' ORDER BY Create_Time DESC LIMIT 5";
        $result = runQuery($query);

        if($result->num_rows > 0){
            while($row = mysqli_fetch_array($result)){
                $Production_ID[$i] = $row["Production_ID"];
                $Total_Quantity[$i] = ((int)$row["Bags"] * (int)$row["Boxes"]) + (int)$row["Toute"];
                $tempTime = $row["Create_Time"];
                $Date[$i] = date('jS\, F Y \| h:i A \(l\)', (strtotime($tempTime)+10800));
                $Type[$i] = "Machine";
                $i++;
            }
        }
    // Production_Machine - END

    // Prduction_Direct - START
        $query = "SELECT * FROM Production_Direct WHERE Barcode_ID='$Barcode_ID' ORDER BY Create_Time DESC LIMIT 5";
        $result = runQuery($query);

        if($result->num_rows > 0){
            while($row = mysqli_fetch_array($result)){
                $Production_ID[$i] = $row["Production_ID"];
                $Total_Quantity[$i] = (int)$row["Bags"];
                $tempTime = $row["Create_Time"];
                $Date[$i] = date('jS\, F Y \| h:i A \(l\)', (strtotime($tempTime)+10800));
                $Type[$i] = "Direct";
                $i++;
            }
        }
    // Prduction_Direct - END

    // Prduction_1lb - START
        // $query = "SELECT * FROM Production_114g WHERE Barcode_ID='$Barcode_ID' ORDER BY Create_Time DESC LIMIT 5";
        // $result = runQuery($query);

        // if($result->num_rows > 0){
        //     $row = mysqli_fetch_array($result);
        //     $Production_ID[$i] = $row["Production_ID"];
        //     $Total_Quantity[$i] = (int)$row["Bags_Made"];
        //     $Date[$i] = $row["Create_Time"];
        //     $Type[$i++] = "1lb -> 114g";
        // }
    // Prduction_1lb - END

    $DATA = array();
    array_push($DATA, $Production_ID);          //Production_ID - 0
    array_push($DATA, $Total_Quantity);         //Total_Quantity - 1
    array_push($DATA, $Date);                   //Date - 2
    array_push($DATA, $Type);                   //Type - 3

    echo json_encode($DATA);