<?php
    include "php/DBConnection.php";
    checkIfLoggedIn(2, 4, 3, null);
?>

<html lang="en" onclick="removeSuggestion()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Report | TTParikh</title>

    <!-- CSS File Link -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/iconStyle.css">

    <!-- JavaScript And Jquery Link -->
    <script src="js/allJavaScripts.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>

    <style>
        .item1 {
            grid-column: 1 / 10;
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
        .item3 table. .item4 table, .item5 table{
            width: 100%;
            text-align: center;
            margin-top: 20px;
            font-size: 20px;
        }
        .item3 td, .item4 td, .item5 td{
            padding: 20px;
        }
        .item3 th, .item4 th, .item5 th{
            background-color: var(--green-theme);
            color: var(--purple-theme);
            padding: 15px;
            border-radius: 5px;
        }
        .item3 a, .item4 a, .item5 a{
            text-decoration: underline;
        }
        .item3 {
            grid-column: 1 / 4;
            grid-row: 3 / 9;
        }
        .item4 {
            grid-column: 4 / 7;
            grid-row: 3 / 9;
        }
        .item5 {
            grid-column: 7 / 10;
            grid-row: 3 / 9;
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
            <a href='trackingReport.php'><div class="menu drop-content highlight"><span class="icon-gift"></span><span>Tracking Report</span></div></a>
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
                    <span>Tracking Report :</span>
                </div>
                <div class="inside">
                    <form id="myForm" action="php/productReportSearch.php" method="post" enctype = "multipart/form-data">
                    <table>
                        <tr><td><input type="text" size="70" name="search" autocomplete="off" id="search_id" onkeyup="searchResultSettings(this.value)"></td><td><input type="submit" value="Search"></td></tr>
                        <tr><td><div id="suggestion" class="suggestion"></div></td><td></td></tr>
                    </table>
                    </form>
                </div>
            </div>
            <div class="grid item3">
                <div class="top-info">
                    <div class="inside"><span id="Canada_Post_Title">Canada Post :</span></div>
                </div>
                <div class="info">
                    <table>
                        <tr>
                            <th>Invoice Number</th><th>Customer_Name</th><th>Tracking Number</th>
                        </tr>
                        <?php 
                            $str = '';
                            $result = runQuery("SELECT * FROM Tracking WHERE Courier='Canada_Post' ORDER BY Create_Time DESC");
                            $Count = $result->num_rows;
                            while($row = mysqli_fetch_assoc($result)) {
                                $Invoice_ID = $row["Invoice_ID"];
                                $row1 = mysqli_fetch_assoc(runQuery("SELECT * FROM Invoice WHERE Invoice_ID = '$Invoice_ID'"));
                                $Invoice_Num = $row1["Invoice_Num"];
                                $Customer_ID = $row1["Customer_ID"];
                                $Customer_Name = mysqli_fetch_assoc(runQuery("SELECT Name FROM Customer WHERE Customer_ID='$Customer_ID'"))["Name"];
                                $str .= "<tr>";
                                $str .= "<td>".$Invoice_Num."</td><td>".$Customer_Name."</td><td><a href='https://www.canadapost.ca/track-reperage/en#/details/".$row["Tracking_ID"]." ' target='_blank'><label>".$row["Tracking_ID"]. "</label></a></td>";
                                $str .= "</tr>";
                            }
                            echo $str;
                            echo '<script>document.getElementById("Canada_Post_Title").innerHTML += " '.$Count.'";</script>';
                        ?>
                    </table>
                </div>
            </div>
            <div class="grid item4">
                <div class="top-info">
                    <div class="inside"><span id="UPS_Title">UPS :</span></div>
                </div>
                <div class="info">
                    <table>
                        <tr>
                            <th>Invoice Number</th><th>Total Packages</th><th>Tracking Number</th>
                        </tr>
                        <?php 
                            $str = '';
                            $result = runQuery("SELECT * FROM Tracking WHERE Courier='UPS' ORDER BY Create_Time DESC");
                            $Count = $result->num_rows;
                            while($row = mysqli_fetch_assoc($result)) {
                                $Invoice_ID = $row["Invoice_ID"];
                                $row1 = mysqli_fetch_assoc(runQuery("SELECT * FROM Invoice WHERE Invoice_ID = '$Invoice_ID'"));
                                $Invoice_Num = $row1["Invoice_Num"];
                                $Customer_ID = $row1["Customer_ID"];
                                $Customer_Name = mysqli_fetch_assoc(runQuery("SELECT Name FROM Customer WHERE Customer_ID='$Customer_ID'"))["Name"];
                                $str .= "<tr>";
                                $str .= "<td>".$Invoice_Num."</td><td>".$Customer_Name."</td><td><a href='https://www.ups.com/track?tracknum=".$row["Tracking_ID"]."' target='_blank'><label>".$row["Tracking_ID"]. "</label></a></td>";
                                $str .= "</tr>";
                            }
                            echo $str;
                            echo '<script>document.getElementById("UPS_Title").innerHTML += " '.$Count.'";</script>';
                        ?>
                    </table>
                </div>
            </div>
            <div class="grid item5">
                <div class="top-info">
                    <div class="inside"><span id="Canpar_Title">Canpar :</span></div>
                </div>
                <div class="info">
                    <table>
                        <tr>
                            <th>Invoice Number</th><th>Total Packages</th><th>Tracking Number</th>
                        </tr>
                        <?php 
                            $str = '';
                            $result = runQuery("SELECT * FROM Tracking WHERE Courier='Canpar' ORDER BY Create_Time DESC");
                            $Count = $result->num_rows;
                            while($row = mysqli_fetch_assoc($result)) {
                                $Invoice_ID = $row["Invoice_ID"];
                                $row1 = mysqli_fetch_assoc(runQuery("SELECT * FROM Invoice WHERE Invoice_ID = '$Invoice_ID'"));
                                $Invoice_Num = $row1["Invoice_Num"];
                                $Customer_ID = $row1["Customer_ID"];
                                $Customer_Name = mysqli_fetch_assoc(runQuery("SELECT Name FROM Customer WHERE Customer_ID='$Customer_ID'"))["Name"];
                                $str .= "<tr>";
                                $str .= "<td>".$Invoice_Num."</td><td>".$Customer_Name."</td><td><a href='https://www.canpar.com/en/track/TrackingAction.do?reference=".$row["Tracking_ID"]."' target='_blank'><label>".$row["Tracking_ID"]. "</label></a></td>";
                                $str .= "</tr>";
                            }
                            echo $str;
                            echo '<script>document.getElementById("Canpar_Title").innerHTML += " '.$Count.'";</script>';
                        ?>
                    </table>
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
    </script>
</body>
</html>