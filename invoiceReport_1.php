<?php
    include "php/DBConnection.php";
    checkIfLoggedIn(2, 3, 4, null);

    if(isset($_POST["submit"])){
        $search = $_POST["search"];
        $DATA = array();
    
        $query = "SELECT * FROM Invoice WHERE Invoice_Num='$search'";
        $result = runQuery($query);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $temp = $row["Name"] . " - " . $row["Company_Name"];
                $DATA[0] = $row["Invoice_Num"];
                $DATA[1] = $row["Total_Count"];
                $DATA[2] = $row["Create_Time"];
                $Customer_ID = $row["Customer_ID"];
                $tempRow = mysqli_fetch_assoc(runQuery("SELECT * FROM Customer WHERE Customer_ID='$Customer_ID'"));
                $DATA[3] = $tempRow["Name"] . " - " . $tempRow["Company_Name"];
            }
        }
    }
?>

<html lang="en" onclick="removeSuggestion()">
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
            grid-row: 3 / 9;
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
        .item2 table{
            width: 100%;
            text-align: center;
            margin-top: 20px;
            font-size: 20px;
        }
        .item2 td{
            padding: 20px;
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
<body>
    <div class="sidebar" id = "sidebar_Id">
        <div class="search-box">
            <form method="post"  action="php/bookingFetch.php" enctype = "multipart/form-data">
                <table>
                    <tr>
                        <td><input type="text" placeholder="Search..."></td>
                        <td><input type="button" value="GO"></td>
                    </tr>
                </table>
            </form>
        </div>
        <a href='index.php'><div class="menu"><span class="icon-clipboard"></span><span>Dashboard</span></div></a>
        <a href='invoice.php'><div class="menu"><span class="icon-browser"></span><span>Invoice</span></div></a>
        <a onclick="dropdownToggle()"><div class="menu dropdown-btn"><span class="icon-presentation"></span><span>Report</span><span><i data-feather="chevron-down"></i></span></div></a>
        <div class="side-drop" id="side-drop-id"> 
            <a href='invoiceReport.php'><div class="menu drop-content highlight"><span class="icon-gift"></span><span>Invoice Report</span></div></a>
            <a href='productReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Product Report</span></div></a>
            <a href='customerReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Customer Report</span></div></a>
        </div>
        <a href='production.php'><div class="menu"><span class="icon-gift"></span><span>Production</span></div></a>
        <a href='vendor.php'><div class="menu"><span class="icon-basket"></span><span>Vendor</span></div></a>
        <a href='customer.php'><div class="menu"><span class="icon-profile-male"></span><span>Customer</span></div></a>
        <a href='products.php'><div class="menu"><span class="icon-beaker"></span><span>Products</span></div></a>
        <a href='tracking.php'><div class="menu"><span class="icon-map"></span><span>Tracking Number</div></a>
        <a href='adjustment.php'><div class="menu"><span class="icon-gears"></span><span>Adjustment</span></div></a>
        <a href='invoiceStatus.php'><div class="menu"><span class="icon-lightbulb"></span><span>Invoice Status</span></div></a>
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
                    <span>Product Report : <?=$DATA[18][0]?></span>
                </div>
                <div class="inside">
                    <form id="myForm" method="post" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td><input type="text" size="70" name="search" autocomplete="off" id="search_id" onkeyup="searchResultSettings(this.value)"></td>
                            <td><input type="submit" name="submit" value="Search"></td>
                        </tr>
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
                            <th>Invoice Number</th><th>Total Packages</th><th>Date</th>
                        </tr>
                        <?php 
                            $str = '';
                            for ($i=0; $i < count($DATA[13]); $i++) { 
                                    $str += "<tr>";
                                    $str += "<td><a href='#invoce' onclick='invoiceInfo(".$DATA[13][$i].")'><label for=''>".$DATA[13][$i]."</label></a></td><td>".$DATA[14][$i]."</td><td><label for=''>".date('mmm dd, YY', $DATA[13][$i]). "</label></td>";
                                    $str += "</tr>";
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
                                <td><label for="">Customer Name:</label></td><td><label for="">Andrew Kingston</label></td>
                            </tr>
                            <tr>
                                <td><label for="">Invoice Number:</label></td><td><label for="">95010</label></td><td style="float:right"><input type="button" value="Edit"></td>
                            </tr>
                            <tr>
                                <td><label for="">Total Packages:</label></td><td><label for="">23</label></td>
                            </tr>
                        </table>
                    </div>
                    <div class="details">
                        <table>
                            <tr>
                                <th><label for="">Product name</label></th><th>Weight</th><th>Count</th>
                            </tr>
                            <tr>
                                <td><label for="">BURDOCK ROOT POWDER</label></td><td><label for="">1.0 lb</label></td><td><label for="">2</label></td>
                            </tr>
                            <tr>
                                <td><label for="">YARROW FLOWER C/S</label></td><td><label for="">1.0 lb</label></td><td><label for="">1</label></td>
                            </tr>
                            <tr>
                                <td><label for="">SPINACH POWDER</label></td><td><label for="">144 g</label></td><td><label for="">2</label></td>
                            </tr>
                            <tr>
                                <td><label for="">COLTSFOOT C/S</label></td><td><label for="">1.0 lb</label></td><td><label for="">2</label></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    
    <script>
        feather.replace();
        function changeBarcodeValue(str1, str2, name, zone) {
            document.getElementById("search_id").value = name;

            removeSuggestion();

            document.getElementById("myForm").submit();
        }
        function removeSuggestion() {
            if(!$("#search_id").is(":focus")){
                document.getElementById("suggestion").innerHTML="";
                document.getElementById("suggestion").style.padding="0px";
                document.getElementById("suggestion").style.border="none";
            }
        }
        function searchResultSettings(str) {
            
        }
    </script>
</body>
</html>