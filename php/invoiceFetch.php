<?php
    include "DBConnection.php";
    
    $Customer_Name = $_POST["invoice_name"];
    $Invoice_Num = $_POST["invoice_num"];
    $Total_Count = $_POST["total_quantity"];
    $Status = $_POST["Status"];
    $Status_PickedUp = $_POST["Status_PickedUp"];
    //$Pickup = $_POST[""];
    if($Invoice_Num == "" || $Invoice_Num == " "){
        $info = "Please enter Invoice Number and Customer Name, No empty invoices are allowed";
    }
    else if($_POST["AddOn"]){
        if($Customer_Name == '' || $Customer_Name == ' ')
            $Customer_ID = 1777;
        else{
            $temp = explode(" - ", $Customer_Name);

            $Name = $temp[0];
            $Company_Name = $temp[1];
            //echo "Name = $Name  and CompanyName = $CompanyName<br>";
            $Name = str_replace("^", "'", $Name);
            $Company_Name = str_replace("^", "'", $Company_Name);

            $Name = htmlspecialchars($Name);
            $Name = addslashes($Name);
            $Company_Name = htmlspecialchars($Company_Name);
            $Company_Name = addslashes($Company_Name);

            $query = "SELECT Customer_ID FROM Customer WHERE Name='$Name' AND Company_Name='$Company_Name'";
            //echo "query = $query <br>";
            $result = runQuery($query);

            if($result->num_rows > 0){
                $Customer_ID = mysqli_fetch_array($result)["Customer_ID"];
                //echo "Customer_ID = $Customer_ID <br/>";
            }
            else{
                $query = "INSERT INTO Customer (Name, Company_Name) VALUES ('$Name', '$Company_Name')";
                //echo "query = $query <br/>";
                $result = runQuery($query);

                $query = "SELECT Customer_ID FROM Customer WHERE Name='$Name' AND Company_Name='$Company_Name' ORDER BY Customer_ID DESC";
                //echo "query = $query <br/>";
                $result = runQuery($query);
        
                $Customer_ID = mysqli_fetch_array($result)['Customer_ID'];
            }
        }

        $query = "SELECT * FROM Invoice WHERE Customer_ID='$Customer_ID' AND Invoice_Num='$Invoice_Num' ORDER BY Invoice_ID DESC";
        //echo "query = $query <br/>";
        $row = mysqli_fetch_array(runQuery($query));

        $Invoice_ID = $row['Invoice_ID'];
        $Total_Count = (int)$row["Total_Count"];

        for ($i=1; $i <= ((count($_POST) - 5) / 4); $i++) { 
            $Barcode_ID = $_POST["barcode_".$i];
            $Count = $_POST["count_".$i];

            if ($Barcode_ID != "" || $Barcode_ID != " ") {
                runQuery("INSERT INTO `B_Invoice_Barcode` (`Invoice_ID`, `Barcode_ID`, `Count`) VALUES ('$Invoice_ID', '$Barcode_ID', '$Count')");
                
                $result = runQuery("SELECT Total_Sold FROM Barcode WHERE `Barcode_ID`='$Barcode_ID'");
                
                $Total_Sold = (int)mysqli_fetch_array($result)["Total_Sold"];
                
                $Total_Sold += $Count;
                $Total_Count += $Count;
                
                runQuery("UPDATE Barcode SET Total_Sold = '$Total_Sold' WHERE `Barcode_ID`='$Barcode_ID'");
            }
        }
            
        runQuery("UPDATE Invoice SET Total_Count = '$Total_Count' WHERE Invoice_ID = '$Invoice_ID'");
    }
    else{
        if($Customer_Name == '' || $Customer_Name == ' ')
            $Customer_ID = 1727;
        else{
            $temp = explode (" - ", $Customer_Name);

            $Name = $temp[0];
            $Company_Name = $temp[1];

            $Name = str_replace("^", "'", $Name);
            $Company_Name = str_replace("^", "'", $Company_Name);
            //echo "query = $query <br>";
            $Name = htmlspecialchars($Name);
            $Name = addslashes($Name);
            $Company_Name = htmlspecialchars($Company_Name);
            $Company_Name = addslashes($Company_Name);

            $query = "SELECT Customer_ID FROM Customer WHERE Name='$Name' AND Company_Name='$Company_Name'";
            //echo "query = $query <br>";
            $result = runQuery($query);

            if($result->num_rows > 0){
                $Customer_ID = mysqli_fetch_array($result)["Customer_ID"];
                ////echo "Customer_ID = $Customer_ID <br/>";
            }
            else{
                $query = "INSERT INTO Customer (Name, Company_Name) VALUES ('$Name', '$Company_Name')";
                //echo "query = $query <br/>";
                $result = runQuery($query);


                $query = "SELECT Customer_ID FROM Customer WHERE Name='$Name' AND Company_Name='$Company_Name' ORDER BY Customer_ID DESC";
                //echo "query = $query <br/>";
                $result = runQuery($query);
        
                $Customer_ID = mysqli_fetch_array($result)['Customer_ID'];
            }
        }

        $query = "INSERT INTO Invoice (Customer_ID, Invoice_Num, Total_Count) VALUES ('$Customer_ID', '$Invoice_Num', '$Total_Count')";
        //echo "query = $query <br/>";
        $result = runQuery($query);
        //$query = "SELECT * FROM Invoice WHERE Invoice_Num='$Invoice_Num'";
        $query = "SELECT * FROM Invoice WHERE Customer_ID='$Customer_ID' AND Invoice_Num='$Invoice_Num' ORDER BY Invoice_ID DESC";
        //echo "query = $query <br/>";
        $result = runQuery($query);

        $Invoice_ID = mysqli_fetch_array($result)['Invoice_ID'];
        for ($i=1; $i <= ((count($_POST) - 5) / 4); $i++) { 
            $Barcode_ID = $_POST["barcode_".$i];
            $Count = $_POST["count_".$i];

            if ($Barcode_ID == "" || $Barcode_ID == " ") {
                break;
            }
            $query = "INSERT INTO `B_Invoice_Barcode` (`Invoice_ID`, `Barcode_ID`, `Count`) VALUES ('$Invoice_ID', '$Barcode_ID', '$Count')";
            //echo "query = $query <br/>";
            $result = runQuery($query);

            $query = "SELECT Total_Sold FROM Barcode WHERE `Barcode_ID`='$Barcode_ID'";
            //echo "query = $query <br/>";
            $result = runQuery($query);

            $Total_Sold = mysqli_fetch_array($result)["Total_Sold"];
            $Total_Sold = (int)$Total_Sold;
            //echo "Total_Sold = $Total_Sold <br/>";

            $Total_Sold += $Count;

            $query = "UPDATE Barcode SET Total_Sold = '$Total_Sold' WHERE `Barcode_ID`='$Barcode_ID'";
            //echo "query = $query <br/> <br/>";
            $result = runQuery($query);

        }
        $info = "Invoice Saved Succesfully with Invoice_ID = " . $Invoice_ID . " and Invoice_Num = " . $Invoice_Num;
        if($Status){
            $last_id = runQueryGiveId("INSERT INTO Invoice_Status (`Invoice_ID`) VALUE ('$Invoice_ID')");
            if($Status_PickedUp)
                runQuery("UPDATE Invoice_Status SET `Status` = '2' WHERE `Status_ID` = '$last_id'");

            $info .= " __last_ID = $last_id";
        }
    }
    header("Location: ../invoice.php?info=$info");