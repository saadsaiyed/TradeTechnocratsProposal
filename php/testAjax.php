<?php

    include "DBConnection.php";
    
    $result = runQuery("SELECT * FROM Location");
    $data = array();
    while ($row = mysqli_fetch_assoc($result)){
        array_push($data, $row["Location_ID"]);
    }

    echo json_encode($data);
?>