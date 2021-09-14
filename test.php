<?php
    include "php/DBConnection.php";

    // $query = "SELECT * FROM `Barcode` ORDER BY Product_ID ASC, Type ASC";
    // $resultBarcode = runQuery($query);

    // while ($rowBarcode = mysqli_fetch_array($resultBarcode)) {
    //     $Barcode_ID = $rowBarcode["Barcode_ID"];
    //     $Total_Production = $rowBarcode["Total_Production"];
    //     $Total_Sold = $rowBarcode["Total_Sold"];
    //     $Adjustment = $rowBarcode["Adjustment"];
        
    //     $New_Total_Sold = (int)mysqli_fetch_assoc(runQuery("SELECT sum(Count) AS 'Sum' FROM B_Invoice_Barcode WHERE `Barcode_ID`='$Barcode_ID'"))['Sum'];
        
    //     $row = mysqli_fetch_assoc(runQuery("SELECT sum(Bags) AS 'Total_Bags', sum(Boxes) AS 'Total_Boxes', sum(Toute) AS 'Total_Toute' FROM Production_Machine WHERE `Barcode_ID`='$Barcode_ID'"));
    //     $New_Total_Production_Machine = ((int)$row['Total_Bags'] * (int)$row['Total_Boxes']) + (int)$row['Total_Toute'];
        
    //     $New_Total_Production_Direct = (int)mysqli_fetch_assoc(runQuery("SELECT sum(Bags) AS 'Sum' FROM Production_Direct WHERE `Barcode_ID`='$Barcode_ID'"))['Sum'];

    //     $New_Total_Production = $New_Total_Production_Machine + $New_Total_Production_Direct;


    //     $Updated_Adjustment = ($Total_Production - $New_Total_Production) - ($Total_Sold - $New_Total_Sold) + $Adjustment;

    //     $Stock = $Total_Production - $Total_Sold + $Adjustment;
    //     $New_Stock = $New_Total_Production - $New_Total_Sold + $Updated_Adjustment;

    //     $query = "UPDATE Barcode SET Total_Production = '$New_Total_Production', Total_Sold = '$New_Total_Sold', Adjustment = '$Updated_Adjustment' WHERE Barcode_ID = '$Barcode_ID'";
    //     runQuery($query);
    // }
    
    $result = runQuery("SELECT
                            P.Product_ID,
                            B.Location_ID,
                            Barcode.Type
                        FROM
                            Barcode
                        INNER JOIN B_Location_Barcode AS B
                        ON
                            Barcode.Barcode_ID = B.Barcode_ID
                        INNER JOIN Product AS P
                        ON
                            Barcode.Product_ID = P.Product_ID
                        ORDER BY
                            P.Product_ID, Barcode.Type");
                        
    $Product = array();
    $Location = array();

    $Previous_ID = 0;
    $Previous_Type = 0;
    $tempLocation = "";
    while($row = mysqli_fetch_assoc($result)){
        // print_r($row); echo "<br/>";

        $Product_ID = $row["Product_ID"];
        $Location_ID = $row["Location_ID"];
        $Type = $row["Type"];
        if ($Previous_ID == $Product_ID) {
            if($Previous_Type == $Type)
                $tempLocation .= "$Location_ID, ";
            else
                $tempLocation = substr($tempLocation, 0, -2) . " || $Location_ID, " ;
        }
        else {
            array_push($Location, substr($tempLocation, 0, -2));
            $tempLocation = "$Location_ID, ";
            array_push($Product, $Product_ID);
        }

        $Previous_ID = $Product_ID;
        $Previous_Type = $Type;
    }

    foreach ($Location as $value) {
        echo "Location = $value <br/>" ;
    }


    $DATA = array(
        "Product_ID" => $Product,
        "Location_ID" => $Location
    );
    $json = json_encode($DATA);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Updating old total sold with new total sold</title>
</head>
<body>
    <a id="downloadAnchorElem"></a>
    <script>
        var result = '<?php echo $json ?>';
        var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(JSON.parse(result)));
        var dlAnchorElem = document.getElementById('downloadAnchorElem');
        dlAnchorElem.setAttribute("href", dataStr);
        dlAnchorElem.setAttribute("download", "location.json");
        dlAnchorElem.click();
    </script>
</body>
</html>