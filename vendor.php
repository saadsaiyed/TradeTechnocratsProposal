<?php
    include "php/DBConnection.php";
    checkIfLoggedIn(2, 3, 4, null);
    
    if($_GET['search'] != "" && $_GET['search'] != " "){
        $search = $_GET['search'];
    }
    else
        $search = $_POST["search"];
    $DATA = array();
    if ($search) {
        $search = htmlspecialchars($search);

        $Item_Code = array();
        $Product_Name = array();
        $Vendor1 = array();
        $Vendor2 = array();
        $Total_Sold = array();
        $Stock = array();
        $Reorder = array();
        $Zone = array();
        $Sold_Range = array();
        $Location = array();
        $Botanical_Name = array();

        //Vendor - START
            $query = "SELECT * FROM Vendor WHERE Name LIKE '$search%'";
            $result = runQuery($query);
            if($result->num_rows > 0){
                $row = mysqli_fetch_assoc($result);
                $Vendor_Name = $row['Name'];
                unset($result);
                //B_Vendor_Product - START
                    $query = "SELECT * FROM B_Vendor_Product WHERE Vendor_ID='".$row['Vendor_ID']."'";
                    $result = runQuery($query);
                    if($result->num_rows > 0){
                        $i = 0;
                        while ($row = mysqli_fetch_assoc($result)) { //Search Of Vendor To Product
                            //Vendor_Preference - START
                                if($row['Preference'] == 'A'){
                                    $Vendor1[$i] = $Vendor_Name;

                                    $query = "SELECT * FROM B_Vendor_Product WHERE Product_ID='".$row['Product_ID']."' AND Preference='B'";
                                    $result_preference = runQuery($query);
                                    if($result_preference->num_rows > 0){
                                        $temp_row = mysqli_fetch_assoc($result_preference);
                                        unset($result_preference);
                                        $query = "SELECT Name FROM Vendor WHERE Vendor_ID='".$temp_row['Vendor_ID']."'";
                                        $Vendor2[$i] = mysqli_fetch_assoc(runQuery($query))['Name'];
                                    }
                                    else{
                                        $Vendor2[$i] = "";
                                    }
                                }
                                else if($row['Preference'] == 'B'){
                                    $Vendor2[$i] = $Vendor_Name;

                                    $query = "SELECT * FROM B_Vendor_Product WHERE Product_ID='".$row['Product_ID']."' AND Preference='A'";
                                    $result_preference = runQuery($query);
                                    if($result_preference->num_rows > 0){
                                        $temp_row = mysqli_fetch_assoc($result_preference);
                                        unset($result_preference);
                                        $query = "SELECT Name FROM Vendor WHERE Vendor_ID='".$temp_row['Vendor_ID']."'";
                                        $Vendor1[$i] = mysqli_fetch_assoc(runQuery($query))['Name'];
                                    }
                                }
                            //Vendor_Preference - END

                            $query = "SELECT * FROM Product WHERE Product_ID='".$row['Product_ID']."' ORDER BY Zone ASC";
                            $resultP = runQuery($query);
                            if($resultP->num_rows > 0){//Single Product Loop
                                $rowP = mysqli_fetch_assoc($resultP);

                                $Item_Code[$i] = $rowP["Item_Code"];
                                $Product_Name[$i] = $rowP["Name"];
                                $Zone[$i] = $rowP["Zone"];

                                $query = "SELECT * FROM Barcode WHERE Product_ID='".$rowP['Product_ID']."' ORDER BY Product_ID ASC, Type ASC";
                                $resultB = runQuery($query);
                                if($resultB->num_rows > 0){
                                    $temp_Sold = array();
                                    $temp_Production = array();
                                    $temp_Adjustment = array();
                                    $temp_Reorder = array();
                                    $temp_Sold_Range = array();
                                    $temp_location = '';
                                    $j = 0;
                                    while ($rowB = mysqli_fetch_assoc($resultB)) { //Each Barcode For Single Product
                                        //Test to achieve sold in some date interval - START
                                            $Barcode_ID = $rowB["Barcode_ID"];
                                            $temp_sold_count = 0;
                                            if($_POST['start_date'])
                                                $Start_Time = $_POST['start_date'] . ' 00:00:00';
                                            else
                                                $Start_Time = $_GET['start_date'] . ' 00:00:00';
                                            $query = "SELECT * FROM B_Invoice_Barcode WHERE Barcode_ID = '$Barcode_ID' AND Create_Time > '$Start_Time'";
                                            $tempResult = runQuery($query);
                                            //print_r($tempResult);
                                            //echo '<br/>';
                                            while ($tempRow = mysqli_fetch_assoc($tempResult)) { //All Appearance of each Barcode In single Product
                                                $temp_sold_count += (int)$tempRow["Count"];
                                            }
                                            $temp_Sold_Range[$j] = $temp_sold_count;
                                        //Test to achieve sold in some date interval - END

                                        $temp_Sold[$j] = (float)$rowB["Total_Sold"];
                                        $temp_Adjustment[$j] = (float)$rowB["Adjustment"];
                                        $temp_Production[$j] = (float)$rowB["Total_Production"];
                                        $temp_Reorder[$j] = (float)$rowB["Mini_Reorder"];

                                        //Location - Start
                                            $result_location = runQuery("SELECT * FROM B_Location_Barcode WHERE Barcode_ID='$Barcode_ID'");
                                            while ($row_location = mysqli_fetch_assoc($result_location)) {
                                                $temp_location .= $row_location["Location_ID"] . ", ";
                                            }
                                            $temp_location=rtrim($temp_location, ", ") . " | ";
                                        //Location - Start
                                        $j++;
                                    }
                                    $Stock1 = $temp_Production[0] - $temp_Sold[0] + $temp_Adjustment[0];
                                    $Stock2 = $temp_Production[1] - $temp_Sold[1] + $temp_Adjustment[1];
                                    $Stock[$i] = $Stock1 + ($Stock2/4);
                                    $Reorder[$i] = $temp_Reorder[0] + ($temp_Reorder[1]/4);
                                    $Total_Sold[$i] = $temp_Sold[0] + ($temp_Sold[1]/4);
                                    $Sold_Range[$i] = $temp_Sold_Range[0] + ($temp_Sold_Range[1]/4);
                                    array_push($Location, $temp_location = rtrim($temp_location, " | "));
                                    //echo "Sold_Range = $Sold_Range[$i], Total_Sold = $Total_Sold[$i], Stock = $Stock[$i], Reorder = $Reorder[$i], $i";
                                }
                            }
                            //B_Botanical_Product - START
                                $query = "SELECT * FROM B_Botanical_Product AS BP 
                                INNER JOIN Botanical_Name AS BN 
                                ON BN.Bot_ID = BP.Bot_ID 
                                WHERE BP.Product_ID = '".$row['Product_ID']."'";

                                $result1 = runQuery($query);
                                while($row1 = mysqli_fetch_assoc($result1)){
                                    array_push($Botanical_Name, $row1["Bot_Name"]);
                                }
                            //B_Botanical_Product - END

                            $i++;
                        }
                    }
                //B_Vendor_Product - END
            }
            //Vendor - END
            //Search via Product - START
            else{
                unset($result);
                $query = "SELECT * FROM Product WHERE Name LIKE '%$search%'";
                $result = runQuery($query);

                if($result->num_rows == 0){
                    unset($result);
                    $query = "SELECT * FROM Product WHERE Item_Code LIKE '%$search%'";
                    $result = runQuery($query);
                }

                if($result->num_rows > 0){

                }
            }
            //Search via Product - END

        array_push($DATA, $Item_Code);              //Item_Code - 0
        array_push($DATA, $Product_Name);           //Product_Name - 1
        array_push($DATA, $Vendor1);                //Vendor1 - 2
        array_push($DATA, $Vendor2);                //Vendor2 - 3
        array_push($DATA, $Total_Sold);             //Total_Sold - 4
        array_push($DATA, $Stock);                  //Stock - 5
        array_push($DATA, $Reorder);                //Reorder - 6
        array_push($DATA, $Zone);                   //Zone - 7
        array_push($DATA, $Sold_Range);             //Sold_Range - 8
        array_push($DATA, $Location);               //Location - 9
        array_push($DATA, $Botanical_Name);         //Botanical_Name - 10
        //print_r($DATA);
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor | TTParikh</title>

    <!-- CSS File Link -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/iconStyle.css">
    <script src="js/allJavaScripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>    <script src="https://unpkg.com/feather-icons"></script>
    <script src="js/vendor.js"></script>
    <link rel="icon" type="image/png" href="./favicon.ico" />

    <style type="text/css">
        .top-info, date-range{
            display:flex;
            justify-content: space-between;
        }
        .date-range input[type=date]{
            width: auto;
            background-color: var(--purple-theme);
            border: 1px solid var(--green-theme);
            font-size: 14px;
            line-height: 17px;
        }
        .date-range input[type=button]{
            width: auto;
            line-height: 22px;
        }
        .date-range label{
            margin: 10px;
        }

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
            text-align: center;
            margin-top: 20px;
            font-size: 20px;
        }
        .item2 tbody{
            overflow-x: auto;
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
                width: 45px;
            }
            #text-area td:nth-child(2){
                width: 107px;
            }
            #text-area td:nth-child(3){
                width: 440px;
            }
            #text-area td:nth-child(4){
                width: 250px;
            }
            #text-area td:nth-child(5){
                width: 250px;
            }
            #text-area td:nth-child(6){
                width: 250px;
            }
            #text-area td:nth-child(7){
                width: 100px;
            }
            #text-area td:nth-child(8){
                width: 100px;
            }
            #text-area td:nth-child(9){
                width: 100px;
            }
            #text-area td:nth-child(10){
                width: 100px;
            }
            #text-area td:nth-child(11){
                width: 100px;
            }

            #text-area thead th:nth-child(1){
                width: 50px;
            }
            #text-area thead th:nth-child(2){
                width: 110px;
            }
            #text-area thead th:nth-child(3){
                width: 720px;
            }
            #text-area thead th:nth-child(4){
                width: 345px;
            }
            #text-area thead th:nth-child(5){
                width: 345px;
            }
            #text-area thead th:nth-child(6){
                width: 340px;
            }
            #text-area thead th:nth-child(7){
                width: 100px;
            }
            #text-area thead th:nth-child(8){
                width: 100px;
            }
            #text-area thead th:nth-child(9){
                width: 100px;
            }
            #text-area thead th:nth-child(10){
                width: 100px;
            }
            #text-area thead th:nth-child(11){
                width: 100px;
            }

            #text-area thead{
                display: block;
            }
            #text-area  tbody{
                height: 440px;
                display: block;
                /* overflow: auto; */
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
        #text-area td:nth-child(9){
            border-right: none;
        }
        #text-area td{
            border-right: 1px solid var(--green-theme);
        }
        /* .item2 thead{
            background-color:var(--green-theme)
        } */
        .item2 .inside{
            padding:10px 20px 0px 0px;
        }
        .item1 select, .item1 option{
            width: 500px;
            height: 30px;
            background-color: var(--purple-theme);
            border: 1px solid var(--green-theme);
            text-align:center;
        }
        .item1 select option{
            background: var(--purple-theme);
        }

        #suggestion{
            position:absolute;
            background-color:var(--purple-theme);
        }
        #suggestion a{
            padding-top: 50px;
        }


        /* style for printing page */
        #print-table td:nth-child(1), #teaxt-area td:nth-child(1) {
            width: 25px;
        }
        #print-table td:nth-child(2), #teaxt-area td:nth-child(2) {
            width: 100px;
        }
        #print-table td:nth-child(3), #teaxt-area td:nth-child(3) {
            width: 400px;
        }
        #print-table td:nth-child(4), #teaxt-area td:nth-child(4) {
            width: 100px;
        }
        #print-table td:nth-child(5), #teaxt-area td:nth-child(5) {
            width: 100px;
        }
        #print-table td:nth-child(6), #teaxt-area td:nth-child(6) {
            width: 50px;
        }
        #print-table td:nth-child(7), #teaxt-area td:nth-child(7) {
            width: 50px;
        }
        #print-table td:nth-child(8), #teaxt-area td:nth-child(8) {
            width: 50px;
        }
        
        #print-table tr:nth-child(2n + 1), #teaxt-area tr:nth-child(2n + 1){
            background:var(--lightpurple-theme)
        }

        #print-table th, #teaxt-area th{
            padding:10px;
            background-color: #555555;
            color: #ffffff;
        }
        #print-table td input, #teaxt-area td input{
            border:none;
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
            <a href='productReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Product Report</span></div></a>
            <a href='customerReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Customer Report</span></div></a>
            <a href='trackingReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Tracking Report</span></div></a>
            <a href='warehouseReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Warehouse Report</span></div></a>
        </div>
        <a href='production.php'><div class="menu"><span class="icon-gift"></span><span>Production</span></div></a>
        <a href='vendor.php'><div class="menu highlight"><span class="icon-basket"></span><span>Vendor</span></div></a>
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
                    <span>Vendor Information</span>
                </div>
                <div class="inside">
                    <form action="" method="post" enctype="multipart/form-data" id="myForm">
                    <table>
                        <tr>
                            <td><input type="text" size="70" autocomplete="off" id="search" name="search" style="text-transform:uppercase;" onkeyup="searchResult(this.value)"></td>
                            <td><input type="submit" value="Search"></td>
                        </tr>
                        <tr><td><div id="suggestion"></div></td></tr>
                    </table>
                </div>
            </div>

            <div class="grid item2">
                <div class="top-info">
                    <div><input type="button" value="Print Friendly" onclick="converToPrint()"></div>
                    <div class="top-info">
                    <div class="inside"><span>Set Time Range for Sold Items:</span></div>
                    <div class="date-range">
                        <input type="date" name="start_date" id="start_date"><label for="">-</label><input type="date" name="end_date" id="end_date"><input type="button" value="Sort">
                    </div>
                    </div>
                    </form>
                </div>
                <div class="details">
                    <table id="text-area">
                    <thead>
                        <tr>
                            <th></th>
                            <th><label for="">Item Code</label></th>
                            <th><label for="">Product Name</label></th>
                            <th><label for="">Botanical Name</label></th>
                            <th><label for="">Vendor 1</label></th>
                            <th><label for="">Vendor 2</label></th>
                            <th><label for="">Total Sold</label></th>
                            <th><label for="">Stock</label></th>
                            <th><label for="">Reorder</label></th>
                            <th><label for="">Zone</label></th>
                            <th><label for="">Sold Range</label></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for($i=0; $i < count($DATA[1]); $i++) {
                        echo "<tr id='tr_$i'>";
                            echo "<td><span class='icon-scope' onclick='document.getElementById(\"tr_$i\").style.display =\"none\";'></td>";
                            echo "<td><input type='text' value='".$DATA[0][$i]."'></td>";
                            echo "<td><input type='text' value='".$DATA[1][$i]."'></td>";
                            echo "<td><input type='text' value='".$DATA[10][$i]."'></td>";
                            echo "<td><input type='text' value='".$DATA[2][$i]."'></td>";
                            echo "<td><input type='text' value='".$DATA[3][$i]."'></td>";
                            echo "<td><input type='text' value='".$DATA[4][$i]."'></td>";
                            echo "<td><input type='text' value='".$DATA[5][$i]."' id='stock_$i'></td>";
                            echo "<td><input type='text' value='".$DATA[6][$i]."' id='reorder_$i'></td>";
                            echo "<td><input type='text' value='".$DATA[7][$i]."' id='zone_$i'></td>";
                            echo "<td><input type='text' value='".$DATA[8][$i]."'></td>";
                        echo "</tr>";
                        }?>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onbeforeunload = function(){
            return 'Are you sure you want to leave?';
        };
        window.onload = function() {
            feather.replace();
            document.getElementById("invoiceStatus_go").addEventListener("click", submitInvoiceStatus, false);

            var dropdown = document.querySelectorAll(".drop-content");
            for (let i = 0; i < dropdown.length; i++) {
                dropdown[i].style.display = "none";
            }

            var count = <?php echo count($DATA[1])?>;
            for (let i = 0; i < count; i++) {
                blinkIfGreen(i);
            }


            //loadOutOfStock();
        }
        var check = true;

        function converToPrint(){
            if(check){
                openCloseSidebar(document.getElementsByClassName("burgureMenuIcon")[0]);
                document.documentElement.style.setProperty('--green-theme', '#333333');
                document.documentElement.style.setProperty('--purple-theme', '#ffffff');
                document.documentElement.style.setProperty('--lightpurple-theme', '#eeeeee');
                document.documentElement.style.setProperty('--font-color', '#000000');
                document.documentElement.style.setProperty('--titlebar-font-color', '#ffffff');

                <?php 
                    $to_show = "<div class='top-info'>";
                        $to_show .= "<h1 style='padding-bottom:20px'>".$_GET['search']."</h1>";
                        $to_show .= "<button style='background-color:#333;padding:10px;border-radius:10px' onclick='converToPrint()'>Go Back</button>";
                    $to_show .= "</div>";
                    $to_show .= "<table id='print-table'><thead>";
                        $to_show .= '<tr><th></th>';
                            $to_show .= '<th><label>Item Code</label></th>';
                            $to_show .= '<th><label>Product Name</label></th>';
                            $to_show .= '<th><label>Total Sold</label></th>';
                            $to_show .= '<th><label>Stock</label></th>';
                            $to_show .= '<th><label>Reorder</label></th>';
                            $to_show .= '<th><label>Zone</label></th>';
                            $to_show .= '<th><label>Sold Range</label></th>';
                            $to_show .= '<th><label>Location</label></th>';
                        $to_show .= '</tr>';
                    $to_show .= '</thead><tbody>';
                    for($i=0; $i < count($DATA[1]); $i++) {
                        $to_show .= "<tr id='tr_$i'>";
                            $to_show .= "<td onclick='removeProduct($i)'><span class='icon-scope'></td>";
                            $to_show .= "<td><input type='text' value='".$DATA[0][$i]."'></td>";
                            $to_show .= "<td><input type='text' value='".$DATA[1][$i]."'></td>";
                            $to_show .= "<td><input type='text' value='".$DATA[4][$i]."'></td>";
                            $to_show .= "<td><input type='text' value='".$DATA[5][$i]."' id='stock_$i'></td>";
                            $to_show .= "<td><input type='text' value='".$DATA[6][$i]."' id='reorder_$i'></td>";
                            $to_show .= "<td><input type='text' value='".$DATA[7][$i]."' id='zone_$i'></td>";
                            $to_show .= "<td><input type='text' value='".$DATA[8][$i]."'></td>";
                            $to_show .= "<td><input type='text' value='".$DATA[9][$i]."'></td>";
                        $to_show .= "</tr>";
                    }
                    $to_show .= '</tbody></table>';
                ?>
                
                document.getElementsByTagName('body')[0].innerHTML = "<?php echo $to_show;?>";
                
                // document.getElementsByTagName('body')[0].innerHTML += "<table id='text-area'></table>";
                // loadOutOfStock();

                check = false;
            }
            else location.reload();
        }
        function removeProduct(index) {
            document.getElementById('tr_'+index).style.display='none';
        }
    </script>
    <a id="downloadAnchorElem"></a>
</body>
</html>

