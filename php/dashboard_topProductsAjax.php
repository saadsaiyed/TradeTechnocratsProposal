<?php
    include 'DBConnection.php';

    $modifiedDate = date('Y-m-d h:m:s', strtotime(date("Y-m-d h:m:s") . ' - 30 days'));
    if($_GET["modifiedDate"])
        $modifiedDate = date('Y-m-d h:m:s', strtotime(date("Y-m-d h:m:s") . $_GET["modifiedDate"]));

    $uniqueBarcodeCount = array();
    $Barcode_ID = array();
    $Count = array();
    $Product_ID = array();
    $Product_Name = array();
    $Stock = array();

    $query = "SELECT * FROM B_Invoice_Barcode WHERE Create_Time >= '$modifiedDate' ORDER BY Barcode_ID";
    $result = runQuery($query);
    $count = 0;
    while($row = mysqli_fetch_assoc($result)) {
        if(!$lastBarcode) $lastBarcode = $row["Barcode_ID"];
        if($row["Barcode_ID"] == $lastBarcode) {
            $count += (int)$row["Count"];
        }
        else{
            $row1 = mysqli_fetch_assoc(runQuery("SELECT * FROM Barcode WHERE Barcode_ID='$lastBarcode'"));
            if($row1["Type"] == 'B') 
                $count /= 4;
                
            $tempProduct_ID = $row1["Product_ID"];
            $product_name = mysqli_fetch_assoc(runQuery("SELECT Name FROM Product WHERE Product_ID='$tempProduct_ID'"))["Name"];
            array_push($Barcode_ID, $lastBarcode);
            array_push($Count, $count);
            array_push($Product_ID, $row1["Product_ID"]);
            array_push($Product_Name, $product_name);
            $temp_stock = (floatval($row1["Total_Production"]) - floatval($row1["Total_Sold"])) + floatval($row1["Adjustment"]);
            array_push($Stock, $temp_stock);

            $count = (int)$row["Count"];
        }
        $lastBarcode = $row["Barcode_ID"];
    }
    $uniqueBarcodeCount = array($Barcode_ID, $Count, $Product_ID, $Product_Name);
    
    array_multisort($Product_ID, SORT_ASC, SORT_NUMERIC,
                    $Count,
                    $Barcode_ID,
                    $Product_Name);

    $Final_Product_ID = array();
    $Final_Product_Name = array();
    $Final_Count = array();

    $lastProduct_ID = $Product_ID[0];
    $count = 0;
    for ($i=0; $i < count($Barcode_ID); $i++) {
        if($Product_ID[$i] == $lastProduct_ID) {
            $count += $Count[$i];
        }
        else{
            array_push($Final_Product_ID, $lastProduct_ID);
            array_push($Final_Product_Name, $lastProduct_Name);
            array_push($Final_Count, $count);
            $count = $Count[$i];
        }
        $lastProduct_ID = $Product_ID[$i];
        $lastProduct_Name = $Product_Name[$i];
    }
    array_multisort($Final_Count, SORT_DESC, SORT_NUMERIC,
                    $Final_Product_ID,
                    $Final_Product_Name);

    $productToCount = array(
        "Product_ID" => $Final_Product_ID,
        "Product_Name" => $Final_Product_Name,
        "Count" => $Final_Count,
        "Stock" => $Stock
    );

    echo json_encode($productToCount);
?>