<?php
    include "php/DBConnection.php";
    checkIfLoggedIn(4, 3, 1, null);
    $err = "";
    if(isset($_POST['submit']) && $_POST['Barcode_ID'] != ''){
        $Barcode_ID = $_POST["Barcode_ID"];
        $Taken_From = $_POST["taken_from"];
        $Expected_Weight = $_POST["Expected_Weight"];
        $Product_ID = mysqli_fetch_assoc(runQuery("SELECT Product_ID FROM Barcode WHERE Barcode_ID='$Barcode_ID'"))['Product_ID'];
        if ($Taken_From == 1) {
            $Bags_A = (int)$_POST["M_Bags_A"];
            $Bags_B = (int)$_POST["M_Bags_B"];
            $Location_ID_A = (int)$_POST["M_Location_ID_A"];
            $Location_ID_B = (int)$_POST["M_Location_ID_B"];
            $Lot_Num = $_POST["M_Lot_Num"];
            $Bot_Name = $_POST["M_Bot_Name"];
            $Bot_Name = addslashes($Bot_Name);
            $Country_Name = $_POST["M_Country_Name"];
            $Emp_Name = $_POST["M_Emp_Name"];
            
            //Lot_Number - START
                //echo "Lot_Num Before : " . $Lot_Num. "<br>";
                $result = runQuery("SELECT * FROM Lot_Number WHERE Lot_Number='$Lot_Num'");
                if($result->num_rows > 0){
                    $Lot_ID = mysqli_fetch_assoc($result)['Lot_ID'];
                }
                else{
                    $Lot_ID = runQueryGiveId("INSERT INTO Lot_Number (`Lot_Number`) VALUES ('$Lot_Num')");
                    //echo "<br>" .$Lot_ID . "<br>";
                }
                mysqli_free_result($result);
                
                $result = runQuery("SELECT * FROM B_Lot_Product WHERE Lot_ID='$Lot_ID' AND Product_ID='$Product_ID'");
                if($result->num_rows < 1 ){
                    runQuery("INSERT INTO B_Lot_Product (`Lot_ID`, `Product_ID`) VALUES ('$Lot_ID', '$Product_ID')");
                }
                mysqli_free_result($result);
            //Lot_Number - END

            //Botanical_Name - START
                if($Bot_Name){
                    $query = "SELECT * FROM Botanical_Name WHERE Bot_Name LIKE '$Bot_Name'";
                    $result = runQuery($query);
                    if($result->num_rows > 0){
                        $Bot_ID = mysqli_fetch_assoc($result)['Bot_ID'];
                        //echo "Bot Name Before : " . $Bot_Name;
                    }
                    else{
                        mysqli_free_result($result);
                        //echo "Bot Name Before : " . $Bot_Name;
                        $query = "INSERT INTO Botanical_Name (`Bot_Name`) VALUES ('$Bot_Name')";
                        $result = runQuery($query);

                        $query = "SELECT * FROM Botanical_Name WHERE Bot_Name LIKE '$Bot_Name'";
                        $result = runQuery($query);
                        if($result->num_rows > 0){
                            $Bot_ID = mysqli_fetch_assoc($result)['Bot_ID'];
                            //echo "Bot Name Before : " . $Bot_Name;
                        }
                    }
                    mysqli_free_result($result);
                    $query = "SELECT * FROM B_Botanical_Product WHERE Bot_ID='$Bot_ID' AND Product_ID='$Product_ID'";
                    $result = runQuery($query);
                    if($result->num_rows == 0){
                        mysqli_free_result($result);
                        $query = "INSERT INTO B_Botanical_Product (`Bot_ID`, `Product_ID`) VALUES ('$Bot_ID', '$Product_ID')";
                        $result = runQuery($query);
                    }
                    mysqli_free_result($result);
                }
            //Botanical_Name - END

            //Country - START
            
                $query = "SELECT * FROM Country WHERE Country_Name LIKE '%$Country_Name%'";
                $result = runQuery($query);
                if($result->num_rows > 0){
                    $Country_ID = mysqli_fetch_assoc($result)['Country_ID'];

                    $result = runQuery("SELECT * FROM B_Country_Product WHERE Country_ID='$Country_ID' AND Product_ID='$Product_ID'");
                    if($result->num_rows < 1 ){
                        runQuery("INSERT INTO B_Country_Product (`Country_ID`, `Product_ID`) VALUES ('$Country_ID', '$Product_ID')");
                    }
                    mysqli_free_result($result);    
                }
                else{
                    $err .= " ___ Country Not Found. <br/>";
                }
                mysqli_free_result($result);
            //Country - END
            
            if($Bot_ID){
                $checkIfExists_query = "SELECT * FROM Production_Machine WHERE `Barcode_ID`='$Barcode_ID' AND `Lot_ID`='$Lot_ID' AND `Bot_ID`='$Bot_ID' AND `Country_ID`='$Country_ID' AND `Bags`='$Bags' AND `Boxes`='$Boxes' AND `Toute`='$Toute' AND `Emp_Name`='$Emp_Name'";
                $query = "INSERT INTO Production_Machine (`Barcode_ID`, `Lot_ID`, `Bot_ID`, `Country_ID`, `Bags`, `Boxes`, `Toute`, `Emp_Name`) VALUES ('$Barcode_ID', '$Lot_ID', '$Bot_ID', '$Country_ID', '$Bags', '$Boxes', '$Toute', '$Emp_Name')";
            }
            else{
                $checkIfExists_query = "SELECT * FROM Production_Machine WHERE `Barcode_ID`='$Barcode_ID' AND `Lot_ID`='$Lot_ID' AND `Country_ID`='$Country_ID' AND `Bags`='$Bags' AND `Boxes`='$Boxes' AND `Toute`='$Toute' AND `Emp_Name`='$Emp_Name'";
                $query = "INSERT INTO Production_Machine (`Barcode_ID`, `Lot_ID`, `Country_ID`, `Bags`, `Boxes`, `Toute`, `Emp_Name`) VALUES ('$Barcode_ID', '$Lot_ID', '$Country_ID', '$Bags', '$Boxes', '$Toute', '$Emp_Name')";
            }
            $checkIfExists_result = runQuery($checkIfExists_query);
            if($checkIfExists_result->num_rows < 1){
                $Production_ID = runQueryGiveId($query);
                
                if($Production_ID != 0){
                    $err .= " ___ Production Entry Successful of ".$Barcode_ID." with Production_ID = " . $Production_ID . "<br/>";
                    //Update the information in the barcode - START
                        $result = runQuery("SELECT * FROM Barcode WHERE Product_ID='$Product_ID' ORDER BY Type ASC");
                        while ($row = mysqli_fetch_assoc($result)) {
                            $Barcode_ID = $row['Barcode_ID'];
                            $Type = $row['Type'];
                            $Total_Production = (int)$row['Total_Production'];
                            $temp = $Total_Production;
                            
                            $temp += ($Type == 'A') ? $Bags_A : $Bags_B;
                            $Location_ID = ($Type == 'A') ? $Location_ID_A : $Location_ID_B;

                            $result = runQuery("UPDATE Barcode SET Total_Production = '$temp' WHERE Barcode_ID = '$Barcode_ID'");
                            if($result){
                                //Location - START
                                    //echo "Location_ID Before : " . $Location_ID;
                                    $query = "SELECT * FROM Location WHERE Location_ID='$Location_ID'";
                                    $result = runQuery($query);
                                    if($result->num_rows > 0){
                                        $query = "SELECT * FROM B_Location_Barcode WHERE Location_ID='$Location_ID' && Barcode_ID='$Barcode_ID'";
                                        $result = runQuery($query);
                                        if($result->num_rows == 0){
                                            $query = "INSERT INTO `B_Location_Barcode` (`Location_ID`, `Barcode_ID`) VALUES ('$Location_ID', '$Barcode_ID')";
                                            $result = runQuery($query);
                                            if(!$result){
                                                $err .= " ___ Error with the B_Location_Barcode <br/>";
                                            }
                                        }
                                        else{
                                            $query = "UPDATE B_Location_Barcode SET Update_Time=now() WHERE Location_ID='$Location_ID' && Barcode_ID='$Barcode_ID'";
                                            $result = runQuery($query);
                                        }
                                        //echo "<br/>Location_ID After : " . $Location_ID;
                                    }
                                    else{
                                        $err .= " ___ Location Not Found. <br/>";
                                    }
                                //Location - END
                            }
                            else $err .= " ___ Production Inserted But Total_Production in Barcode is not updated.<br/>";    
                        }
                        
                        //Warehouse - START
                            $query = "SELECT * FROM B_Warehouse_Product WHERE Product_ID='$Product_ID' ORDER BY `Create_Time` ASC";
                            $result = runQuery($query);
                            if($result->num_rows > 0){
                                $CheckIfInserted = false;
                                while($row = mysqli_fetch_assoc($result)){
                                    // echo "inside while <br/><br/>";
                                    $skip = false;
                                    if ($row['Lot_ID'] == $Lot_ID) {
                                        // echo "Lot ID Found<br/><br/>";
                                        $total_weight = $Bags_A + ($Bags_B / 4);

                                        $Processed = (float)$row['Processed'];
                                        $Garbage = (float)$row['Garbage'];
                                        $Weight = (float)$row['Weight'];
                                        $Stock_ID = $row['Stock_ID'];
                                        if($Expected_Weight != "" && $Expected_Weight <= $Weight){
                                            // echo "Expected Weight = $Expected_Weight <= Weight = $Weight <br/><br/>";
                                            $temp_w_stock = $Weight - ($Processed + $Garbage + $Expected_Weight);
                                        }
                                        else{
                                            // echo "Inside skip <br/><br/>";
                                            $skip = true;
                                        }
                                        if(!$skip){
                                            //Edge Cases - START
                                                if ($temp_w_stock < 0) { //Edge case where adding expected weight the stock makes the stock less than zero
                                                    // echo "temp_w_stock = $temp_w_stock < 0 <br/><br/>";
                                                    if($total_bags < $Garbage)//Edge case where Garbage value entered is more than suspected.
                                                        $Garbage -= $total_bags;
                                                    else{
                                                        $err .= "!!!!!! Warehouse stock will be negative if this entry will be submitted. <br/>";
                                                        // echo "$err <br/><br/>";
                                                        break;
                                                    }
                                                }
                                                else //Most likely case
                                                    $Garbage += $Expected_Weight - $total_bags;
                                                
                                                $Processed += $total_bags;
                                            //Edge Cases - ENDS
                                            
                                            // echo "All edge cases checked : Garbage = $Garbage, Processed = $Processed, Weight = $Weight<br/><br/>";
                                            //Update values in B_Warehouse_Product - START
                                                if($Weight >= ($Processed + $Garbage)){ 
                                                    // echo "Inside the final if <br/><br/>";
                                                    $CheckIfInserted = true;
                                                    $query = "UPDATE B_Warehouse_Product SET Processed='$Processed', Garbage='$Garbage' WHERE Product_ID='$Product_ID' AND Stock_ID='$Stock_ID'";
                                                    $result = runQuery($query);
                                                    if($result) {
                                                        $err .= " ___ Production Entry for Processed Product is Successful for Warehouse Production.<br/>";
                                                        break;
                                                    }
                                                }
                                            //Update values in B_Warehouse_Product - END
                                        }
                                    }
                                }
                                if(!$CheckIfInserted)
                                $err .= " ___ Warehouse stock was unaffected as no matching entries were found.<br/>";
                            }
                        //Warehouse - START
                    //Update the information in the barcode - END
                }
                else
                    $err .= "This Production is similar to pre-existing production entry with Production_ID = " . mysqli_fetch_assoc($checkIfExists_result)["Production_ID"] . "<br/>";
            }

        }
        else if($Taken_From == 2) {
            $Bags = $_POST["D_Bags"];
            $Lot_Num = $_POST["D_Lot_Num"];
            $Invoice_Num = $_POST["D_Invoice_Num"];
            $Emp_Name = $_POST["D_Emp_Name"];

            //Lot_Number - START
                $query = "SELECT * FROM Lot_Number WHERE Lot_Number='$Lot_Num'";
                $result = runQuery($query);
                //print_r($result);
                if($result->num_rows > 0){
                    $Lot_ID = mysqli_fetch_assoc($result)['Lot_ID'];
                }
                else{
                    mysqli_free_result($result);
                    $query = "INSERT INTO Lot_Number (`Lot_Number`) VALUES ('$Lot_Num')";
                    $result = runQuery($query);
                    
                    $query = "SELECT * FROM Lot_Number WHERE Lot_Number='$Lot_Num'";
                    $result = runQuery($query);
                    if($result->num_rows > 0){
                        $Lot_ID = mysqli_fetch_assoc($result)['Lot_ID'];
                    }
                }
                mysqli_free_result($result);
                
                $result = runQuery("SELECT * FROM B_Lot_Product WHERE Lot_ID='$Lot_ID' AND Product_ID='$Product_ID'");
                if($result->num_rows < 1){
                    runQuery("INSERT INTO B_Lot_Product (`Lot_ID`, `Product_ID`) VALUES ('$Lot_ID', '$Product_ID')");
                }
                mysqli_free_result($result);
            //Lot_Number - END
            
            
            $query = "INSERT INTO Production_Direct (`Barcode_ID`, `Lot_ID`, `Bags`, `Emp_Name`, `Invoice_Num`) VALUES ('$Barcode_ID', '$Lot_ID', '$Bags', '$Emp_Name', '$Invoice_Num')";
            $checkIfExists_query = "SELECT * FROM Production_Direct WHERE `Barcode_ID`='$Barcode_ID' AND `Lot_ID`='$Lot_ID' AND `Bags`='$Bags' AND `Emp_Name`='$Emp_Name' AND `Invoice_Num`='$Invoice_Num'";
            
            $checkIfExists_result = runQuery($checkIfExists_query);
            if($checkIfExists_result->num_rows < 1){
                if(runQuery($query)){
                    $BarcodeRow = mysqli_fetch_assoc(runQuery("SELECT * FROM Barcode WHERE Barcode_ID='$Barcode_ID'"));
                    $Type = $BarcodeRow['Type'];
                    $Total_Production = $BarcodeRow['Total_Production'];
                    $temp = (int)$Total_Production;
                    $total_bags = (int)$Bags;
                    $temp += (int)$Bags;

                    $query = "UPDATE Barcode SET Total_Production = '$temp' WHERE Barcode_ID = '$Barcode_ID'";
                    $result = runQuery($query);
                    $err .= " ___ Production Entry Successesful of ".$Barcode_ID." with Production_ID = " . mysqli_fetch_assoc($checkIfExists_result)["Production_ID"];

                    //Warehouse - START
                        $query = "SELECT * FROM B_Warehouse_Product WHERE Product_ID='$Product_ID' ORDER BY `Create_Time` ASC";
                        $result = runQuery($query);
                        if($result->num_rows > 0){
                            while($row = mysqli_fetch_assoc($result)){
                                $Processed = (float)$row['Processed'];
                                $Weight = (float)$row['Weight'];
                                $Stock_ID = $row['Stock_ID'];
                                if($Type == 'A' ) $Processed += $total_bags;
                                else $Processed += ($total_bags / 4);

                                if($Weight > $Processed){
                                    $query = "UPDATE B_Warehouse_Product SET Processed='$Processed' WHERE Product_ID='$Product_ID' AND Stock_ID='$Stock_ID'";
                                    $result = runQuery($query);
                                    if($result)
                                        $err .= " ___ Production Entry for Processed Product is Successesful for Warehouse Production.";
                                    break;
                                }
                            }
                        }
                    //Warehouse - START
                }
            }
        }
        else if($Taken_From == 3 || $Taken_From == 4) {
            if ($Taken_From == 3) {
                $Conversion = "0";    //0 -> 1lb TO 114g : 1 -> 114g TO 1lb
                $Type_From = "A";
                $Type_To = "B";
            }
            else {
                $Conversion = "1";
                $Type_From = "B";
                $Type_To = "A";
            }
            $Bags_Cut = (int)$_POST["1_Bags_Cut"];
            $Bags_Made = (int)$_POST["1_Bags_Made"];
            $Lot_Num = $_POST["1_Lot_Num"];
            $Emp_Name = $_POST["1_Emp_Name"];
            
            //Lot_Number - START
                $query = "SELECT * FROM Lot_Number WHERE Lot_Number='$Lot_Num'";
                $result = runQuery($query);
                if($result->num_rows > 0){
                    $Lot_ID = mysqli_fetch_assoc($result)['Lot_ID'];
                }
                else{
                    mysqli_free_result($result);
                    $query = "INSERT INTO Lot_Number (`Lot_Number`) VALUES ('$Lot_Num')";
                    //echo "Lot_Num Before : " . $Lot_Num;
                    $result = runQuery($query);

                    $query = "SELECT * FROM Lot_Number WHERE Lot_Number='$Lot_Num'";
                    $result = runQuery($query);
                    if($result->num_rows > 0){
                        $Lot_ID = mysqli_fetch_assoc($result)['Lot_ID'];
                    }
                }
                mysqli_free_result($result);

                $result = runQuery("SELECT * FROM B_Lot_Product WHERE Lot_ID='$Lot_ID' AND Product_ID='$Product_ID'");
                if($result->num_rows < 1 ){
                    runQuery("INSERT INTO B_Lot_Product (`Lot_ID`, `Product_ID`) VALUES ('$Lot_ID', '$Product_ID')");
                }
                mysqli_free_result($result);
            //Lot_Number - END
            
            $Row_To = mysqli_fetch_assoc(runQuery("SELECT * FROM Barcode WHERE Product_ID='$Product_ID' AND Type ='$Type_To'"));
            $Total_Production_To = (int)$Row_To["Total_Production"];
            $Total_Production_To += $Bags_Made;

            $Row_From = mysqli_fetch_assoc(runQuery("SELECT * FROM Barcode WHERE Product_ID='$Product_ID' AND Type ='$Type_From'"));
            $Barcode_ID_From = $Row_From['Barcode_ID'];
            $Adjustment_From = (int)$Row_From['Adjustment'];
            $Adjustment_From -= $Bags_Cut;

            $query = "INSERT INTO Production_Convert 
            (`Barcode_ID`, `Lot_ID`, `Bags_Cut`, `Bags_Made`, `Conversion`, `Emp_Name`) 
            VALUES ('$Barcode_ID', '$Lot_ID', '$Bags_Cut', '$Bags_Made', '$Conversion', '$Emp_Name')";
            if($result = runQuery($query)){
                runQuery("UPDATE Barcode SET Adjustment = '$Adjustment_From' WHERE Barcode_ID = '$Barcode_ID_From'");
                runQuery("UPDATE Barcode SET Total_Production = '$Total_Production_To' WHERE Barcode_ID = '$Barcode_ID'");
                
                $err = "Successful!";
            }
        }
        else if($Taken_From == 5) {
            $F_Barcode_ID = $_POST["F_Barcode_ID"];
            $F_Emp_Name = $_POST["F_Emp_Name"];
            $F_Bags_Cut = (float)$_POST["F_Bags_Cut"];
            $F_Bags_Made = (float)$_POST["F_Bags_Made"];
            $Adjustment = (float)mysqli_fetch_assoc(runQuery("SELECT Adjustment FROM Barcode WHERE Barcode_ID='$Barcode_ID'"))['Adjustment'];
            $Total_Production = (float)mysqli_fetch_assoc(runQuery("SELECT Total_Production FROM Barcode WHERE Barcode_ID='$F_Barcode_ID'"))['Total_Production'];

            $Adjustment -= $F_Bags_Cut;
            $F_Bags_Made += $Total_Production;
            runQuery("UPDATE Barcode SET Total_Production='$F_Bags_Made' WHERE Barcode_ID='$F_Barcode_ID'");
            runQuery("UPDATE Barcode SET Adjustment='$Adjustment' WHERE Barcode_ID='$Barcode_ID'");
            $err = "Successful!";
        }
    }

    if($err != ""){ /*This part was taken from W3Schools.com*/
        $err = ucwords($err);?>
        <div id="snackbar"><?=$err?></div>
        <script>
            var x = document.getElementById("snackbar");
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 10000);
        </script>
    <?}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production | TTParikh</title>
    <link rel="icon" href="images/favicon.png">

    <!-- CSS File Link -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/iconStyle.css">

    <script src="js/allJavaScripts.js"></script>
    <script src="js/production_validation.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/9.4.4/math.js" integrity="sha512-XTa+nLKjbTCUazCWzwYpykjsTQDaepuKlg9YToCij7+Bdi9aHQhBQlV0rfnYmactJjHdusRQQV+6qWNNv0BScA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
        input[type=checkbox]{
            height: 20px;
            width:20px;
        }
        .item1 {
            grid-column: 1 / 9;
            grid-row: 1 / 3;
        }
        .item1 .page-title {
            color: var(--green-theme);
            font-family: 'Oswald', sans-serif;
            font-size: 25px;
            font-weight: 200;
            transition: ease 0.3s;
            margin-left: 30px;
        }
        .item1 input[type=button]{
            line-height: 4.9px;
            margin-left: -1.7px;
        }
        .item2 {
            grid-column: 1 / 9;
            grid-row: 3 / 5;
        }
        .item2 select, .item2 option{
            width: 100%;
            height: 30px;
            background-color: var(--purple-theme);
            border: 1px solid var(--green-theme);
            text-align:center;
        }
        .item2 select option{
            background: var(--purple-theme);
        }
        .item2 table{
            width: 100%;
            margin-top: 10px;
            font-size: 20px;
        }
        .item2 td, .item3 td{
            padding: 15px;
        }
        .item2 th, .item3 th{
            background-color: var(--green-theme);
            color: var(--purple-theme);
            padding: 15px;
        }
        .item2 a{
            text-decoration: underline;
        }
        .brd > td{
            border: 2px solid var(--green-theme);
        }
        
        .item3 {
            grid-column: 3 / 9;
            grid-row: 3 / 5;
        }
        .item3 .invoice-details{
            width: 100%;
        }
        .item3 table{
            width: 100%;
            font-size: 20px;
        }
        .item3 .details table{
            text-align: center;
        }
        .item3 .details table *{
            padding: 15px;
        }
        .item3 .invoice-details div{
            padding: 20px;
        }

        .item4 {
            grid-column: 1 / 9;
            grid-row: 5 / 7;
        }
        .item4 table{
            border: 1px dashed var(--green-theme);
            margin: 10px;
            padding: 10px;
            font-size: 20px;
        }
        .item4 td{
            padding: 15px;
            font-size: 20px;
            text-align: center;
        }
        .item5{
            grid-column: 1 / 11;
            padding: 0px;
            text-align: center;
        }
        
        .suggestion{
            position:absolute;
            background-color:var(--purple-theme);
            font-size:smaller;
            cursor: pointer;
        }
        .suggestion_i:hover{
            color:var(--purple-theme);
            background-color:var(--green-theme);
        }
        .suggestion_i{
            cursor: pointer;
            text-justify:center;
            padding-top: 10px;
            padding-bottom: 10px;
            padding-left: 5px;
            padding-right: 5px;
        }

        #instruction {
            padding-left: 20px;
            color: var(--red-theme);
            font-size: 20px;
        }
        #instruction span {
            box-sizing: border-box;
        }

        /* Auto Complete functionality */
        .autocomplete {
            position: relative;
            display: inline-block;
            margin-top:20px;
            width: 100%;
        }

        .autocomplete-items {
            position: absolute;
            border: 1px solid var(--green-theme);
            border-top: none;
            z-index: 99;
            color: var(--font-color);
            /*position the autocomplete items to be the same width as the container:*/
            top: 100%;
            left: 0;
            right: 0;
        }

        .autocomplete-items > div {
            padding: 10px 20px;
            cursor: pointer;
            font-size: 15px;
            background-color: var(--purple-theme);
        }
        .autocomplete-items > div {
            color: white;
        }
        .autocomplete-items p{
            color: var(--dark-font-color);
        }

        /*when navigating through the items using the arrow keys:*/
        .autocomplete-active{
            background-color: var(--green-theme) !important; 
            color: var(--purple-theme) !important;
        }
        .autocomplete-active *{
            background-color: var(--green-theme) !important; 
            color: var(--purple-theme) !important;
        }
        /*when hovering an item:*/
        .autocomplete-items div:hover {
            background-color: var(--lightgreen-theme);
            color: var(--purple-theme);
        }
    </style>

