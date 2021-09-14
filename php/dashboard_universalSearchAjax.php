<?php
    include "DBConnection.php";

    $search = $_GET["search"];

    $json_object = "";
    if($search || $search != ""){

        $DATA = array();

      //Product - START
        $result = runQuery("SELECT * FROM Product WHERE Name LIKE '$search%' LIMIT 3");
        if($result->num_rows > 0){
            $Product_ID = array();
            $Product_Name = array();
            $Item_Code = array();
            $Stock = array();
            $Zone = array();
            $Location = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $Temp_Product_ID = $row["Product_ID"];
                // Barcode - START
                $tempLocation = "";
                $result_i = runQuery("SELECT * FROM Barcode WHERE Product_ID = '$Temp_Product_ID' ORDER BY `Type` ASC");
                $tempStock = "";
                while ($row_i = mysqli_fetch_assoc($result_i)) {
                    $temp_Barcode_ID = $row_i["Barcode_ID"];
                  // Location - START
                    $resultLocation = runQuery("SELECT * FROM B_Location_Barcode WHERE Barcode_ID='$temp_Barcode_ID' ORDER BY Create_Time DESC");
                    while ($rowLocation = mysqli_fetch_array($resultLocation)) {
                        $tempLocation .= $rowLocation["Location_ID"] . ", ";
                    }
                    mysqli_free_result($resultLocation);
                    $tempLocation = substr($tempLocation, 0, -2) . " | ";
                  // Location - END
                    $tempSold = (int)$row_i["Total_Sold"];
                    $tempProduction = (int)$row_i["Total_Production"];
                    $tempAdjustment = (float)$row_i["Adjustment"];
                    
                    $tempStock .= ($tempProduction - $tempSold + $tempAdjustment) . " | ";
                }
                array_push($Location, substr($tempLocation, 0, -3));
                array_push($Stock, substr($tempStock, 0, -3));    
              // Barcode - END

                array_push($Product_ID, $row["Product_ID"]);
                array_push($Product_Name, $row["Name"]);
                array_push($Item_Code, $row["Item_Code"]);
                array_push($Zone, $row["Zone"]);
            }
            $DATA["P_Location"] = $Location;
            $DATA["P_Stock"] = $Stock;

            $DATA["P_Product_ID"] = $Product_ID;
            $DATA["P_Product_Name"] = $Product_Name;
            $DATA["P_Item_Code"] = $Item_Code;
            $DATA["P_Zone"] = $Zone;
        }
        mysqli_free_result($result);
      //Product - END
        
       //Barcode - START
        // $result = runQuery("SELECT * FROM Barcode WHERE Barcode_ID LIKE '%$search%' LIMIT 3");
        // if($result->num_rows > 0){
        //     $Barocde_ID = array();
        //     $Location = array();
            
        //     $Product_ID = array();
        //     $Product_Name = array();
        //     $Item_Code = array();
        //     $Stock = array();
        //     $Zone = array();
            
        //     while ($row = mysqli_fetch_assoc($result)) {
        //         // Stock - START
        //         $tempSold = (int)$row["Total_Sold"];
        //         $tempProduction = (int)$row["Total_Production"];
        //         $tempAdjustment = (int)$row["Adjustment"];
        //         array_push($Stock, ($tempProduction + $tempSold - $tempAdjustment));
        //         // Stock - END

        //         // Location - START
        //         $tempLocation = '';
        //         $result_ii = runQuery("SELECT * FROM B_Location_Barcode WHERE Barcode_ID = '$row["Barcode_ID"]' ORDER BY Create_Time DESC");
        //         while ($row_ii = mysqli_fetch_assoc($result_ii)) {
        //             $tempLocation .= $row_ii . ", ";
        //         }
        //         array_push($Location, substr($tempLocation, 0, -2));
        //         // Location - END
                
        //         // Product - START
        //         $result_i = runQuery("SELECT * FROM Product WHERE Product_ID = '$search%' LIMIT 3");
        //         $row_i = mysqli_fetch_assoc($result_i)
        //         $tempName = $row_i["Name"];
        //         $row["Type"] == 'B' ? $tempName . " - 114g";

        //         array_push($Product_Name, $tempName);
        //         array_push($Product_ID, $row_i["Product_ID"]);
        //         array_push($Item_Code, $row_i["Item_Code"]);
        //         array_push($Zone, $row_i["Zone"]);
        //         // Product - END
                
        //         array_push($Barcode_ID, $row["Barcode_ID"]);
        //     }
        //     $DATA["B_Barcode_ID"] = $Barcode_ID;
        //     $DATA["B_Location"] = $Location;
        //     $DATA["B_Stock"] = $Stock;

        //     $DATA["B_Product_ID"] = $Product_ID;
        //     $DATA["B_Product_Name"] = $Product_Name;
        //     $DATA["B_Item_Code"] = $Item_Code;
        //     $DATA["B_Zone"] = $Zone;
        // }
        // mysqli_free_result($result);
       //Barcode - END
        
      //Invoice - START
        $Invoice_Num = array();
        $Customer_Name = array();
        $Total_Count = array();

        $query = "SELECT * FROM Invoice WHERE Invoice_Num LIKE '$search%' LIMIT 3";
        $result = runQuery($query);
        if($result->num_rows > 0){
            $Customer_Info = array();
            $Invoice_ID = array();
            $Invoice_Num = array();
            $Total_Count = array();
            $Create_Time = array();

            $i = 0;
            while ($row = mysqli_fetch_assoc($result)){
                $Invoice_ID[$i] = $row["Invoice_ID"];
                $Invoice_Num[$i] = $row["Invoice_Num"];
                $Total_Count[$i] = $row["Total_Count"];
                $Create_Time[$i] = time2str(strtotime($row["Create_Time"]));
                
                //Invoice-Customer - Start
                    $Customer_ID = $row["Customer_ID"];
                    $tempRow = mysqli_fetch_assoc(runQuery("SELECT * FROM Customer WHERE Customer_ID='$Customer_ID'"));

                    $tempName = $tempRow["Name"];
                    $tempCompany = $tempRow["Company_Name"];
                    if($tempName != "" && $tempCompany != "")
                        $Customer_Info[$i] = $tempName . " - " . $tempCompany;
                    elseif($tempName == "" && $tempCompany != "")
                        $Customer_Info[$i] = $tempCompany;
                    elseif($tempName != "" && $tempCompany == "")
                        $Customer_Info[$i] = $tempName;
                    else 
                        $Customer_Info[$i] = "Null";
                //Invoice-Customer - End

                $i++;
            }
            $DATA["Invoice_ID"] = $Invoice_ID;
            $DATA["Invoice_Num"] = $Invoice_Num;
            $DATA["Customer_Info"] = $Customer_Info;
            $DATA["Total_Count"] = $Total_Count;
            $DATA["Create_Time"] = $Create_Time;
        }
        mysqli_free_result($result);
      //Invoice - END

      //Customer - START
        $result = runQuery("SELECT * FROM Customer WHERE Name LIKE '$search%' OR Company_Name LIKE '$search%' LIMIT 3");
        if($result->num_rows > 0){
            $Customer_ID = array();
            $Customer_Info = array();
            $i=0;
            while ($row = mysqli_fetch_assoc($result)) {
                $Customer_ID[$i] = $row["Customer_ID"];
                $tempName = $row["Name"];
                $tempCompany = $row["Company_Name"];
                
                if($tempName != "" && $tempCompany != "")
                    $Customer_Info[$i] = $tempName . " - " . $tempCompany;
                elseif($tempName == "" && $tempCompany != "")
                    $Customer_Info[$i] = $tempCompany;
                elseif($tempName != "" && $tempCompany == "")
                    $Customer_Info[$i] = $tempName;
                else 
                    $Customer_Info[$i] = "Null";
                
                $i++;
            }
            $DATA["Customer_ID"] = $Customer_ID;
            $DATA["Customer_Info"] = $Customer_Info;
        }
      //Customer - END

      //Tracking - START
        $result = runQuery("SELECT T.*, I.Invoice_Num 
        FROM Tracking AS T 
        INNER JOIN Invoice AS I ON I.Invoice_ID = T.Invoice_ID
        WHERE T.Tracking_ID LIKE '$search%'
        ORDER BY Create_Time LIMIT 3");
        if($result->num_rows > 0){
            $Tracking_ID = array();
            $T_Invoice_Num = array();
            $Status = array();
            $Courier = array();
            
            $i=0;
            while ($row = mysqli_fetch_assoc($result)) {
                $Tracking_ID[$i] = $row["Tracking_ID"];
                $T_Invoice_Num[$i] = $row["Invoice_Num"];
                $Status[$i] = $row["Status"];
                $Courier[$i] = $row["Courier"];

                $i++;
            }
            $DATA["Tracking_ID"] = $Tracking_ID;
            $DATA["T_Invoice_Num"] = $T_Invoice_Num;
            $DATA["Status"] = $Status;
            $DATA["Courier"] = $Courier;
        }
      //Tracking - END

      //Vendor - START
        $result = runQuery("SELECT * FROM Vendor WHERE Name LIKE '$search%' ORDER BY Name LIMIT 3");
        if($result->num_rows > 0){
            $Vendor_ID = array();
            $Name = array();

            $i = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $Vendor_ID[$i] = $row["Vendor_ID"];
                $Name[$i] = $row["Name"];
                $i++;
            }
            $DATA["Vendor_ID"] = $Vendor_ID;
            $DATA["Name"] = $Name;
        }
      //Vendor - END

        $json_object = json_encode($DATA);
    }        
    echo $json_object;
    //echo $DATA;
?>