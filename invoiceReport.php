<?php
    include "php/DBConnection.php";
    checkIfLoggedIn(1, 2, 3, 4);

    if(isset($_POST["search"]) || $_GET["search"]){
        if($_GET["search"]) $search = $_GET["search"];
        else $search = $_POST["search"];

        $DATA = array();
    
        $query = "SELECT * FROM Invoice WHERE Invoice_Num='$search'";
        $result = runQuery($query);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $Customer_ID = $row["Customer_ID"];
                $tempRow = mysqli_fetch_assoc(runQuery("SELECT * FROM Customer WHERE Customer_ID='$Customer_ID'"));
                $DATA[0] = $tempRow["Name"] . " - " . $tempRow["Company_Name"];
                $DATA[1] = $row["Invoice_Num"];
                $DATA[2] = $row["Total_Count"];
                $DATA[3] = date('jS\, F Y \| h:i A \(l\)', (strtotime($row["Create_Time"])+10800));

                $DATA[4] = array();
                $Count = array();
                $Barcode_ID = array();
                $Product_Name = array();
                $Zone = array();
                $Invoice_ID = $row["Invoice_ID"];
                $InvoiceResult = runQuery("SELECT * FROM B_Invoice_Barcode WHERE Invoice_ID='$Invoice_ID'");
                while($InvoiceRow = $InvoiceResult->fetch_assoc()){
                    $temp_Count = $InvoiceRow['Count'];
                    $temp_Barcode_ID = $InvoiceRow['Barcode_ID'];
                    $BarcodeRow = runQuery("SELECT * FROM Barcode WHERE Barcode_ID='$temp_Barcode_ID'")->fetch_assoc();
                    $Product_ID = $BarcodeRow['Product_ID'];
                    $Type = $BarcodeRow['Type'];
                    $ProductRow = runQuery("SELECT * FROM Product WHERE Product_ID='$Product_ID'")->fetch_assoc();
                    $temp_Product_Name = $ProductRow['Name'];
                    if($Type == 'B') $temp_Product_Name .= ' - 114g';
                    $temp_Zone = $ProductRow['Zone'];
                    array_push($Barcode_ID, $temp_Barcode_ID);
                    array_push($Product_Name, $temp_Product_Name);
                    array_push($Count, $temp_Count);
                    array_push($Zone, $temp_Zone);
                }

                array_multisort($Product_Name, $Barcode_ID, $Count, $Zone);
                            
                array_push($DATA[4], $Barcode_ID);
                array_push($DATA[4], $Product_Name);
                array_push($DATA[4], $Count);
                array_push($DATA[4], $Zone);
                $DATA[5] = $row["Invoice_ID"];
            }
        }
    }
    //LoadRecentInvoice - START
    $RecentInvoiceList = array();
        
    $date = new DateTime();
    $date->sub(new DateInterval('P2D'));
    $date = $date->format('Y-m-d') . " 00:00:01";
    $query = "SELECT * FROM Invoice WHERE Create_Time > '$date' ORDER BY Create_Time DESC";
    $result = runQuery($query);
    if($result->num_rows > 0){
        $Invoice_ID = array();
        $Invoice_Num = array();
        $Total_Count = array();
        $Customer_Name = array();
        $Company_Name = array();
        $Create_Time = array();
        
        $i = 0;
        while($row = mysqli_fetch_assoc($result)){
            $Invoice_ID[$i] = $row["Invoice_ID"];
            $Invoice_Num[$i] = $row["Invoice_Num"];
            $Total_Count[$i] = $row["Total_Count"];
            $Create_Time[$i] = date('jS\, F Y \| h:i A \(l\)', (strtotime($row["Create_Time"])+10800));
            $Customer_ID = $row['Customer_ID'];
            $tempCustomerRow = mysqli_fetch_assoc(runQuery("SELECT * FROM Customer WHERE Customer_ID = '$Customer_ID'"));
            $Customer_Name[$i] = addslashes($tempCustomerRow["Customer_Name"]);
            $Company_Name[$i] = addslashes($tempCustomerRow["Company_Name"]);

            $i++;
        }

        array_push($RecentInvoiceList, $Invoice_ID);
        array_push($RecentInvoiceList, $Invoice_Num);
        array_push($RecentInvoiceList, $Total_Count);
        array_push($RecentInvoiceList, $Customer_Name);
        array_push($RecentInvoiceList, $Company_Name);
        array_push($RecentInvoiceList, $Create_Time);
    }
    //LoadRecentInvoice - END
?>

