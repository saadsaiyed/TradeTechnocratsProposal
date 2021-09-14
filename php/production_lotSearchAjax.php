<?php 
    include "DBConnection.php";
    
    $search = $_GET["search"];
    $result = runQuery("SELECT Lot.Lot_Number 
        FROM Lot_Number AS Lot
        INNER JOIN B_Lot_Product AS BLP
        ON BLP.Lot_ID = Lot.Lot_ID
        INNER JOIN Product AS P
        ON P.Product_ID = BLP.Product_ID
        INNER JOIN Barcode AS B
        ON B.Product_ID = P.Product_ID
        WHERE B.Barcode_ID = '$search'
        ORDER BY B.Product_ID");
    
    $LotNumbers = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $LotNumbers = $row["Lot_Number"];
    }

    echo json_encode($LotNumbers);
?>