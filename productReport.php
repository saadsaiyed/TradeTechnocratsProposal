<?php
    include "php/DBConnection.php";
    checkIfLoggedIn(2, 3, 4, 1);

    $search = $_GET["search"];
    if($search){
        $query = "SELECT * FROM Product WHERE Name LIKE '$search%'";
        $result = runQuery($query);
        /* if($result->num_rows == 0){
            //$search = mysql_real_escape_string($search);
            //echo "Here |" . $search . "|<br/>";
            $query = "SELECT * FROM Product WHERE Item_Code='$search'";
            echo $query . "<br/>";
            $result = runQuery($query);
        } */
        if($result->num_rows == 0){
            $query = "SELECT * FROM Product WHERE Product_ID LIKE'%$search%' LIMIT 1";
            $result = runQuery($query);    
        }
        if($result->num_rows == 0){
            $query = "SELECT * FROM Barcode WHERE Barcode_ID LIKE '%$search%' LIMIT 1";
            $result = runQuery($query);
            if($result->num_rows != 0){
                $row = mysqli_fetch_array($result);
                $Product_ID = $row['Product_ID'];
                $query = "SELECT * FROM Product WHERE Product_ID LIKE '%$Product_ID%' LIMIT 1";
                $result = runQuery($query);
            }
        }
        if($result->num_rows != 0){
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
                    $tempYearlySell = (int)mysqli_fetch_assoc(runQuery("SELECT sum(Count) AS 'Sum' FROM B_Invoice_Barcode WHERE `Barcode_ID`='$Barcode_ID[$i]'"))['Sum'];
                    $tempYearlySell /= monthDifference();
                    $Mini_Reorder[$i] = $tempYearlySell * 12;
                    $Type[$i] = $rowBarcode["Type"];

                    //Location - START
                        $query = "SELECT * FROM B_Location_Barcode WHERE Barcode_ID='$Barcode_ID[$i]' ORDER BY Create_Time DESC";
                        $resultLocation = runQuery($query);
                        $tempLocation = "";
                        while ($rowLocation = mysqli_fetch_array($resultLocation)) {
                            $tempLocation .= $rowLocation["Location_ID"] . ", ";                                
                        }
                        mysqli_free_result($resultLocation);

                        $Location_ID[$i] = substr($tempLocation, 0, -2);
                    //Location - END
                    $i++;
                }
                $Ratio_Of_1lb_To_114g = round($Mini_Reorder[0]/$Mini_Reorder[1], 1);
                
                mysqli_free_result($resultBarcode);

            //Barcode - END

            //Invoice - START
                $Invoice_ID = array();
                $Invoice_Num = array();
                $Total_Count = array();
                $Pickup = array();
                $Create_Time = array();

                $Customer_ID = array();
                $Customer_Name = array();
                $Company_Name = array();

                $Tracking_ID = array();
                $Courier = array();
                            
                $i = 0;
                foreach ($Barcode_ID as $Barcode) {
                    $query = "SELECT * FROM B_Invoice_Barcode WHERE Barcode_ID='$Barcode' ORDER BY Create_Time DESC LIMIT 10";
                    $resultInvoiceB = runQuery($query);
                    while ($rowInvoiceB = mysqli_fetch_array($resultInvoiceB)) {
                        $Invoice_ID[$i] = $rowInvoiceB["Invoice_ID"];
                        
                        $tempInvoiceID = $Invoice_ID[$i];
                        
                        $rowInvoice = mysqli_fetch_array(runQuery("SELECT * FROM Invoice WHERE Invoice_ID='$tempInvoiceID'"));
                        
                        $Invoice_Num[$i] = $rowInvoice["Invoice_Num"];
                        $Total_Count[$i] = $rowInvoiceB["Count"];
                        $Pickup[$i] = $rowInvoice["Pickup"];
                        $tempTime = $rowInvoice["Create_Time"];
                        $Create_Time[$i] = date('jS\, F Y \| h:i A \(l\)', (strtotime($tempTime)+10800));
                        
                        $Customer_ID = $rowInvoice["Customer_ID"];
                        $rowCustomer = mysqli_fetch_assoc(runQuery("SELECT * FROM Customer WHERE Customer_ID='$Customer_ID'"));
                        $Customer_Name[$i] = $rowCustomer['Name'];
                        $Company_Name[$i] = $rowCustomer['Company_Name'];

                        $i++;
                    }
                    $i++;
                    mysqli_free_result($resultInvoiceB);
                }
            
            //Invoice - END

            //Production(Machine) - START
                $Production_ID = array();
                $P_Create_Time = array();
                $P_Update_Time = array();
                $Bags = array();
                $Boxes = array();
                $Toute = array();
                $Total = array();
                $Emp_Name = array();
                $Lot_Number = array();
                $Country_Name = array();
                $Bot_Name = array();
                
                $Location = array();
                
                $i = 0;
                foreach ($Barcode_ID as $Barcode) {
                    $query = "SELECT * FROM `Production_Machine` WHERE `Barcode_ID`='$Barcode'";
                    $resultProduction = runQuery($query);

                    while ($rowProduction = mysqli_fetch_array($resultProduction)) {
                        $Production_ID[$i] = $rowProduction["Production_ID"];
                        $P_Create_Time[$i] = $rowProduction["Create_Time"];
                        $P_Update_Time[$i] = $rowProduction["Update_Time"];
                        $Bags[$i] = (float)$rowProduction["Bags"];
                        $Boxes[$i] = (float)$rowProduction["Boxes"];
                        $Toute[$i] = (float)$rowProduction["Toute"];
                        $Total[$i] = $Bags[$i] * $Boxes[$i] + $Toute[$i];
                        $Emp_Name[$i] = $rowProduction["Emp_Name"];
                        $Lot_ID = $rowProduction["Lot_ID"];
                        $Lot_Number[$i] = mysqli_fetch_assoc(runQuery("SELECT * FROM Lot_Number WHERE Lot_ID='$Lot_ID'"))['Lot_Number'];
                        $Country_ID = $rowProduction["Country_ID"];
                        $Country_Name[$i] = mysqli_fetch_assoc(runQuery("SELECT * FROM Country WHERE Country_ID='$Country_ID'"))['Country_Name'];
                        $Bot_ID = $rowProduction["Bot_ID"];
                        $Bot_Name[$i] = mysqli_fetch_assoc(runQuery("SELECT * FROM Botanical_Name WHERE Bot_ID='$Bot_ID'"))['Bot_Name'];

                        $BeforeTime = date("Y-m-d H:i:s", strtotime($P_Create_Time[$i]) - 60);
                        $AfterTime = date("Y-m-d H:i:s", strtotime($P_Create_Time[$i]) + 60);
                        $tempResult = runQuery("SELECT * FROM B_Location_Barcode WHERE Barcode_ID='$Barcode' AND Update_Time BETWEEN '$BeforeTime' AND '$AfterTime'");
                        // print_r($tempResult);
                        //echo "SELECT * FROM B_Location_Barcode WHERE Barcode_ID='$Barcode' AND Update_Time BETWEEN '$BeforeTime' AND '$AfterTime'<br/>";
                        $tempRow = mysqli_fetch_array($tempResult);
                        $Location[$i] = $tempRow["Location_ID"];

                        $i++;
                    }
                    $i+=2;//This is to create an empty row in result set to separate 1lb and .25lb
                }
                if($i > 1){
                    $Production_Machine = array(
                        "Production_ID" => $Production_ID,
                        "P_Create_Time" => $P_Create_Time,
                        "P_Update_Time" => $P_Update_Time,
                        "Bags" => $Bags,
                        "Boxes" => $Boxes,
                        "Toute" => $Toute,
                        "Total" => $Total,
                        "Emp_Name" => $Emp_Name,
                        "Lot_Number" => $Lot_Number,
                        "Country_Name" => $Country_Name,
                        "Bot_Name" => $Bot_Name,
                        "Location" => $Location
                    );
                }
            //Production(Machine) - END
            
            //Production(Direct) - START
                $Production_ID = array();
                $D_Create_Time = array();
                $D_Update_Time = array();
                $Bags = array();
                $Lot_Number = array();
                $D_Invoice_Num = array();
                $Emp_Name = array();
                    
                $i = 0;
                foreach ($Barcode_ID as $Barcode) {
                    $query = "SELECT * FROM `Production_Direct` WHERE `Barcode_ID`='$Barcode'";
                    $resultProduction = runQuery($query);

                    while ($rowProduction = mysqli_fetch_array($resultProduction)) {
                        $Production_ID[$i] = $rowProduction["Production_ID"];
                        $D_Create_Time[$i] = $rowProduction["Create_Time"];
                        $D_Update_Time[$i] = $rowProduction["Update_Time"];
                        $Bags[$i] = (float)$rowProduction["Bags"];
                        $Emp_Name[$i] = $rowProduction["Emp_Name"];
                        $Lot_ID = $rowProduction["Lot_ID"];
                        $Lot_Number[$i] = mysqli_fetch_assoc(runQuery("SELECT * FROM Lot_Number WHERE Lot_ID='$Lot_ID'"))['Lot_Number'];
                        $D_Invoice_Num[$i] = $rowProduction["Invoice_Num"];
                        $i++;
                    }
                    $i+=2;
                }
                if($i != 0){
                    $Production_Direct = array(
                        "Production_ID" => $Production_ID,
                        "D_Create_Time" => $D_Create_Time,
                        "D_Update_Time" => $D_Update_Time,
                        "Bags" => $Bags,
                        "Emp_Name" => $Emp_Name,
                        "Lot_Number" => $Lot_Number,
                        "D_Invoice_Num" => $D_Invoice_Num
                    );
                }
            //Production(Direct) - END

            //Production(Convert) - START
                $Production_ID = array();
                $C_Create_Time = array();
                $C_Update_Time = array();
                $Lot_Number = array();
                $Bags_Cut = array();
                $Bags_Made = array();
                $Conversion = array();
                $Emp_Name = array();
                $Conversion = array();
                
                $i = 0;
                foreach ($Barcode_ID as $Barcode) {
                    $query = "SELECT * FROM `Production_Convert` WHERE `Barcode_ID`='$Barcode'";
                    $resultProduction = runQuery($query);

                    while ($rowProduction = mysqli_fetch_array($resultProduction)) {
                        $Production_ID[$i] = $rowProduction["Production_ID"];
                        $C_Create_Time[$i] = $rowProduction["Create_Time"];
                        $C_Update_Time[$i] = $rowProduction["Update_Time"];
                        $Bags_Cut[$i] = (float)$rowProduction["Bags_Cut"];
                        $Bags_Made[$i] = (float)$rowProduction["Bags_Made"];
                        $Emp_Name[$i] = $rowProduction["Emp_Name"];
                        $Lot_ID = $rowProduction["Lot_ID"];
                        $Lot_Number[$i] = mysqli_fetch_assoc(runQuery("SELECT * FROM Lot_Number WHERE Lot_ID='$Lot_ID'"))['Lot_Number'];
                        if($rowProduction["Conversion"] == 1) $Conversion[$i] = "114g to 1lb"; else $Conversion[$i] = "1lb to 114g";
                        $i++;
                    }
                    $i+=2;
                }
                if($i != 0){
                    $Production_Convert = array(
                        "Production_ID" => $Production_ID,
                        "C_Create_Time" => $C_Create_Time,
                        "C_Update_Time" => $C_Update_Time,
                        "Bags_Cut" => $Bags_Cut,
                        "Bags_Made" => $Bags_Made,
                        "Emp_Name" => $Emp_Name,
                        "Lot_Number" => $Lot_Number,
                        "Conversion" => $Conversion
                    );
                }
            //Production(Convert) - END
        
            //Warehouse - START
                $Warehouse_Stock = array();
                $query = "SELECT Weight, Processed, Garbage FROM B_Warehouse_Product WHERE Product_ID='$Product_ID'";
                $result = runQuery($query);
                $i = 0;
                $Warehouse_Stock[0] = 0; //There can only be two Warehouse stocks and both 1lb and 0.25lb has same stock we just need 1 so there is [0]
                while ($result->num_rows > 0 && $row = mysqli_fetch_assoc($result)) {
                    $Warehouse_Stock[0] += ((float) $row["Weight"] - (float) $row["Processed"] - (float) $row["Garbage"]);
                }
                mysqli_free_result($result);

                if($Warehouse_Stock[0] == 0){

                }
            //Warehouse - END
            
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

            array_push($DATA, $Invoice_ID);                     //Invoice_ID - 13
            array_push($DATA, $Invoice_Num);                    //Invoice_Num - 14
            array_push($DATA, $Total_Count);                    //Total_Count - 15
            array_push($DATA, $Pickup);                         //Pickup - 16
            array_push($DATA, $Create_Time);                    //Create_Time - 17

            array_push($DATA, $Product_ID=array($Product_ID));  //Product_ID - 18
            array_push($DATA, $Name);                           //Name - 19
            array_push($DATA, $Item_Code);                      //Item_Code - 20
            array_push($DATA, $Tax_Code);                       //Tax_Code - 21
            array_push($DATA, $Zone);                           //Zone - 22

            array_push($DATA, $Warehouse_Stock);                //Warehouse_Stock - 23
            array_push($DATA, $Ratio_Of_1lb_To_114g);           //Ratio_Of_1lb_To_114g - 24
            

            $json_OBJ = json_encode($DATA);
            $json_OBJ = addslashes($json_OBJ);
        }
    }