<html lang="en" onclick="removeSuggestion()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Report | TTParikh</title>

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
            grid-column: 1 / 9;
            grid-row: 3 / 7;
        }
        .item2 .inside input[type=text]{
            width:200px;
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
            text-align: center;
            margin-top: 10px;
            font-size: 20px;
        }
        .item2 .details td{
            padding:10px;
        }
        .item2 th{
            background-color: var(--green-theme);
            color: var(--purple-theme);
            padding: 15px;
        }
        .item2 a{
            text-decoration: underline;
        }

            #text-area td:nth-child(1){
                width: 400px;
            }
            #text-area td:nth-child(2){
                width: 800px;
            }
            #text-area td:nth-child(3){
                width: 300px;
            }
            #text-area td:nth-child(4){
                width: 400px;
            }

            #text-area thead th:nth-child(1){
                width: 400px;
            }
            #text-area thead th:nth-child(2){
                width: 800px;
            }
            #text-area thead th:nth-child(3){
                width: 300px;
            }
            #text-area thead th:nth-child(4){
                width: 400px;
            }

            #text-area thead{
                display: block;
            }
            #text-area  tbody{
                height: auto;
                display: block;
                overflow: auto;
            }
            #text-area input{
                width: 100%
            }


        #text-area input{
            border:none;
        }
        #text-area{
            border: 1px solid var(--green-theme);
        }
        #text-area tr:nth-child(2n + 1){
            background:var(--lightpurple-theme)
        }
        #text-area td:nth-child(4){
            border-right: none;
        }
        #text-area td{
            border-right: 1px solid var(--green-theme);
        }

        .item2 .inside table *{
            padding-bottom: 20px;
        }

        .item3 {
            grid-column: 1 / 5;
            grid-row: 7 / 11;
        }
        .item3 .top-info, date-range{
            display:flex;
            justify-content: space-between;
        }
        .item3 .date-range input[type=date]{
            width: auto;
            background-color: var(--purple-theme);
            border: 1px solid var(--green-theme);
            font-size: 14px;
            line-height: 17px;
        }
        .item3 .date-range input[type=button]{
            width: auto;
            line-height: 22px;
        }
        .item3 .date-range label{
            margin: 10px;
        }
        .item3 table{
            width: 100%;
            text-align: center;
            margin-top: 20px;
            font-size: 20px;
        }
        .item3 td{
            padding: 20px;
        }
        .item3 th{
            background-color: var(--green-theme);
            color: var(--purple-theme);
            padding: 15px;
            border-radius: 5px;
        }
        .item3 a{
            text-decoration: underline;
        }
        .item3 .info tr:hover{
            color: var(--purple-theme);
            background-color: var(--green-theme);
            cursor: pointer;
        }
        
        .item4 {
            grid-column: 5 / 9;
            grid-row: 7 / 11;
        }
        .item4 .invoice-details{
            width: 100%;
            display:none;
        }
        .item4 table{
            width: 100%;
            font-size: 20px;
        }
        .item4 .details table{
            text-align: center;
            margin-left: -30px;
        }
        .item4 .details table *{
            padding: 15px;
        }
        .item4 .contact-info td:nth-child(1), .item4 .contact-info td:nth-child(2){
            padding: 15px;
        }
        .item4 .invoice-details div{
            padding: 20px;
        }
        .item4 .details{
            border: 1px dashed var(--green-theme);
        }


        #suggestion{
            position:absolute;
            background-color:var(--purple-theme);
        }
        .suggestion_i:hover{
            color:var(--purple-theme);
            background-color:var(--green-theme);
        }
        .suggestion_i{
            cursor: pointer;
            text-align:center;
            padding: 10px;
        }
        .suggestion_i label{
            padding: 10px 15px 10px 15px;
        }
        
        /* Auto Complete functionality */
        .autocomplete {
            position: relative;
            display: inline-block;
            width: 500px;
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
            <a href='invoiceReport.php'><div class="menu drop-content highlight"><span class="icon-gift"></span><span>Invoice Report</span></div></a>
            <a href='productReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Product Report</span></div></a>
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
                    <span>Invoice Report</span>
                </div>
                <div class="inside autocomplete">
                    <form id="myForm" method="post" enctype="multipart/form-data">
                        <input type="text" size="70" name="search" autocomplete="off" id="search_id" placeholder="Search Invoice">
                    </form>
                </div>
            </div>
            <?if(count($DATA)>0){?>
                <div class="grid item2" id="checkIfExists">
                    <div class="inside">
                        <a href="editInvoice.php?InvoiceID=<?=$DATA[5]?>"><input type="button" value="Edit" style="width:auto;"></a>
                        <table>
                            <tr>
                            <td><label>Invoice Name :</label></td>
                            <td><label><?=$DATA[0]?></label></td>
                            <td><label>Invoice Number :</label></td>
                            <td><label><?=$DATA[1]?></label></td>
                            <td><label>Total Quantity :</label></td>
                            <td><label><?=$DATA[2]?></label></td>
                            </tr>
                        </table>
                    </div>
                    <div class="details">
                        <table id="text-area">
                            <thead>
                                <tr>
                                <th><label>Barcode</label></th>
                                <th><label>Product Name</label></th>
                                <th><label>Count</label></th>
                                <th><label>Zone</label></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?for($i=0; $i < count($DATA[4][0]); $i++){?>
                                <tr>
                                <td><label><?=$DATA[4][0][$i]?></label></td>
                                <td><label><?=$DATA[4][1][$i]?></label></td>
                                <td><label><?=$DATA[4][2][$i]?></label></td>
                                <td><label><?=$DATA[4][3][$i]?></label></td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?}?>
            <div class="grid item3" id="moveItUp_1">
                <div class="top-info">
                    <div class="inside"><span>Invoice Details :</span></div>
                    <div class="date-range">
                        <input type="date" name="startDate" id="startDate"><label>-</label><input type="date" name="endDate" id="endDate"><input type="button" value="Sort">
                    </div>
                </div>
                <div class="info">
                    <table>
                        <tr>
                            <th>Invoice Number</th><th>Total Packages</th><th>Date</th>
                        </tr>
                        <?for ($i=0; $i < count($RecentInvoiceList[0]); $i++) { ?>
                            <tr onclick="invoiceInfo('<?=$RecentInvoiceList[0][$i]?>', '<?=$RecentInvoiceList[1][$i]?>', '<?=$RecentInvoiceList[2][$i]?>', '<?=$RecentInvoiceList[3][$i]?>', '<?=$RecentInvoiceList[4][$i]?>')">
                                <td><label><?=$RecentInvoiceList[1][$i]?></label></td>
                                <td><?=$RecentInvoiceList[2][$i]?></td>
                                <td><label><?=$RecentInvoiceList[5][$i]?></label></td>
                            </tr>
                        <?}?>
                    </table>
                </div>
            </div>

            <div class="grid item4" id="moveItUp_2">
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
            </div>
        </div>
    </div>
    
    <script>
        window.onload = function() {
            feather.replace();
            document.getElementById("invoiceStatus_go").addEventListener("click", submitInvoiceStatus, false);

            if(document.getElementById("checkIfExists") == null){
                var leftGrid = document.getElementById("moveItUp_1");
                var rightGrid = document.getElementById("moveItUp_2");

                leftGrid.style.gridColumnStart = '1';
                leftGrid.style.gridColumnEnd = '5';
                leftGrid.style.gridRowStart = '3';
                leftGrid.style.gridRowEnd = '7';
                rightGrid.style.gridColumnStart = '5';
                rightGrid.style.gridColumnEnd = '9';
                rightGrid.style.gridRowStart = '3';
                rightGrid.style.gridRowEnd = '7';
            }

            autocomplete(document.getElementById("search_id"));
        };

        function removeSuggestion() {
            if(!$("#search_id").is(":focus")){
                document.getElementById("suggestion").innerHTML="";
                document.getElementById("suggestion").style.padding="0px";
                document.getElementById("suggestion").style.border="none";
            }
        }
        function submitForm(id){
            document.getElementById('search_id').value = id;

            var el = document.getElementById('myForm');
            var etype = 'click';
            if (el.fireEvent) {
                el.fireEvent('on' + etype);
            } else {
                var evObj = document.createEvent('Events');
                evObj.initEvent(etype, true, false);
                el.dispatchEvent(evObj);
            }
        }

        function sugesstionAjax(a, b, input) {
            var val = input.value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    var result = JSON.parse(this.responseText);
                    for (let i = 0; i < result[0].length; i++) {
                        b = document.createElement("DIV");
                    
                        var Title = document.createElement("section");
                        Title.innerHTML = "#<strong>" + result[1][i].substr(0, val.length) + "</strong>";
                        Title.innerHTML += result[1][i].substr(val.length);
                        
                        var Details = document.createElement("section");
                        Details.innerHTML = "<p>Total Packages: " + result[3][i] + "</p>";
                        Details.innerHTML += "<p>Ordered by " + toTitleCase(result[2][i]) + " [" + result[4][i] + "]</p>";
                        
                        Title.appendChild(Details);
                        b.appendChild(Title);

                        // b.innerHTML += " | Count: " + result["Total_Count"][i];
                        // b.innerHTML += " | " + toTitleCase(result["Customer_Info"][i]);
                        // b.innerHTML += " | " + result["Create_Time"][i];
                        b.addEventListener("click",
                            function (e) {
                                window.location.href =
                                    "./invoiceReport.php?search=" +
                                    encodeURI(result[1][i]);
                            }
                        );
                        a.appendChild(b);
                    }
                }
            };
            xhttp.open("GET", "./php/invoiceReportAjax.php?search="+encodeURI(val), true);
            xhttp.send();
        }
    </script>
</body>
</html>