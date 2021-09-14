<?php
    include "DBConnection.php";
    
    $search = $_POST["search"];

    $query = "SELECT * FROM Product WHERE Name='$search'";
    $result = runQuery($query);
    //print_r($result);
    // if($result->num_rows == 0){
    //     //$search = mysql_real_escape_string($search);
    //     //echo "Here |" . $search . "|<br/>";
    //     $query = "SELECT * FROM Product WHERE Item_Code='$search'";
    //     echo $query . "<br/>";
    //     $result = runQuery($query);
    // }
    if($result->num_rows == 0){
        //echo "Here 2";
        $query = "SELECT * FROM Product WHERE Product_ID LIKE'%$search%' LIMIT 1";
        $result = runQuery($query);    
    }
    if($result->num_rows == 0){
        //echo "Here 3";
        $query = "SELECT * FROM Barcode WHERE Barcode_ID LIKE '%$search%' LIMIT 1";
        $result = runQuery($query);
        if($result->num_rows != 0){
            //echo "Here 33";
            $row = mysqli_fetch_array($result);
            $Product_ID = $row['Product_ID'];
            $query = "SELECT * FROM Product WHERE Product_ID LIKE '%$Product_ID%' LIMIT 1";
            $result = runQuery($query);
        }
    }
    if($result->num_rows != 0){
        //echo "Here finally";

        $row = mysqli_fetch_array($result);
        $Product_ID = $row['Product_ID'];
        $Name[] = $row['Name'];
        $Item_Code[] = $row['Item_Code'];
        $Tax_Code[] = $row['Tax_Code'];
        $Zone[] = $row['Zone'];
        $DATA = array();

        //Barcode - START
            $Barcode_ID = array();
            $Online_Price = array();
            $Bulk_Price = array();
            $Total_Production = array();
            $Total_Sold = array();
            $Adjustment = array();
            $Mini_Reorder = array();
            $Type = array();

            $Location_ID = array();

            $query = "SELECT * FROM `Barcode` WHERE `Product_ID`='$Product_ID' ORDER BY Product_ID ASC, Type ASC";
            $resultBarcode = runQuery($query);
            $i = 0;

            while ($rowBarcode = mysqli_fetch_array($resultBarcode)) {
                $Barcode_ID[$i] = $rowBarcode["Barcode_ID"];
                $Online_Price[$i] = $rowBarcode["Online_Price"];
                $Bulk_Price[$i] = $rowBarcode["Bulk_Price"];
                $Total_Production[$i] = $rowBarcode["Total_Production"];
                $Total_Sold[$i] = $rowBarcode["Total_Sold"];
                $Adjustment[$i] = $rowBarcode["Adjustment"];
                $Mini_Reorder[$i] = $rowBarcode["Mini_Reorder"];
                $Type[$i] = $rowBarcode["Type"];

                //Location - START
                    $query = "SELECT * FROM B_Location_Barcode WHERE Barcode_ID='$Barcode_ID[$i]'";
                    $resultLocation = runQuery($query);
                    $tempLocation = "";
                    while ($rowLocation = mysqli_fetch_array($resultLocation)) {
                        if($i == 0)
                            $tempLocation .= $rowLocation["Location_ID"] . ", ";
                        else
                            $tempLocation .= ", " . $rowLocation["Location_ID"];
                            
                    }
                    mysqli_free_result($resultLocation);
                    $Location_ID[$i] = $tempLocation;
                //Location - END
                $i++;
            }
            mysqli_free_result($resultBarcode);

        //Barcode - END

        //Invoice - START
            $Invoice_ID = array();
            $Invoice_Num = array();
            $Total_Count = array();
            $Pickup = array();
            $Create_Time = array();

            $Customer_Name = array();
            $Company_Name = array();

            $Tracking_ID = array();
            $Courier = array();
                        
            // foreach ($Barcode_ID as $Barcode) {
            //     $query = "SELECT * FROM B_Invoice_Barcode WHERE Barcode_ID='$Barcode'";
            //     $resultInvoiceB = runQuery($query);
            //     $i = 0;
            //     while ($rowInvoiceB = mysqli_fetch_array($resultInvoiceB)) {
            //         $Invoice_ID[$i] = $rowInvoiceB["Invoice_ID"];
            //         $Invoice_Num[$i] = $rowInvoiceB["Invoice_Num"];
            //         $Count[$i] = $rowInvoiceB["Count"];
                    
            //         $tempInvoiceID = $Invoice_ID[$i];
            //         $query = "SELECT * FROM Invoice WHERE Invoice_ID='$tempInvoiceID'";
            //         $resultInvoiceB = runQuery($query);

            //         $rowInvoice = mysqli_fetch_array(mysqli_query(new mysqli( "mysql.ttparikh.club", "ttparikh", "zaidsaad", "ttparikh_master"), "SELECT * FROM Invoice WHERE Invoice_ID='$tempInvoiceID'"));
                    
            //         $Total_Count[$i] = $resultInvoice["Total_Count"];
            //         $Pickup[$i] = $resultInvoice["Pickup"];
            //         $Create_Time[$i] = $resultInvoice["Create_Time"];
                    
            //         //Customer - START
            //             $Customer_ID = $resultInvoice["Customer_ID"];
            //             $query = "SELECT * FROM Customer WHERE Customer_ID='$Customer_ID'";
            //             $resultCustomer = runQuery($query);
            //             $rowCustomer = mysqli_fetch_array($resultCustomer);
            //             $Customer_Name[$i] = $rowCustomer["Name"];
            //             $Company_Name[$i] = $rowCustomer["Company_Name"];

            //             mysqli_free_result($resultCustomer);

            //         //Customer - END
            //         $i++;
            //     }
            //     //$myDateTime = new DateTime();
            //     //echo $myDateTime;
            //     //All Product In Invoice - START
            //         // $query = "SELECT * FROM B_Invoice_Barcode WHERE Barcode_ID='$Barcode'";
            //         // $resultBarcode = runQuery($query);
                    
            //         // while ($rowBarcode = mysqli_fetch_array($resultBarcode)) {
            //         //     # code...
            //         // }
            //     //All Product In Invoice - END

            //     mysqli_free_result($resultInvoiceB);

            //     //Tracking - START
            //         // $query = "SELECT * FROM Tracking WHERE Invoice_ID=$Invoice_ID";
            //         // $resultTracking = runQuery($query);
            //         // $rowTracking = mysqli_fetch_array($resultTracking);
            //         // $Tracking_ID[$i] = $rowTracking["Tracking_ID"];
            //         // $Courier[$i] = $rowTracking["Courier"];
            //     //Tracking - END            
            // }
        
        //Invoice - END

        //Production - START
            // $query = "SELECT * FROM `Production_Machine` WHERE `Barcode_ID`='$Barcode_ID[0]'";
            // $resultBarcode = runQuery($query);
            // $i = 0;

            // while ($rowBarcode = mysqli_fetch_array($resultBarcode)) {
            //     $Barcode_ID[$i] = $rowBarcode["Barcode_ID"];
            //     $Online_Price[$i] = $rowBarcode["Online_Price"];
            //     $Bulk_Price[$i] = $rowBarcode["Bulk_Price"];
            //     $Total_Production[$i] = $rowBarcode["Total_Production"];
            //     $Total_Sold[$i] = $rowBarcode["Total_Sold"];
            //     $Adjustment[$i] = $rowBarcode["Adjustment"];
            //     $Mini_Reorder[$i] = $rowBarcode["Mini_Reorder"];
            //     $Type[$i] = $rowBarcode["Type"];

            //     //Location - START
            //         $query = "SELECT * FROM B_Location_Barcode WHERE Barcode_ID='$Barcode_ID[$i]'";
            //         $resultLocation = runQuery($query);
            //         $tempLocation = "";
            //         while ($rowLocation = mysqli_fetch_array($resultLocation)) {
            //             $tempLocation += $rowLocation["Location_ID"];
            //         }
            //         $Location_ID[$i] = $tempLocation;
            //     //Location - END
            //     $i++;
            // }
        //Production - END
        
        array_push($DATA, $Barcode_ID);                     //Barcode_ID - 0
        array_push($DATA, $Online_Price);                   //Online_Price - 1
        array_push($DATA, $Bulk_Price);                     //Bulk_Price - 2
        array_push($DATA, $Total_Production);               //Total_Production - 3
        array_push($DATA, $Total_Sold);                     //Total_Sold - 4
        array_push($DATA, $Adjustment);                     //Adjustment - 5
        array_push($DATA, $Mini_Reorder);                   //Mini_Reorder - 6
        array_push($DATA, $Type);                           //Type - 7

        array_push($DATA, $Location_ID);                    //Location_ID - 8

        array_push($DATA, $Customer_Name);                  //Customer_Name - 9
        array_push($DATA, $Company_Name);                   //Company_Name - 10

        array_push($DATA, $Tracking_ID);                    //Tracking_ID - 11
        array_push($DATA, $Courier);                        //Courier - 12

        array_push($DATA, $Invoice_Num);                    //Invoice_Num - 13
        array_push($DATA, $Total_Count);                    //Total_Count - 14
        array_push($DATA, $Pickup);                         //Pickup - 15
        array_push($DATA, $Create_Time);                    //Create_Time - 16

        array_push($DATA, $Product_ID=array($Product_ID));  //Product_ID - 17
        array_push($DATA, $Name);                           //Name - 18
        array_push($DATA, $Item_Code);                      //Item_Code - 19
        array_push($DATA, $Tax_Code);                       //Tax_Code - 20
        array_push($DATA, $Zone);                           //Zone - 21
        $json_OBJ = json_encode($DATA);                     //21
    }

    header("Location: ../productReport.php?DATA=$json_OBJ");
?>