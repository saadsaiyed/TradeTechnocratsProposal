<?php
    include "php/DBConnection.php";
    checkIfLoggedIn(2, 3, 4, null);

    $DATA = array();

    $Item_Code = array();
    $Product_Name = array();
    $Barcode1 = array();
    $Barcode2 = array();
    $Zone = array();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zaid Edits</title>
    <script src="js/index.js"></script>
    <style>
        table, td {
            border: 1px solid black;
        }
        tr:nth-child(even){background-color: #B8B8B8;}
    </style>
</head>
<body>
   <form action="post">
    <table>
    <tr>
        <td><label for="location">Location: </label></td>
        <td><input type="text" name="location" id="location"></td>
        <td><input type="submit" value="Submit"></td>
    </tr>
    </table>
   </form> 

   <table>

   </table>
   <div id="Extraction_Table">
        <h1>The result have this many data</h1>
        <table>

        <?php
    include "DBConnection.php";
    
    $query = "SELECT * FROM Product ORDER BY FIELD(Zone,'G','R','Y'), Name ASC";
    $result = runQuery($query);

    $Product_ID = array();
    $Product_Name = array();
    $Stock = array();
    $Warehouse_Stock = array();
    $Total_Stock = array();
    $Zone = array();
    $Barcode_ID = array();
    while($row = mysqli_fetch_assoc($result)) {
        $P_ID = $row['Product_ID'];
        $result1 = runQuery("SELECT * FROM Barcode WHERE Product_ID='$P_ID'");
        
        if($result1->num_rows > 1) {
            $Stock_Temp = null;
            while($row1 = mysqli_fetch_assoc($result1)) {
                if($Stock_Temp == null) $Stock_Temp = 0;
                $Total_Production = (int)$row1['Total_Production'];
                $Total_Sold = (int)$row1['Total_Sold'];
                $Adjustment = (int)$row1['Adjustment'];
                $Barcode_ID_Temp = $row1['Barcode_ID'];
                $S_Temp = $Total_Production - $Total_Sold + $Adjustment;
                if ($row1["Type"] == 'B')
                    $S_Temp = $S_Temp / 4;
                $Stock_Temp += $S_Temp;
            }
            $result2 = runQuery("SELECT * FROM B_Warehouse_Product WHERE Product_ID='$P_ID'");
            $Warehouse_Temp = '-';
            if($result2->num_rows > 0){
                $Warehouse_Temp = 0;
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    $Warehouse_Temp += (int)$row2["Weight"] - (int)$row2["Processed"] + (int)$row2["Garbadge"];
                }
            }
            $Total_Stock_Temp = $Stock_Temp;
            if(is_int($Warehouse_Temp)) $Total_Stock_Temp += $Warehouse_Temp;

            if($Stock_Temp <= 5){
                array_push($Product_ID, $P_ID);
                array_push($Product_Name, $row['Name']);
                array_push($Zone, $row['Zone']);
                array_push($Barcode_ID, $Barcode_ID_Temp);
                array_push($Warehouse_Stock, $Warehouse_Temp);
                array_push($Stock, $Stock_Temp);
                array_push($Total_Stock, $Total_Stock_Temp);
            }
        }
    }

    $Data = array(
        "Product_ID" => $Product_ID,
        "Product_Name" => $Product_Name,
        "Zone" => $Zone,
        "Barcode_ID" => $Barcode_ID,
        "Warehouse_Stock" => $Warehouse_Stock,
        "Stock" => $Stock,
        "Total_Stock" => $Total_Stock
    );

    echo json_encode($Data);
?>

        </table>
   </div>

<script>
    outOfStock();
</script>   
</body>
</html>