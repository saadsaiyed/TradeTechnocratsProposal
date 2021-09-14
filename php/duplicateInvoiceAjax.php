<?php
    include "DBConnection.php";
    
    $search = $_GET["search"];
    $data = array();

    $query = "SELECT * FROM Invoice WHERE Invoice_Num='$search'";
    $result = runQuery($query);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $data[0] = $row["Invoice_Num"];
        $data[1] = $row["Total_Count"];
        $data[2] = $row["Create_Time"];
        $Customer_ID = $row["Customer_ID"];
        $tempRow = mysqli_fetch_assoc(runQuery("SELECT * FROM Customer WHERE Customer_ID='$Customer_ID'"));
        $data[3] = $tempRow["Name"] . " - " . $tempRow["Company_Name"];
    }

    $mysqli->close();
    echo json_encode($data);
?>