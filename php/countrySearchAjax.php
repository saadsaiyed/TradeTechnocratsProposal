<?php
    include "DBConnection.php";
    
    $search = $_GET["search"];
    $data = array();

    $query = "SELECT * FROM Country WHERE Country_Name LIKE '$search%' ORDER BY Country_Name LIMIT 5";
    $result = runQuery($query);
    $i=0;

    while ($result->num_rows > 0 && $row = $result->fetch_assoc()) {
        $data[$i++] = $row["Country_Name"];
    }

    $mysqli->close();
    echo json_encode($data);
?>