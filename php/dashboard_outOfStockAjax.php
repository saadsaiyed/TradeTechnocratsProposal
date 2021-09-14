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
    $Yearly_Avg = array();
    $Barcode_ID = array();

    while($row = mysqli_fetch_assoc($result)) {
        $P_ID = $row['Product_ID'];
        $result1 = runQuery("SELECT * FROM Barcode WHERE Product_ID='$P_ID'");
        
        if($result1->num_rows > 1) {
            $Stock_Temp = 0;
            $Avg_Yearly_Sell = 0;
            while($row1 = mysqli_fetch_assoc($result1)) {
                $Total_Production = (int)$row1['Total_Production'];
                $Total_Sold = (int)$row1['Total_Sold'];
                $Adjustment = (int)$row1['Adjustment'];
                $Barcode_ID_Temp = $row1['Barcode_ID'];

                //Calculating Total Sold - Start
                $S_Temp = $Total_Production - $Total_Sold + $Adjustment;
                if($row1["Type"] == 'B') $S_Temp = $S_Temp / 4;
                $Stock_Temp += $S_Temp;
                //Calculating Total Sold - END

                //Calculating Avg Yearly Sell - Start
                $datediff = time() - strtotime("2020-06-15");
                $Num_Days_Till_Date = round($datediff / (60 * 60 * 24));
                $temp_Avg = $Total_Sold / $Num_Days_Till_Date * 365;
                if($row1["Type"] == 'B') $temp_Avg /= 4;
                $Avg_Yearly_Sell += $temp_Avg;
                //Calculating Avg Yearly Sell - Start
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

            if($Stock_Temp < $Avg_Yearly_Sell){
                array_push($Product_ID, $P_ID);
                array_push($Product_Name, $row['Name']);
                array_push($Zone, $row['Zone']);
                array_push($Barcode_ID, $Barcode_ID_Temp);
                array_push($Warehouse_Stock, $Warehouse_Temp);
                array_push($Stock, $Stock_Temp);
                array_push($Total_Stock, $Total_Stock_Temp);
                array_push($Yearly_Avg, number_format((float)$Avg_Yearly_Sell, 0, '.', ''));
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
        "Total_Stock" => $Total_Stock,
        "Yearly_Avg" => $Yearly_Avg
    );

    echo json_encode($Data);
?>