?>

<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report | TTParikh</title>

    <!-- CSS File Link -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/iconStyle.css">

    <!-- JavaScript And Jquery Link -->
    <script src="js/allJavaScripts.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <!-- <script> jQuery(function(){$("#search_id").autocomplete("php/productReportSearchAutocomplete.php");}); </script> -->

    <style>
        .item1 {
            grid-column: 1 / 9;
            grid-row: 1 / 3;
            display: flex;
            justify-content: space-between;
        }
        .item1 .page-title {
            color: var(--green-theme);
            font-family: 'Oswald', sans-serif;
            font-size: 25px;
            font-weight: 200;
            transition: ease 0.3s;
            margin-left: 30px;
        }
        .item1 .inside input{
            font-size: 20px;
            height: 40px;
        }
        .item1 input[type=button]{
            line-height: 4.9px;
            margin-left: -1.7px;
        }
        .item2 {
            grid-column: 1 / 5;
            grid-row: 7 / 9;
        }
        .top-info, date-range{
            display:flex;
            justify-content: space-between;
        }
        .date-range input[type=date]{
            width: auto;
            /* background-color: var(--purple-theme); */
            background-image: url('images/Untitled_design.png');
            border: 1px solid var(--green-theme);
            font-size: 14px;
            line-height: 17px;
        }
        .date-range input[type=button]{
            width: auto;
            line-height: 20px;
            margin-left: 10px;
        }
        .date-range label{
            margin: 10px;
        }
        .item2 table{
            width: 100%;
            text-align: center;
            margin-top: 20px;
            font-size: 20px;
        }
        .item2 td{
            padding: 20px;
            border-radius: 5px;
        }
        .item2 th{
            background-color: var(--green-theme);
            color: var(--purple-theme);
            padding: 15px;
            border-radius: 5px;
        }
        .item2 a{
            text-decoration: underline;
        }
        .item3 {
            grid-column: 5 / 9;
            grid-row: 7 / 9;
        }
        .item3 .invoice-details{
            width: 100%;
            display:none;
        }
        .item3 table{
            width: 100%;
            font-size: 20px;
        }
        .item3 .details table{
            text-align: center;
            margin-left: -30px;
        }
        .item3 .details table *{
            padding: 15px;
        }
        .item3 .contact-info td:nth-child(1), .item3 .contact-info td:nth-child(2){
            padding: 15px;
        }
        .item3 .invoice-details div{
            padding: 20px;
        }
        .item3 .details{
            border: 1px dashed var(--green-theme);
        }

        .item4 {
            grid-column: 1 / 9;
            grid-row: 3 / 5;
        }
        .item4 .inside{
            display: flex;
            justify-content: space-between;
        }
        .item4 .details, .item6 .details{
            display: flex;
            justify-content: space-between;
            border: 1px dashed var(--green-theme);
        }
        .item4 table, .item6 table{
            margin: auto 20px;
            font-size: 20px;
        }
        .item4 td, .item6 td{
            padding: 15px;
            font-size: 20px;
            text-align: center;
        }

        .item5 {
            grid-column: 1 / 9;
            padding: 20px;
        }
        .item5 table{
            width: 100%;
            text-align: center;
            margin-top: 20px;
            font-size: 20px;
        }
        .item5 td{
            padding: 20px;
        }
        .item5 th{
            background-color: var(--green-theme);
            color: var(--purple-theme);
            padding: 15px;
            border-radius: 5px;
        }

        .item6 {
            grid-column: 1 / 9;
            grid-row: 5 / 7;
            text-align: left;
        }
        .item6 td, .item4 td{
            text-align: left;
        }

        .inside{
            padding-bottom: 10px;
        }

        #suggestion{
            position:absolute;
            background-color:var(--purple-theme);
        }
        #suggestion li{
            text-decoration:none;
            cursor: pointer;
            padding: 5px;
            margin: -2px;
        }
        #suggestion li:hover{
            background-color: var(--green-theme);
            color: var(--purple-theme);
        }
    </style>
