<?php
    include "DBConnection.php";
    
    $Vendor_Name = $_POST["Vendor_Name"];
    $Arrival_Date = $_POST["Arrival_Date"];
    $Invoice_Num = $_POST["invoice_num"];
    $Invoice_Link = $_POST["invoice_link"];

    if($Vendor_Name == "" || $Vendor_Name == " " || $_POST["product_id_1"] == null || $_POST["product_id_1"] == "" || $_POST["product_id_1"] == " ") {
        $info = "Please enter valid information.";
    }
    else{
        $Vendor_ID = mysqli_fetch_array(runQuery("SELECT Vendor_ID FROM Vendor WHERE Name='$Vendor_Name'"))["Vendor_ID"];

        $query = "INSERT INTO Warehouse (Vendor_ID, Arrival_Date, Invoice_Num, Invoice_Link) VALUES ('$Vendor_ID', '$Arrival_Date', '$Invoice_Num', '$Invoice_Link')";
        $Warehouse_Stock_ID = runQueryGiveId($query);

        $result = runQuery("SELECT Stock_ID FROM Warehouse WHERE Arrival_Date='$Arrival_Date' AND Vendor_ID='$Vendor_ID' ORDER BY Vendor_ID DESC LIMIT 1");
        $Stock_ID = mysqli_fetch_array($result)['Stock_ID'];

        for ($i = 1; $i <= ((count($_POST) - 4) / 4); $i++) { 
            $Product_ID = $_POST["product_id_".$i];
            $V_Item_Code = $_POST["item_code_".$i];
            $Weight = $_POST["weight_".$i];
            $Count = $_POST["count_".$i];
            $Country_Name = $_POST["country_name_".$i];
            $Lot_Number = $_POST["lot_num_".$i];
            

            if ($Product_ID == "" || $Product_ID == " ") {
                break;
            }
            
            //Vendor - START
                $result = runQuery("SELECT * FROM B_Vendor_Product WHERE Vendor_ID='$Vendor_ID' AND Product_ID='$Product_ID'");

                if($result->num_rows == 0)
                    runQuery("INSERT INTO `B_Vendor_Product` (`Vendor_ID`, `Product_ID`, `V_Item_Code`) VALUES ('$Vendor_ID', '$Product_ID', '$V_Item_Code')");
                else
                    runQuery("UPDATE `B_Vendor_Product` SET `V_Item_Code`='$V_Item_Code' WHERE `Vendor_ID`='$Vendor_ID' AND `Product_ID`='$Product_ID'");
            //Vendor - END    

            //Botanical Name - START
                $Bot_Name = $_POST["bot_name_".$i];
                if($Bot_Name != "" || $Bot_Name != " "){
    
                    $Bot_ID = mysqli_fetch_array(runQuery("SELECT Bot_ID FROM Botanical_Name WHERE Bot_Name='$Bot_Name'"))["Bot_ID"];
                    if (!$Bot_ID) {
                        runQuery("INSERT INTO `Botanical_Name` (`Bot_Name`) VALUES ('$Bot_Name')");
                        $Bot_ID = mysqli_fetch_array(runQuery("SELECT Bot_ID FROM Botanical_Name WHERE Bot_Name='$Bot_Name' ORDER BY Bot_ID DESC LIMIT 1"))["Bot_ID"];

                        runQuery("INSERT INTO `B_Botanical_Product` (`Bot_ID`, `Product_ID`) VALUES ('$Bot_ID', '$Product_ID')");
                    }
                    else{
                        $result = runQuery("SELECT * FROM B_Botanical_Product WHERE Bot_ID='$Bot_ID' AND Product_ID='$Product_ID'");
                        if($result->num_rows == 0)
                            runQuery("INSERT INTO `B_Botanical_Product` (`Bot_ID`, `Product_ID`) VALUES ('$Bot_ID', '$Product_ID')");
                    }
                }
            //Botanical Name - END

            //Country - START
                $result = runQuery("SELECT Country_ID FROM Country WHERE Country_Name='$Country_Name'");

                if($result->num_rows > 0){
                    $Country_ID = mysqli_fetch_array($result)["Country_ID"];
                    $result = runQuery("SELECT * FROM B_Country_Product WHERE Country_ID='$Country_ID' AND Product_ID='$Product_ID'");
                    if($result->num_rows == 0)
                        runQuery("INSERT INTO `B_Country_Product` (`Country_ID`, `Product_ID`) VALUES ('$Country_ID', '$Product_ID')");
                        
                }
            //Country - END

            //Lot Number - START
                $result = runQuery("SELECT Lot_ID FROM Lot_Number WHERE Lot_Number='$Lot_Number'");
                
                if($result->num_rows > 0){
                    $Lot_ID = mysqli_fetch_array($result)["Lot_ID"];
                }
                else{
                    $Lot_ID = runQueryGiveId("INSERT INTO Lot_Number (`Lot_Number`) VALUES ('$Lot_Number')");
                }

                $result = runQuery("SELECT * FROM B_Lot_Product WHERE Lot_ID='$Lot_ID' AND Product_ID='$Product_ID'");
                if($result->num_rows == 0)
                    runQuery("INSERT INTO `B_Lot_Product` (`Lot_ID`, `Product_ID`) VALUES ('$Lot_ID', '$Product_ID')");
            //Lot Number - END

            $query = "INSERT INTO `B_Warehouse_Product` (`Stock_ID`, `Product_ID`, `Lot_ID`, `Bot_ID`, `Country_ID`, `Weight`, `Count`) VALUES ('$Stock_ID', '$Product_ID', '$Lot_ID', '$Bot_ID', '$Country_ID', '$Weight', '$Count')";
            $result = runQuery($query);
        }

        $info = "Details saved successfuly under Warehouse_ID = $Stock_ID";
    }
    header("Location: ../warehouse.php?info=$info");