</head>
<body onclick="removeSuggestion()">
    <div class="sidebar" id = "sidebar_Id">
        <div class="search-box">
            <table>
                <tr>
                    <td><input type="text" name="search" placeholder="Search..." id="invoiceStatus_text"></td>
                    <td><input type="submit" value="GO" id="invoiceStatus_go"></td>
                </tr>
            </table>
        </div>
        <a href='index.php'><div class="menu"><span class="icon-clipboard"></span><span>Dashboard</span></div></a>
        <a href='invoice.php'><div class="menu"><span class="icon-browser"></span><span>Invoice</span></div></a>
        <a onclick="dropdownToggle()"><div class="menu dropdown-btn"><span class="icon-presentation"></span><span>Report</span><span><i data-feather="chevron-down"></i></span></div></a>
        <div class="side-drop" id="side-drop-id">
            <a href='invoiceReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Invoice Report</span></div></a>
            <a href='productReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Product Report</span></div></a>
            <a href='customerReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Customer Report</span></div></a>
            <a href='trackingReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Tracking Report</span></div></a>
            <a href='warehouseReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Warehouse Report</span></div></a>
        </div>
        <a href='production.php'><div class="menu highlight"><span class="icon-gift"></span><span>Production</span></div></a>
        <a href='vendor.php'><div class="menu"><span class="icon-basket"></span><span>Vendor</span></div></a>
        <a href='products.php'><div class="menu"><span class="icon-beaker"></span><span>Products</span></div></a>
        <a href='tracking.php'><div class="menu"><span class="icon-map"></span><span>Tracking Number</div></a>
        <a href='invoiceStatus.php'><div class="menu"><span class="icon-lightbulb"></span><span>Invoice Status</span></div></a>
        <a href='customer.php'><div class="menu"><span class="icon-profile-male"></span><span>Customer</span></div></a>
        <a href='warehouse.php'><div class="menu"><span class="icon-gift"></span><span>Warehouse</span></div></a>
        <a href='adjustment.php'><div class="menu"><span class="icon-gears"></span><span>Adjustment</span></div></a>
    </div>

    <div class="root" id = "root_Id">
        <header>
            <div class="burgureMenuIcon change" onclick="openCloseSidebar(this)">
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
            </div>
            <a class="logo" href="index.php">Trade Technocrats Ltd.</a>
            <ul class="nav_links">
                <input type="color" name="" id="colorPicker1" style="width:auto; background-color:var(--green-theme);border:none;">
                <a href="index.php"><i data-feather="bell"></i></a>
                <a href="#Form"><li><i data-feather="flag" alt="flag"></i></li></a>
                <a href="#ContactUs" onclick=someFunc() ><i data-feather="github"></i></a>
                <input type="color" name="" id="colorPicker2" style="width:auto; background-color:var(--green-theme);border:none;">
            </ul>
        </header>
        <div class="grid_container">
            <div class="grid item1">
                <div class="page-title">
                    <span>New Production Entry :</span>
                </div>
            </div>

            <div class="grid item2">
                <div class="inside">
                    <span>Entry Feed</span>
                    <div class="autocomplete">
                        <!-- onkeyup="searchProduct(this.value)" -->
                        <input type="text" placeholder="Type Product Name" name="search" autocomplete="off" id="product_name_1">
                    </div>
                </div>
                <div class="details">
                    <form id='form_id' action="production.php" method="post" enctype="multipart/form-data">
                        <div id="instruction"></div>
                        <table style="float:left">
                            <tr>
                                <td id="Original-Barcode">Barcode :</td>
                                <td><input type="text" autocomplete="off" name="Barcode_ID" id="Barcode_ID" onchange='getProduction(this.value)'></td>
                                <td>Taken From :</td>
                                <td><select name="taken_from" id="taken_from">
                                    <option value = "1">Machine</option>
                                    <option value = "2">Direct</option>
                                    <option value = "3">1.00 lb --> 0.25 lb</option>
                                    <option value = "4">0.25 lb --> 1.00 lb</option>
                                    <option value = "5">Form Alter</option>
                                </select></td>
                            </tr>
                        </table>
                        <table id="machine">
                            <tr>
                                <td>Calculate :</td>
                                <td><input type="text" size="5" name="Calculate" id="Calculate" onchange="calculateWeight(this)"/></td>
                                <td>Expected Weight :</td>
                                <td><input type="text" size="5" name="Expected_Weight" id="Expected_Weight" onchange="showGarbageValue(this.value)"/></td>
                            </tr>
                            <tr class="brd">
                                <td style="border-right:none; border-bottom:none">1.00 LB :</td>
                                <td style="border-left:none; border-bottom:none"><input type="text" name="M_Bags_A" id="M_Bags_A"></td>
                                <td style="border-right:none; border-bottom:none">0.25 LB :</td>
                                <td style="border-left:none; border-bottom:none"><input type="text" name="M_Bags_B" id="M_Bags_B"></td>
                            </tr>
                            <tr class="brd">
                                <td style="border-right:none; border-top:none">Location (1.00 LB) :</td>
                                <td style="border-left:none; border-top:none"><input type="text" name="M_Location_ID_A" id="M_Location_ID_A"></td>
                                <td style="border-right:none; border-top:none">Location (0.25 LB) :</td>
                                <td style="border-left:none; border-top:none"><input type="text" name="M_Location_ID_B" id="M_Location_ID_B"></td>
                            </tr>
                            <tr>
                                <td>Lot Number :</td>
                                <td><input type="text" name="M_Lot_Num" id="M_Lot_Num"></td>
                                <td>Botanical Name :</td>
                                <td><input type="text" name="M_Bot_Name" id="M_Bot_Name"></td>
                            </tr>
                            <tr>
                                <td>Country Of Origin :</td>
                                <td><input type="text" autocomplete="off" name="M_Country_Name" id="M_Country_Name" onkeyup="countryOnChange(this.value)"></td>
                                <td>Name :</td>
                                <td><input type="text" name="M_Emp_Name" id="M_Emp_Name"></td>
                            </tr>
                            <tr><td></td><td><div id="C_suggestion" class="suggestion"></div></td></tr>
                        </table>
                        <table id="direct" style="display:none">
                            <tr>
                                <td>Bags :</td>
                                <td><input type="text" name="D_Bags" id="D_Bags"></td>
                                <td>Lot Number :</td>
                                <td><input type="text" name="D_Lot_Num" id="D_Lot_Num"></td>
                            </tr>
                            <tr>
                                <td>Invoice Number :</td>
                                <td><input type="text" name="D_Invoice_Num" id="invoice_name" onchange="invoiceSpliter()"></td>
                                <td>Name :</td>
                                <td><input type="text" name="D_Emp_Name" id="D_Emp_Name"></td>
                            </tr>
                        </table>
                        <table id="1lb" style="display:none">
                            <tr>
                                <td><label id="label_bags_cut">Bags Cut :</label></td>
                                <td><input type="text" name="1_Bags_Cut" id="1_Bags_Cut"></td>
                                <td><label id="label_bags_made">Bags Made :</label></td>
                                <td><input type="text" name="1_Bags_Made" id="1_Bags_Made"></td>
                            </tr>
                            <tr>
                                <td><label>Lot Number :</label></td>
                                <td><input type="text" name="1_Lot_Num" id="1_Lot_Num"></td>
                                <td><label>Name :</label></td>
                                <td><input type="text" name="1_Emp_Name" id="1_Emp_Name"></td>
                            </tr>
                        </table>
                        <table id="form_alter" style="display:none">
                            <tr>
                                <td>Barcode of Converted Product :</td>
                                <td><input type="text" name="F_Barcode_ID" id="F_Barcode_ID"></td>
                                <td>Bags Cut :</td>
                                <td><input type="text" name="F_Bags_Cut" id="F_Bags_Cut"></td>
                            </tr>
                            <tr>
                                <td>Bags Made :</td>
                                <td><input type="text" name="F_Bags_Made" id="F_Bags_Made"></td>
                                <td>Name :</td>
                                <td><input type="text" name="F_Emp_Name" id="F_Emp_Name"></td>
                            </tr>
                        </table>                        
                        <table>
                            <tr>
                                <td><input type="reset" value="Reset"></td>
                                <td><input type="submit" value="Submit" name="submit"></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>

            <div class="grid item4">
                <div class="inside">
                    <span>Recent Production :</span>
                </div>
                <div class="details">
                    <table>
                        <tr>
                            <th>Create_Time</th>
                            <th>Name</th>
                            <th>Bags</th>
                            <th>Boxes</th>
                            <th>Toute</th>
                            <th>Lot_Number</th>
                            <th>Bot_Name</th>
                            <th>Country_Name</th>
                            <th>Emp_Name</th>
                        </tr>

                    <?php
                        $result = runQuery("SELECT
                                                Product.Name,
                                                B.Type,
                                                L.Lot_Number,
                                                Bot.Bot_Name,
                                                C.Country_Name,
                                                p.Create_Time,
                                                p.Bags,
                                                p.Boxes,
                                                p.Toute,
                                                p.Emp_Name
                                            FROM
                                                Production_Machine AS p
                                            INNER JOIN Lot_Number AS L
                                            ON
                                                L.Lot_ID = p.Lot_ID
                                            INNER JOIN Botanical_Name AS Bot
                                            ON
                                                Bot.Bot_ID = p.Bot_ID
                                            INNER JOIN Country AS C
                                            ON
                                                C.Country_ID = p.Country_ID
                                            INNER JOIN Barcode AS B
                                            ON
                                                B.Barcode_ID = p.Barcode_ID
                                            INNER JOIN Product 
                                            ON 
                                                Product.Product_ID = B.Product_ID
                                            ORDER BY
                                                Create_Time
                                            DESC
                                            LIMIT 10");
                        while($row = mysqli_fetch_assoc($result)){?>
                        <tr>
                            <td><?=time2str(strtotime($row["Create_Time"]))?></td>
                            <td><? echo $row["Name"]; if($row["Type"] == 'B') echo " - 114g";?></td>
                            <td><?=$row["Bags"]?></td>
                            <td><?=$row["Boxes"]?></td>
                            <td><?=$row["Toute"]?></td>
                            <td><?=$row["Lot_Number"]?></td>
                            <td><?=$row["Bot_Name"]?></td>
                            <td><?=$row["Country_Name"]?></td>
                            <td><?=$row["Emp_Name"]?></td>
                        </tr>
                    <?}?>
                    </table>
                </div>
            </div>

        </div>
    </div>
    
    <script>
        feather.replace();
        var dropdown = document.querySelectorAll(".drop-content");
        for (let i = 0; i < dropdown.length; i++) {
            dropdown[i].style.display = "none";
        }
        window.onload = function(event){
            document.getElementById("product_name_1").focus();

            document.getElementById("taken_from").addEventListener("change", changeProductionForm, false);
            document.getElementById("form_id").addEventListener("submit", formValidation, false);
            document.getElementById("form_id").addEventListener("reset", resetForm, false);

            document.getElementById("invoiceStatus_go").addEventListener("click", submitInvoiceStatus, false);

            //suggestion box function
            autocomplete(document.getElementById("M_Lot_Num"));
            autocomplete(document.getElementById("product_name_1"));

            if("<?=$_GET['info']?>" != "")
                alert("<?=$_GET['info']?>");
        }
        //Suggestion Box Ajax Function
        function suggestionAjax(a, b, input) {
            if(input.id == "M_Lot_Num"){
                var val = input.value;
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        var result = JSON.parse(this.responseText);
                        for (let j = 0; j < result.length; j++) {
                            b = document.createElement("DIV");

                            /*make the matching letters bold:*/
                            b.innerHTML = "<strong>" + result[j].substr(0, val.length) + "</strong>";
                            b.innerHTML += result[j].substr(val.length);

                            /*execute a function when someone clicks on the item value (DIV element):*/
                            b.addEventListener("click",
                                function (e) {
                                    input.value = result[j];
                                }
                            );
                            a.appendChild(b);
                        }
                    }
                };
                xhttp.open("GET", "./php/production_lotSearchAjax.php?search=" +
                    encodeURI(val), true);
                xhttp.send();
            }
            else if(input.id == "product_name_1"){
                var val = input.value;
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        var result = JSON.parse(this.responseText);
                        for (let j = 0; j < result[0].length; j++) {
                            b = document.createElement("DIV");

                            /*make the matching letters bold:*/
                            b.innerHTML = "<strong>" + result[0][j].substr(0, val.length) + "</strong>";
                            b.innerHTML += result[0][j].substr(val.length);

                            /*execute a function when someone clicks on the item value (DIV element):*/
                            b.addEventListener("click",
                                function (e) {
                                    document.getElementById("Barcode_ID").value = result[1][j];

                                    input.value = result[0][j];
                                    if(result[2][j] == 'B')
                                        input.value = result[0][j] + " - 114g";
                                        
                                        getProduction(result[1][j]);
                                        
                                    document.getElementById("taken_from").focus();
                                }
                            );
                            a.appendChild(b);
                        }
                    }
                };
                xhttp.open("GET", "./php/productSearchAjax.php?search=" + encodeURI(val), true);
                xhttp.send();
            }
        }

        function searchProduct(str){
            str = str.toUpperCase();
            if (str.length==0){
                document.getElementById("suggestion").innerHTML="";
                document.getElementById("suggestion").style.padding="0";
                document.getElementById("suggestion").style.border="none";
                // document.getElementById("suggestion").style.border="none";
                return;
            }
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp=new XMLHttpRequest();
            } else {  // code for IE6, IE5
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange=function() {
                if (this.readyState==4 && this.status==200) {
                    var to_show = "";
                    var results = JSON.parse(this.responseText)
                    if (results.length > 0){
                        //console.log(results);
                        for (var i = 0; i < results[0].length; i++)
                        {
                            to_show += "<div class='suggestion_i' onclick='changeBarcodeValue(\""+results[1][i]+"\", \""+results[0][i]+"\", \""+results[2][i]+"\")'>"+results[0][i]+"</div>";
                        }
                    }
                    else
                        to_show = "No Result Found";
                    document.getElementById("suggestion").innerHTML=to_show;
                    document.getElementById("suggestion").style.text_align="left";
                    document.getElementById("suggestion").style.padding="20px";
                    document.getElementById("suggestion").style.border="1px solid var(--green-theme)";
                    // document.getElementById("suggestion").style.border="1px solid #A5ACB2";
                }
            }
            xmlhttp.open("GET", "./php/productSearchAjax.php?search=" + str, true);
            xmlhttp.send();    
        }

        function changeBarcodeValue(str, name, type) {
            document.getElementById("Barcode_ID").value = str;

            if(type == 'B')
                document.getElementById("product_name_1").value = name + " - 114g";
            else
                document.getElementById("product_name_1").value = name;
                
                getProduction(str);
                removeSuggestion();
                
            document.getElementById("taken_from").focus();
        }
        function countryOnChange(str) {
            str = str.toUpperCase();
            if (str.length==0){
                document.getElementById("C_suggestion").innerHTML="";
                document.getElementById("C_suggestion").style.padding="0";
                document.getElementById("C_suggestion").style.border="none";
                return;
            }
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp=new XMLHttpRequest();
            } else {  // code for IE6, IE5
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange=function() {
                if (this.readyState==4 && this.status==200) {
                    var to_show = "";
                    var results = JSON.parse(this.responseText)
                    if (results.length > 0){
                        for (var i = 0; i < results.length; i++)
                        {
                            to_show += "<div class='suggestion_i' onclick='insertValues(\"" + results[i] + "\")'>" + results[i] + "</div>";
                        }
                    }
                    else
                        to_show = "No Result Found";
                    document.getElementById("C_suggestion").innerHTML=to_show;
                    document.getElementById("C_suggestion").style.text_align="left";
                    document.getElementById("C_suggestion").style.padding="20px";
                    document.getElementById("C_suggestion").style.border="1px solid var(--green-theme)";
                }
            }
            xmlhttp.open("GET", "./php/countrySearchAjax.php?search=" + str, true);
            xmlhttp.send();    

        }
        function lotOnfocus() {
            str = document.getElementById("Barcode_ID").value;
            if (str.length==0){
                document.getElementById("C_suggestion").innerHTML="";
                document.getElementById("C_suggestion").style.padding="0";
                document.getElementById("C_suggestion").style.border="none";
                return;
            }
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp=new XMLHttpRequest();
            } else {  // code for IE6, IE5
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange=function() {
                if (this.readyState==4 && this.status==200) {
                    var to_show = "";
                    var results = JSON.parse(this.responseText);
                    if (results.length > 0){
                        for (var i = 0; i < results.length; i++)
                        {
                            to_show += "<div class='suggestion_i' onclick='insertValues(\"" + results[i] + "\")'>" + results[i] + "</div>";
                        }
                    }
                    else
                        to_show = "No Result Found";
                    document.getElementById("C_suggestion").innerHTML=to_show;
                    document.getElementById("C_suggestion").style.text_align="left";
                    document.getElementById("C_suggestion").style.padding="20px";
                    document.getElementById("C_suggestion").style.border="1px solid var(--green-theme)";
                }
            }
            xmlhttp.open("GET", "./php/lotSearchAjax.php?search="+str, true);
            xmlhttp.send();    

        }

        function insertValues(Country) {
            document.getElementById("M_Country_Name").value = Country;
            removeSuggestion();
        }
        function removeSuggestion() {
            if(!$("#product_name_1").is(":focus")){
                document.getElementById("C_suggestion").innerHTML="";
                document.getElementById("C_suggestion").style.padding="0px";
                document.getElementById("C_suggestion").style.border="none";
            }
        }
        function invoiceSpliter() {
            var current_element = document.getElementById("invoice_name");
            var current_value= current_element.value;

            var invoices = current_value.split("} ");
            if(!invoices[1])
                return;
            var temp = invoices[0].split("{");
            var invoiceNumber = temp[1];

            current_element.value = invoiceNumber;
        }

        function calculateWeight(val) {
            if(val.value)
                val.value = math.evaluate(val.value);
        }
        function showGarbageValue(val) {
            var taken_from = document.getElementById("taken_from").value.toString();
            var M_Bags = document.getElementById("M_Bags").value;
            var M_Boxes = document.getElementById("M_Boxes").value;
            var M_Toute = document.getElementById("M_Toute").value;
            var D_Bags = document.getElementById("D_Bags").value;
            var barcode = document.getElementById('Barcode_ID').value;
            if((taken_from == '1' || taken_from == 2) && ((M_Bags != "" && M_Boxes != "" && M_Toute != "") || D_Bags != "") && barcode != null){
                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp=new XMLHttpRequest();
                } else {  // code for IE6, IE5
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange=function() {
                    if (this.readyState==4 && this.status==200) {
                        var to_show = "<br/><br/>";
                        var results = JSON.parse(this.responseText)
                        console.log(results);
                        if (results){
                            let stock;
                            var production = D_Bags;
                            if(taken_from == '1')
                                production = parseInt(M_Bags) * parseInt(M_Boxes) + parseInt(M_Toute);
                            
                                console.log("M_Bags", M_Bags);
                                console.log("M_Boxes", M_Boxes);
                                console.log("M_Toute", M_Toute);
                                console.log("production", production);
                                
                            stock = results["Weight"] - (results["Processed"] + production) - results["Garbage"];
                                
                            if(val.indexOf("*") != -1){
                                const myArr = val.split("*");
                                val = parseInt(myArr[0].trim()) * parseInt(myArr[1].trim());
                                document.getElementById("Expected_Weight").value = val;
                            }
                            to_show += "<span>Process Weight = " + val + ", </span><span>Garbage value = " + (val - production) + "</span>";
                        }
                        else
                            to_show = "No Result Found";
                        document.getElementById("instruction").innerHTML+=to_show;
                    }
                }
                xmlhttp.open("GET", "./php/getWarehouseDetails.php?barcode=" + barcode, true);
                xmlhttp.send();    
            }
        }
    </script>
</body>
</html>