</head>
<body>
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
            <a href='productReport.php'><div class="menu drop-content highlight"><span class="icon-gift"></span><span>Product Report</span></div></a>
            <a href='customerReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Customer Report</span></div></a>
            <a href='trackingReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Tracking Report</span></div></a>
            <a href='warehouseReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Warehouse Report</span></div></a>
        </div>
        <a href='production.php'><div class="menu"><span class="icon-gift"></span><span>Production</span></div></a>
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
                    <span>Product Report : <?=$DATA[19][0]?></span>
                </div>
                <div class="inside">
                    <form id="myForm" action="" method="get" enctype = "multipart/form-data">
                        <table>
                            <tr><td><input type="text" size="70" name="search" autocomplete="off" id="search_id" onkeyup="searchResultSettings(this.value)"></td><td><input type="submit" value="Search"></td></tr>
                            <tr><td><div id="suggestion" class="suggestion"></div></td><td></td></tr>
                        </table>
                    </form>
                </div>
            </div>

            <div class="grid item4">
                <div class="inside">
                    <span>Warehouse Details (1 LB) : For Each 114g you need to have <?=$DATA[24]?> 1lb</span>
                    <span id="rationCalculator"><label>Number of Pound: </label><input type="text" size='10' onchange="ratioCalculator(this.value, '<?=$DATA[24]?>')"/></span>
                </div>
                <div class="details">
                    <div class="iitem1">
                        <table>
                            <!-- <img src="https://generator.barcodetools.com/barcode.png?gen=0&data=772696110713&bcolor=091428&fcolor=46B692&tcolor=46B692&fh=14&bred=0&w2n=2.5&xdim=2&w=&h=50&debug=1&btype=7&angle=0&quiet=1&balign=2&talign=3&guarg=1&text=0&tdown=1&stst=1&schk=0&cchk=1&ntxt=1&c128=0" alt="barcode" srcset=""> -->
                            <tr>
                                <td><label for="">Barcode :</label></td><td><label for=""><?=$DATA[0][0]?></label></td>
                            </tr>
                            <tr>
                                <td><label for="">Item Code :</label></td><td><label for=""><?=$DATA[20][0]?></label></td>
                            </tr>
                        </table>
                    </div>
                    <div class="iitem2">
                        <table>
                            <tr>
                                <td><label for="">Locations :</label></td><td><label for=""><?=$DATA[8][0]?></label></td>
                            </tr>
                            <tr>
                                <td><label for="">Zone :</label></td><td><label for=""><?=$DATA[22][0]?></label></td>
                            </tr>
                        </table>
                    </div>
                    <div class="iitem3">
                        <table>
                            <tr>
                                <td><label for="">Yearly Avg :</label></td><td><label for=""><?=$DATA[6][0]?></label></td>
                            </tr>
                            <tr>
                                <td><label for="">In Stock :</label></td><td><label for=""><?php echo ($DATA[3][0] - $DATA[4][0] + $DATA[5][0]); ?></label></td>
                            </tr>
                        </table>
                    </div>
                    <div class="iitem4">
                        <table>
                            <tr>
                                <td><label for="">Total Sold :</label></td><td><label for=""><?=$DATA[4][0]?></label></td>
                            </tr>
                            <tr>
                                <td><label for="">Warehouse Stock :</label></td><td><label for=""><?=$DATA[23][0]?></label></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="grid item6">
                <div class="inside"><span>Warehouse Details (114g) :</span></div>
                <div class="details">
                    <div class="iitem1">
                        <table><!-- <img src="https://generator.barcodetools.com/barcode.png?gen=0&data=772696110713&bcolor=091428&fcolor=46B692&tcolor=46B692&fh=14&bred=0&w2n=2.5&xdim=2&w=&h=50&debug=1&btype=7&angle=0&quiet=1&balign=2&talign=3&guarg=1&text=0&tdown=1&stst=1&schk=0&cchk=1&ntxt=1&c128=0" alt="barcode" srcset=""> -->
                            <tr>
                                <td><label for="">Barcode :</label></td><td><label for=""><?=$DATA[0][1]?></label></td>
                            </tr>
                            <tr>
                                <td><label for="">Item Code :</label></td><td><label for=""><?=$DATA[20][0]?>A</label></td>
                            </tr>
                        </table>
                    </div>
                    <div class="iitem2">
                        <table>
                            <tr>
                                <td><label for="">Locations :</label></td><td><label for=""><?=$DATA[8][1]?></label></td>
                            </tr>
                            <tr>
                                <td><label for="">Zone :</label></td><td><label for=""><?=$DATA[22][0]?></label></td>
                            </tr>
                        </table>
                    </div>
                    <div class="iitem3">
                        <table>
                            <tr>
                                <td><label for="">Yearly Avg :</label></td><td><label for=""><?=$DATA[6][1]?></label></td>
                            </tr>
                            <tr>
                                <td><label for="">In Stock :</label></td><td><label for=""><?php echo ($DATA[3][1] - $DATA[4][1] + $DATA[5][1]); ?></label></td>
                            </tr>
                        </table>
                    </div>
                    <div class="iitem4">
                        <table>
                            <tr>
                                <td><label for="">Total Sold :</label></td><td><label for=""><?=$DATA[4][1]?></label></td>
                            </tr>
                            <tr>
                                <td><label for="">Warehouse Stock :</label></td><td><label for=""><?=$DATA[23][0]?></label></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="grid item2">
                <div class="top-info">
                    <div class="inside"><span>Invoice Details :</span></div>
                    <div class="date-range">
                        <input type="date" id="StartDate_Invoice"><label>-</label><input type="date" id="EndDate_Invoice"><input type="button" value="Sort" id="Sort_Invoice">
                    </div>
                </div>
                <div class="info">
                    <table id="all_invoice_entry_ID">
                        <tr>
                            <th>Invoice Number</th><th>Count</th><th>Date</th>
                        </tr>
                        <?php 
                            $str = '';
                            for ($i=0; $i < count($DATA[13]); $i++) { 
                                if($DATA[13][$i] == null){
                                    $str .= "<tr style='background-color: var(--green-theme);'>";
                                }
                                else{
                                    $str .= "<tr>";
                                }
                                $str .= "<td><a onclick='invoiceInfo(\"".$DATA[13][$i]."\", \"".$DATA[14][$i]."\", \"".$DATA[15][$i]."\", \"".$DATA[9][$i]."\", \"".$DATA[10][$i]."\")'><label for=''>".$DATA[14][$i]."</label></a></td><td>".$DATA[15][$i]."</td><td><label for=''>".$DATA[17][$i]."</label></td>";
                                $str .= "</tr>";
                            }
                            echo $str;
                        ?>
                        <input type="hidden" id="invoiceHolder" value="<?$DATA[13][--$i]?>">
                    </table>
                </div>
                    <div class="top-info">
                        <div class="date-range">
                        <label onclick='loadMoreInvoice(false)'><--</label>
                        </div>
                        <div class="date-range">
                            <label onclick='loadMoreInvoice(true)'> --></label>
                        </div>
                    </div>
            </div>

            <div class="grid item3">
                <div class="inside"><span>Specific Details :</span></div>
                <div class="invoice-details" id="invoice_content_div">
                    <div class="contact-info">
                        <table id="invoice_info_table"></table>
                    </div>
                    <div class="details">
                        <table id='invoice_details_table'></table>
                    </div>
                </div>
            </div>
            
            <div class="grid item5">
                <div class="top-info">
                    <div class="inside"><span>Production Details :</span></div>
                    <div class="date-range">
                        <input type="date"><label for="">-</label><input type="date"><input type="button" value="Sort">
                    </div>
                </div>
                <div class="info">
                    <?if(count($Production_Machine['Production_ID']) > 0){?>
                    <label>Machine Production</label>
                    <table id="Machine">
                        <tr>
                            <th>Date</th><th>No. Bags</th>
                            <th>No. Boxes</th><th>In Toute</th>
                            <th>Total Packages</th><th>Lot No.</th>
                            <th>Location</th><th>Name</th>
                            <th>Country</th><th>Botanical Name</th>
                        </tr>
                        <?for($i = 0; $i < count($Production_Machine['Production_ID']); $i++){?>
                        <tr>
                            <td><?=$Production_Machine["P_Create_Time"][$i]?></td><td><?=$Production_Machine["Bags"][$i]?></td>
                            <td><?=$Production_Machine["Boxes"][$i]?></td><td><?=$Production_Machine["Toute"][$i]?></td>
                            <td><?=$Production_Machine["Total"][$i]?></td><td><?=$Production_Machine["Lot_Number"][$i]?></td>
                            <td><?=$Production_Machine["Location"][$i]?></td><td><?=$Production_Machine["Emp_Name"][$i]?></td>
                            <td><?=$Production_Machine["Country_Name"][$i]?></td><td><?=$Production_Machine["Bot_Name"][$i]?></td>
                        </tr>
                        <?}?>
                    </table>
                    <?}
                    if(count($Production_Direct['Production_ID']) > 0){?>
                    <label>Direct Production</label>
                    <table id="Direct">
                        <tr>
                            <th>Date</th><th>No. Bags</th><th>Lot No.</th><th>Invoice Num</th><th>Name</th>
                        </tr>
                        <?for($i = 0; $i < count($Production_Direct['Production_ID']); $i++){?>
                        <tr>
                            <td><?=$Production_Direct["D_Create_Time"][$i]?></td><td><?=$Production_Direct["Bags"][$i]?></td>
                            <td><?=$Production_Direct["Lot_Number"][$i]?></td><td><?=$Production_Direct["D_Invoice_Num"][$i]?></td>
                            <td><?=$Production_Direct["Emp_Name"][$i]?></td>
                        </tr>
                        <?}?>
                    </table>
                    <?}
                    if(count($Production_Convert['Production_ID']) > 0){?>
                    <label>Conversion Production</label>
                    <table id="Convert">
                        <tr>
                            <th>Date</th><th>Variation</th><th>Bags Cut</th><th>Bags Made</th><th>Lot No.</th><th>Name</th>
                        </tr>
                        <?for($i = 0; $i < count($Production_Convert['Production_ID']); $i++){?>
                        <tr>
                            <td><?=$Production_Convert["C_Create_Time"][$i]?></td><td><?=$Production_Convert["Conversion"][$i]?></td>
                            <td><?=$Production_Convert["Bags_Cut"][$i]?></td><td><?=$Production_Convert["Bags_Made"][$i]?></td>
                            <td><?=$Production_Convert["Lot_Number"][$i]?></td><td><?=$Production_Convert["Emp_Name"][$i]?></td>
                        </tr>
                        <?}?>
                    </table>
                    <?}?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        window.onload = function(){
            feather.replace();
            document.getElementById("search_id").focus();
            document.getElementById("invoiceStatus_go").addEventListener("click", submitInvoiceStatus, false);
            document.getElementById("Sort_Invoice").addEventListener("click", sortInvoice, false);
        };
        
        function changeBarcodeValue(str1, str2, name, zone) {
            document.getElementById("search_id").value = name;

            removeSuggestion();

            document.getElementById("myForm").submit();
        }

        function removeSuggestion() {
            if(!$("#product_name_1").is(":focus")){
                var temp = document.getElementById("suggestion");
                temp.innerHTML="";
                temp.style.padding="0px";
                temp.style.border="none";
            }
        }
        
        function loadMoreInvoice(checkNext) {
            if(checkNext){
                return;
            }
        }
        
        function ratioCalculator(str, value) {
            var to_show = str * (Math.pow(value, -1)) + 4;
            document.getElementById('rationCalculator').innerHTML = '<span>For ' + str + 'lb you should make ' + Math.ceil(to_show) + ' quater pound bags.</span>';
        }

        function sortInvoice(event) {
            var Start = document.getElementById("StartDate_Invoice");
            var End = document.getElementById("EndDate_Invoice");
            var Barcode1 = '<?=$Barcode_ID[0]?>';
            var Barcode2 = '<?=$Barcode_ID[1]?>';
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    var result = this.responseText;

                    var to_show = '';
                    // for (let i = 0; i < result.length; i++) {
                    //     const element = result[i];
                    //     to_show += "";
                    //     if($DATA[13][$i] == null){
                    //         $str .= "<tr style='background-color: var(--green-theme);'>";
                    //     }
                    //     else{
                    //         $str .= "<tr>";
                    //     }
                    //     $str .= "<td><a onclick='invoiceInfo(\"".$DATA[13][$i]."\", \"".$DATA[14][$i]."\", \"".$DATA[15][$i]."\", \"".$DATA[9][$i]."\", \"".$DATA[10][$i]."\")'><label for=''>".$DATA[14][$i]."</label></a></td><td>".$DATA[15][$i]."</td><td><label for=''>".$DATA[17][$i]."</label></td>";
                    //     $str .= "</tr>";
                    // }
                    // var x = document.createElement("DIV");
                    // x.setAttribute("id", "sidebar_Id");
                    // x.setAttribute("class", "show");
                    // x.innerHTML = result;
                    // document.getElementById("root_Id").appendChild(x);
                    // setTimeout(function(){ x.className = x.className.replace("show", ""); }, 10000);
                    document.getElementById("all_invoice_entry_ID").innerHTML = to_show;
                }
            };
            xhttp.open("GET", "php/sortInvoice_Ajax.php?Start="+Start+"&End="+End+'&Barcode1='+Barcode1+'&Barcode2='+Barcode2, true);
            xhttp.send();
        }
    </script>
</body>
</html>