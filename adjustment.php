<?php
    include "php/DBConnection.php";
    checkIfLoggedIn(3, 4, null, null);

    $Barcode_ID = $_GET['Barcode_ID'];
    $B_Barcode_ID = $_GET['B_Barcode_ID'];
    if ($Barcode_ID || $B_Barcode_ID){
        echo "<script>console.log('here')</script>";
        $Total_Production = $_GET["Total_Production"];
        $Total_Sold = $_GET["Total_Sold"];
        $Adjustment = $_GET["Adjustment"];
        $Zone = $_GET["Zone"];
        $query = "UPDATE Barcode SET `Total_Production`='$Total_Production', `Total_Sold`='$Total_Sold', `Adjustment`='$Adjustment' WHERE Barcode_ID = '$Barcode_ID'";
        //echo "query = ".$query;
        $result = runQuery($query);
        $B_Total_Production = $_GET["B_Total_Production"];
        $B_Total_Sold = $_GET["B_Total_Sold"];
        $B_Adjustment = $_GET["B_Adjustment"];

        $query = "UPDATE Barcode SET `Total_Production`='$B_Total_Production', `Total_Sold`='$B_Total_Sold', `Adjustment`='$B_Adjustment' WHERE Barcode_ID = '$B_Barcode_ID'";
        //echo "<br/>query = ".$query;
        $result = runQuery($query);

        $query = "SELECT Product_ID FROM Barcode WHERE Barcode_ID='$Barcode_ID'";
        $result = runQuery($query);
        $Product_ID = mysqli_fetch_assoc($result)['Product_ID'];
    
        $query = "UPDATE Product SET `Zone`='G' WHERE `Product_ID`='$Product_ID'";
        $result = runQuery($query);

        $query = "DELETE B_Location_Barcode WHERE Barcode_ID = '$Barcode_ID'";
        $query = "DELETE B_Location_Barcode WHERE Barcode_ID = '$B_Barcode_ID'";
    }
?>  
<html lang="en" onclick="removeSuggestion()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | TTParikh</title>
    <link rel="icon" href="images/favicon.gif">

    <!-- CSS File Link -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/iconStyle.css">
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
            width: 100%;
        }
        .item1 > .inside > table tr > td > span{
            font-family: 'Oswald', sans-serif;
            font-size: 20px;
            font-weight: 400;
        }
        .item1 .page-title table td {
            padding:10px 20px;
        }
        .item1 .inside input{
            height: 40px;
        }
        .item1 .inside td{
            padding-right: 20px;
        }
        .item1 input[type=button]{
            line-height: 4.9px;
            margin-left: -1.7px;
        }
        .item2 {
            grid-column: 1 / 9;
            grid-row: 3 / 5;
        }
        .item2 select, .item2 option, .item3 select, .item3 option{
            width: 100%;
            height: 30px;
            background-color: var(--purple-theme);
            border: 1px solid var(--green-theme);
            text-align:center;
        }
        .item2 table, .item3 table{
            width: 100%;
            text-align: center;
            margin-top: 20px;
            font-size: 20px;
        }
        .item2 th, .item3 th{
            background-color: var(--green-theme);
            color: var(--purple-theme);
            padding: 15px;
        }
        .item2 .inside *, .item3 .inside *{
            padding-bottom: 20px;
        }
        .item3 {
            grid-column: 1 / 9;
            grid-row: 5 / 9;
        }
        .item1 select, .item1 option{
            width: 500px;
            height: 30px;
            background-color: var(--purple-theme);
            border: 1px solid var(--green-theme);
            text-align:center;
        }
        .details table td{
            padding: 15px 10px;
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
        <a href='products.php'><div class="menu"><span class="icon-beaker"></span><span>Products</span></div></a>
        <a href='tracking.php'><div class="menu"><span class="icon-map"></span><span>Tracking Number</div></a>
        <a href='invoiceStatus.php'><div class="menu"><span class="icon-lightbulb"></span><span>Invoice Status</span></div></a>
        <a href='customer.php'><div class="menu"><span class="icon-profile-male"></span><span>Customer</span></div></a>
        <a href='warehouse.php'><div class="menu"><span class="icon-gift"></span><span>Warehouse</span></div></a>
        <a href='adjustment.php'><div class="menu highlight"><span class="icon-gears"></span><span>Adjustment</span></div></a>
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
                <div class="inside">
                    <table>
                    <tr>
                        <td><span>Search Product:</span></td><td><input type="text" autocomplete="off" id="product_name_1" onkeyup="searchResultSettings(this.value)"></td>
                    </tr>
                    <tr>
                        <td></td><td><div id="suggestion" class="suggestion"></div></td>
                    </tr>
                    </table>
                </div>
            </div>

            <div class="grid item2">
                <div class="inside"><span id="product_name">Entry Feed(1 lb) : </span></div>
                <div class="details">
                    <form action="adjustment.php" method="get" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td>Barcode :</td>
                            <td><input type="text" autocomplete="off" name="Barcode_ID" id="Barcode_ID" onchange="adjust(this.value, '')"></td>
                            <td>Zone :</td>
                            <td><input type="text" autocomplete="off" id="Zone"></td>
                        </tr>
                        <tr>
                            <td>Total_Production :</td>
                            <td><input type="text" autocomplete="off" name="Total_Production" id="Total_Production" onchange="updateQuantity_PS()"></td>
                            <td>Total_Sold :</td>
                            <td><input type="text" autocomplete="off" name="Total_Sold" id="Total_Sold" onchange="updateQuantity_PS()"></td>
                        </tr>
                        <tr>
                            <td>Stock :</td>
                            <td><input type="text" autocomplete="off" name="Stock" id="Stock" onchange="updateQuantity()"></td>
                            <td>Adjustment :</td>
                            <td><input type="text" autocomplete="off" name="Adjustment" id="Adjustment"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="grid item3">
                <div class="inside"><span id="B_product_name">Entry Feed(114 g) : </span></div>
                <div class="details">
                    <table>
                        <tr>
                            <td>Barcode :</td>
                            <td><input type="text" autocomplete="off" name="B_Barcode_ID" id="B_Barcode_ID" onchange="adjust(this.value, 'B_')"></td>
                        </tr>
                        <tr>
                            <td>Total_Production :</td>
                            <td><input type="text" autocomplete="off" name="B_Total_Production" id="B_Total_Production" onchange="updateQuantity_PS()"></td>
                            <td>Total_Sold :</td>
                            <td><input type="text" autocomplete="off" name="B_Total_Sold" id="B_Total_Sold" onchange="updateQuantity_PS()"></td>
                        </tr>
                        <tr>
                            <td>Stock :</td>
                            <td><input type="text" autocomplete="off" name="B_Stock" id="B_Stock" onchange="updateQuantity()"></td>
                            <td>Adjustment :</td>
                        <td><input type="text" autocomplete="off" name="B_Adjustment" id="B_Adjustment"></td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td><input type="reset" value="Reset"></td>
                            <td><input type="submit" value="Submit"></td>
                        </tr>
                    </table>
                    </form>
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
        document.getElementById("invoiceStatus_go").addEventListener("click", submitInvoiceStatus, false);

        function changeBarcodeValue(str1, str2, name, zone) {
            document.getElementById("product_name_1").value = name;

            document.getElementById("Barcode_ID").value = str1;
            adjust(str1, "");
            if(str2 != 'undefined'){
                document.getElementById("B_Barcode_ID").value = str2;
                adjust(str2, "B_");
            }
            else
                document.getElementById("B_Barcode_ID").value = "N/A";
            removeSuggestion();
            document.getElementById("Zone").value = zone;
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