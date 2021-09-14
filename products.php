<?php
    include "php/DBConnection.php";
    checkIfLoggedIn(2, 3, 4, null);
    
    $DATA = array();

    $Item_Code = array();
    $Product_Name = array();
    $Barcode1 = array();
    $Barcode2 = array();
    $Zone = array();

    $result = runQuery("SELECT * FROM Product");
    $i = 0;
    while($row = mysqli_fetch_assoc($result)){
        $Item_Code[$i] = $row["Item_Code"];
        $Product_Name[$i] = $row["Name"];
        $Zone[$i] = $row["Zone"];
        $Product_ID = $row["Product_ID"];

        $result1 = runQuery("SELECT * FROM Barcode WHERE Product_ID='$Product_ID' ORDER BY Type ASC");
        while ($row1 = mysqli_fetch_assoc($result1)) {
            if($row1["Type"] == "A"){
                $Barcode1[$i] = $row1["Barcode_ID"];
            }
            else{
                $Barcode2[$i] = $row1["Barcode_ID"];
            }
        }
        $i++;
    }
        
    array_push($DATA, $Item_Code);              //Item_Code - 0
    array_push($DATA, $Product_Name);           //Product_Name - 1
    array_push($DATA, $Barcode1);               //Barcode1 - 2
    array_push($DATA, $Barcode2);               //Barcode2 - 3
    array_push($DATA, $Zone);                   //Zone - 4
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | TTParikh</title>

    <!-- CSS File Link -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/iconStyle.css">
    <script src="js/allJavaScripts.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
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
                width: 200px;
            }
            #text-area td:nth-child(2){
                width: 600px;
            }
            #text-area td:nth-child(3){
                width: 300px;
            }
            #text-area td:nth-child(4){
                width: 300px;
            }
            #text-area td:nth-child(5){
                width: 200px;
            }

            #text-area thead th:nth-child(1){
                width: 200px;
            }
            #text-area thead th:nth-child(2){
                width: 600px;
            }
            #text-area thead th:nth-child(3){
                width: 300px;
            }
            #text-area thead th:nth-child(4){
                width: 300px;
            }
            #text-area thead th:nth-child(5){
                width: 200px;
            }

            #text-area thead{
                display: block;
            }
            #text-area  tbody{
                height: 100%;
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
        .item2 .inside *{
            padding-bottom: 20px;
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
        <a href='index.php'><div class="menu "><span class="icon-clipboard"></span><span>Dashboard</span></div></a>
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
        <a href='vendor.php'><div class="menu"><span class="icon-basket"></span><span>Vendor</span></div></a>
        <a href='products.php'><div class="menu highlight"><span class="icon-beaker"></span><span>Products</span></div></a>
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
                    <span>Product List</span>
                </div>
                <!-- <div class="inside">
                    <table>
                        <tr>
                            <td><input type="text" size="70" autocomplete="off" id="search" name="search" style="text-transform:uppercase;" onkeyup="searchResult(this.value)"></td>
                            <td><input type="submit" value="Search"></td>
                        </tr>
                        <tr><td><div id="suggestion"></div></td></tr>
                    </table>
                </div> -->
            </div>

            <div class="grid item2">
                <div class="details">
                    <table id="text-area">
                    <thead>
                        <tr>
                            <th><label for="">Item Code</label></th>
                            <th><label for="">Product Name</label></th>
                            <th><label for="">Barcode 1</label></th>
                            <th><label for="">Barcode 2</label></th>
                            <th><label for="">Zone</label></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for($i=0; $i < count($DATA[1]); $i++) {
                        echo "<tr>";
                            echo "<td><input type='text' value='".$DATA[0][$i]."'></td>";
                            echo "<td><input type='text' value='".$DATA[1][$i]."'></td>";
                            echo "<td><input type='text' value='".$DATA[2][$i]."'></td>";
                            echo "<td><input type='text' value='".$DATA[3][$i]."'></td>";
                            echo "<td><input type='text' value='".$DATA[4][$i]."'></td>";
                        echo "</tr>";
                        }?>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // window.onbeforeunload = function(){
        //     return 'Are you sure you want to leave?';
        // };
        window.onload = function() {
            feather.replace();
            var dropdown = document.querySelectorAll(".drop-content");
            for (let i = 0; i < dropdown.length; i++) {
                dropdown[i].style.display = "none";
            }
            document.getElementById("invoiceStatus_go").addEventListener("click", submitInvoiceStatus, false);
        }
    </script>
</body>
</html>