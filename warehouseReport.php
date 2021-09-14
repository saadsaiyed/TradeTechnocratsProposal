<?php
    include "php/DBConnection.php";
    checkIfLoggedIn(2, 3, 4, 1);
    
    $search = $_GET["search"];

    if($search){
        $query = "SELECT * FROM Product WHERE Name='$search'";
        $result = runQuery($query);
        $Product_ID = mysqli_fetch_assoc($result)["Product_ID"];

        $query = "SELECT * FROM B_Warehouse_Product WHERE Product_ID='$Product_ID'";
        $result = runQuery($query);
        while ($row = mysqli_fetch_assoc($result)) {
            $Stock_ID = $row["Stock_ID"];
            $query = "SELECT * FROM Warehouse WHERE Stock_ID='$Stock_ID'";
            $result = runQuery($query);
            $Vendor_ID = mysqli_fetch_assoc($result)["Vendor_ID"];
        }
           
        $query = "SELECT * FROM Product WHERE Name='$search'";
        $result = runQuery($query);
        $Product_ID = mysqli_fetch_assoc($result)["Product_ID"];

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
            
            //Invoice - END

            //Production - START
            //Production - END

            //Warehouse - START
                $Warehouse_Stock = array();
                $query = "SELECT Weight FROM B_Warehouse_Product WHERE Product_ID='$Product_ID'";
                $result = runQuery($query);
                $i = 0;
                $Warehouse_Stock[0] = 0; //There can only be two Warehouse stocks and both 1lb and 0.25lb has same stock we just need 1 so there is [0]
                while ($result->num_rows > 0 && $row = mysqli_fetch_assoc($result)) {
                    $Warehouse_Stock[0] += (float) $row["Weight"];
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

            array_push($DATA, $Invoice_Num);                    //Invoice_Num - 13
            array_push($DATA, $Total_Count);                    //Total_Count - 14
            array_push($DATA, $Pickup);                         //Pickup - 15
            array_push($DATA, $Create_Time);                    //Create_Time - 16

            array_push($DATA, $Product_ID=array($Product_ID));  //Product_ID - 17
            array_push($DATA, $Name);                           //Name - 18
            array_push($DATA, $Item_Code);                      //Item_Code - 19
            array_push($DATA, $Tax_Code);                       //Tax_Code - 20
            array_push($DATA, $Zone);                           //Zone - 21

            array_push($DATA, $Warehouse_Stock);                //Warehouse_Stock - 22

            // $json_OBJ = json_encode($DATA);
        }
    }
    else{
        $DATA = array();
        $Invoice_Num = array();
        $Invoice_Link = array();
        $Vendor_Name = array();
        $Arrival_Date = array();
        $Create_Time = array();
        $Invoice_Details = array();

        $i=0;
        $result = runQuery("SELECT * FROM Warehouse ORDER BY Arrival_Date DESC");
        while ($row = mysqli_fetch_assoc($result)) {
            $Stock_ID = $row["Stock_ID"];
            $Vendor_ID = $row["Vendor_ID"];
            $Invoice_Num[$i] = $row["Invoice_Num"];
            $Invoice_Link[$i] = $row["Invoice_Link"];
            $Arrival_Date[$i] = $row["Arrival_Date"];
            $Create_Time[$i] = $row["Create_Time"];

            $Vendor_Name[$i] = mysqli_fetch_assoc(runQuery("SELECT * FROM Vendor WHERE Vendor_ID='$Vendor_ID'"))["Name"];

            //B_Warehouse_Product - START
                $Invoice_Details[$i] = array();
                $Product_Name = array();
                $Lot_Number = array();
                $Bot_Name = array();
                $Country_Name = array();
                $Count = array();
                $Weight = array();
                $Processed = array();
                $j = 0;
                $B_result = runQuery("SELECT * FROM B_Warehouse_Product AS B
                                    INNER JOIN Product AS P 
                                    ON B.Product_ID = P.Product_ID
                                    WHERE B.Stock_ID='$Stock_ID'");
                while ($B_row = mysqli_fetch_assoc($B_result)) {
                    $Lot_Number[$j] = null;
                    $Country_Name[$j] = null;
                    $Bot_Name[$j] = null;

                    $Lot_ID = $B_row["Lot_ID"];
                    $Country_ID = $B_row["Country_ID"];
                    $Bot_ID = $B_row["Bot_ID"];
                    
                    if($Lot_ID)
                        $Lot_Number[$j] = mysqli_fetch_assoc(runQuery("SELECT Lot_Number FROM Lot_Number WHERE Lot_ID = '$Lot_ID'"))["Lot_Number"];
                    if($Country_ID)
                        $Country_Name[$j] = mysqli_fetch_assoc(runQuery("SELECT Country_Name FROM Country WHERE Country_ID = '$Country_ID'"))["Country_Name"];
                    if($Bot_ID)
                        $Bot_Name[$j] = mysqli_fetch_assoc(runQuery("SELECT Bot_Name FROM Botanical_Name WHERE Bot_ID = '$Bot_ID'"))["Bot_Name"];
                    
                    $Product_Name[$j] = $B_row["Name"]; 
                    $Count[$j] = $B_row["Count"];
                    $Weight[$j] = $B_row["Weight"];
                    $Processed[$j] = $B_row["Processed"];
                    $j++;
                }

                array_push($Invoice_Details[$i], $Product_Name);                //Product_Name - 0
                array_push($Invoice_Details[$i], $Count);                       //Count - 1
                array_push($Invoice_Details[$i], $Weight);                      //Weight - 2
                array_push($Invoice_Details[$i], $Processed);                   //Processed - 3
                array_push($Invoice_Details[$i], $Lot_Number);                  //Lot_Number - 4
                array_push($Invoice_Details[$i], $Bot_Name);                    //Bot_Name - 5
                array_push($Invoice_Details[$i], $Country_Name);                //Country_Name - 6
            //B_Warehouse_Product - END

            $i++;
        }

        array_push($DATA, $Invoice_Num);                    //Invoice_Num - 0
        array_push($DATA, $Invoice_Link);                   //Invoice_Link - 1
        array_push($DATA, $Vendor_Name);                    //Vendor_Name - 2
        array_push($DATA, $Arrival_Date);                   //Arrival_Date - 3
        array_push($DATA, $Create_Time);                    //Create_Time - 4
        array_push($DATA, $Invoice_Details);                //Invoice_Details - 5
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
            grid-row: 3 / 9;
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
        .item2 .info table tr:hover {
            background-color: var(--green-theme);
        }
        .item2 td{
            padding: 20px;
            cursor: pointer;
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
            grid-row: 3 / 9;
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
            <a href='productReport.php'><div class="menu drop-content "><span class="icon-gift"></span><span>Product Report</span></div></a>
            <a href='customerReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Customer Report</span></div></a>
            <a href='trackingReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Tracking Report</span></div></a>
            <a href='warehouseReport.php'><div class="menu drop-content highlight"><span class="icon-gift"></span><span>Warehouse Report</span></div></a>
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
                    <span>Warehouse Report :</span>
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

            <div class="grid item2">
                <div class="top-info">
                    <div class="inside"><span>Invoice Details :</span></div>
                    <div class="date-range">
                        <input type="date" name="" id=""><label for="">-</label><input type="date" name="" id=""><input type="button" value="Sort">
                    </div>
                </div>
                <div class="info">
                    <table>
                        <tr>
                            <th>Invoice Number</th><th>Vendor Name</th><th>Arrival Time</th><th>Date</th>
                        </tr>
                        <?php 
                            $str = '';
                            for ($i=0; $i < count($DATA[0]); $i++) { 
                                    $jsonString = $DATA[5];
                                    $str .= "<tr>";
                                    $str .= "<td onclick='invoiceInfo(\"".$DATA[2][$i]."\", \"".$DATA[0][$i]."\", \"$i\", \"".$DATA[1][$i]."\")'><label for=''>".$DATA[0][$i]."</label></td><td onclick='invoiceInfo(\"".$DATA[2][$i]."\", \"".$DATA[0][$i]."\", \"$i\", \"".$DATA[1][$i]."\")'>".$DATA[2][$i]."</td><td onclick='invoiceInfo(\"".$DATA[2][$i]."\", \"".$DATA[0][$i]."\", \"$i\", \"".$DATA[1][$i]."\")'>".$DATA[3][$i]."</td><td onclick='invoiceInfo(\"".$DATA[2][$i]."\", \"".$DATA[0][$i]."\", \"$i\", \"".$DATA[1][$i]."\")'>".$DATA[4][$i]. "</label></td>";
                                    $str .= "</tr>";
                            }
                            echo $str;
                        ?>
                    </table>
                </div>
            </div>

            <div class="grid item3">
                <div class="inside"><span>Specific Details :</span></div>
                <div class="invoice-details" id="invoice-details-id">
                    <div class="contact-info">
                        <table>
                            <tr>
                                <td><label>Vendor Name:</label></td><td><label id="Vendor_Name">Andrew Kingston</label></td>
                            </tr>
                            <tr>
                                <td><label>Invoice Number:</label></td><td><label id="Invoice_Num">95010</label></td>
                                <td style="float:right"><a href="" target="_blank" id="Invoice_Link"><input type="button" value="Look Up"></a></td>
                            </tr>
                        </table>
                    </div>
                    <div class="details">
                        <table id="invoice_details_table"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        window.onload = function() {
            feather.replace();
            document.getElementById("invoiceStatus_go").addEventListener("click", submitInvoiceStatus, false);
        }
        
        function changeBarcodeValue(str1, str2, name, zone) {
            document.getElementById("search_id").value = name;

            removeSuggestion();

            document.getElementById("myForm").submit();
        }
        function removeSuggestion() {
            if(!$("#product_name_1").is(":focus")){
                document.getElementById("suggestion").innerHTML="";
                document.getElementById("suggestion").style.padding="0px";
                document.getElementById("suggestion").style.border="none";
            }
        }
        function invoiceInfo(vendorName, invoiceNum, id, link) {
            var x = document.getElementById("invoice-details-id");

            if (x.style.display === "block" && document.getElementById("Invoice_Num").innerHTML == invoiceNum)
                x.style.display = "none";
            else{
                x.style.display = "block";

                document.getElementById("Vendor_Name").innerHTML = vendorName;
                document.getElementById("Invoice_Num").innerHTML = invoiceNum;
                document.getElementById("Invoice_Link").href = link;

                var json_result = '<?php echo json_encode($jsonString); ?>';
                var invoiceDetails = JSON.parse(json_result);
                var to_show = "<tr><th>Product Name</th><th>Weight</th><th>Stock</th><th>Lot</th><th>Bot</th><th>Country</th></tr>";
                for (let i = 0; i < invoiceDetails[id][0].length; i++) {
                    const element = invoiceDetails[id];
                    var temp1 = parseFloat(element[2][i]);
                    var temp2 = parseFloat(element[3][i]);
                    var stock = temp1 - temp2;
                    to_show += "<tr><td><label>" + element[0][i] + "</label></td><td><label>" + element[2][i] + "</label></td><td><label>" + stock + "</label></td>";
                    to_show += "<td><label>" + element[4][i] + "</label></td><td><label>" + element[5][i] + "</label></td><td><label>" + element[6][i] + "</label></td></tr>";
                }
                document.getElementById("invoice_details_table").innerHTML = to_show;
            }
        }

    </script>
</body>
</html>