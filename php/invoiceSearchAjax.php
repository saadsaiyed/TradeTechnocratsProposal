<?php
    include "DBConnection.php";
    
    $search = $_GET["search"];
    $DATA = array();
    $Invoice_ID = array();
    $Invoice_Num = array();
    $Total_Packages = array();
    $Date = array();
    $T_Invoice_ID = array();
    $T_Invoice_Num = array();
    $T_Total_Packages = array();
    $Status_ID = array();
    $Status = array();

    if ($search == "") {
        $j = 0;
        $query = "SELECT * FROM Invoice_Status WHERE Status='0'";
        $result = runQuery($query);
        if($result->num_rows > 0){
            while ($row = $result->fetch_assoc()) {
                $Invoice_ID[$j] = $row["Invoice_ID"];
                $Status_ID[$j] = $row["Status_ID"];
                $Date[$j] = $row["Create_Time"];
                
                $rowi=mysqli_fetch_assoc(runQuery("SELECT * FROM Invoice WHERE Invoice_id='$Invoice_ID[$j]'"));
                $Invoice_Num[$j] = $rowi['Invoice_Num'];
                $Total_Packages[$j] = $rowi['Total_Count'];

                $j++;
            }
        }
        array_push($DATA, $Invoice_ID);             //Invoice_ID - 0
        array_push($DATA, $Invoice_Num);            //Invoice_Num - 1
        array_push($DATA, $Status_ID);              //Status_ID - 2
        array_push($DATA, $Total_Packages);         //Total_Packages - 3
        array_push($DATA, $Date);                   //Date - 4
    }
    else{
        $query = "SELECT * FROM Invoice WHERE Invoice_Num LIKE '$search%' ORDER BY Create_Time DESC LIMIT 10";
        $result = runQuery($query);
        $i=0;
        if($result->num_rows > 0){
            while ($row = $result->fetch_assoc()) {
                $T_Invoice_ID[$i] = $row["Invoice_ID"];
                $T_Invoice_Num[$i] = $row["Invoice_Num"];
                $T_Total_Packages[$i++] = $row["Total_Count"];
            }
        }
        
        $i=0;
        $j=0;
        foreach ($T_Invoice_ID as $T_Iid) {
            $query = "SELECT * FROM Invoice_Status WHERE Invoice_ID='$T_Iid' AND Status='0'";
            $result = runQuery($query);
            if($result->num_rows > 0){
                while ($row = $result->fetch_assoc()) {
                    $Invoice_ID[$j] = $row["Invoice_ID"];
                    $Invoice_Num[$j] = $T_Invoice_Num[$i];
                    $Status_ID[$j] = $row["Status_ID"];
                    $Total_Packages[$j] = $T_Total_Packages[$i];
                    $Date[$j++] = $row["Create_Time"];
                }
            }
            $i++;
        }
    
        array_push($DATA, $Invoice_ID);             //Invoice_ID - 0
        array_push($DATA, $Invoice_Num);            //Invoice_Num - 1
        array_push($DATA, $Status_ID);              //Status_ID - 2
        array_push($DATA, $Total_Packages);         //Total_Packages - 3
        array_push($DATA, $Date);                   //Date - 4
    }

    echo json_encode($DATA);
    $mysqli->close();
?>