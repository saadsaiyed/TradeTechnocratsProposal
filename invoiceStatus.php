<?php
    include "php/DBConnection.php";
    checkIfLoggedIn(2, 3, 1, 4);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | TTParikh</title>

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
            /* grid-row: 7 / 9; */
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
        .item2 table, .item3 table{
            width: 100%;
            text-align: center;
            margin-top: 20px;
            font-size: 20px;
        }
        .item2 td, .item3 td{
            padding: 20px;
        }
        .item2 th, .item3 th{
            background-color: var(--green-theme);
            color: var(--purple-theme);
            padding: 15px;
            border-radius: 5px;
        }
        .item2 a{
            text-decoration: none;
        }
        
        .item3 {
            grid-column: 5 / 9;
            /* grid-row: 7 / 9; */
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
            grid-row: 3 / 5;
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
        input{
            text-align: center;
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
            <a href='productReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Product Report</span></div></a>
            <a href='customerReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Customer Report</span></div></a>
            <a href='trackingReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Tracking Report</span></div></a>
            <a href='warehouseReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Warehouse Report</span></div></a>
        </div>
        <a href='production.php'><div class="menu"><span class="icon-gift"></span><span>Production</span></div></a>
        <a href='vendor.php'><div class="menu"><span class="icon-basket"></span><span>Vendor</span></div></a>
        <a href='products.php'><div class="menu"><span class="icon-beaker"></span><span>Products</span></div></a>
        <a href='tracking.php'><div class="menu"><span class="icon-map"></span><span>Tracking Number</div></a>
        <a href='invoiceStatus.php'><div class="menu highlight"><span class="icon-lightbulb"></span><span>Invoice Status</span></div></a>
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
                    <span>Order Status</span>
                </div>
                <div class="inside">
                    <form action="" method="get" enctype="multipart/form-data">
                    <table>
                        <tr><td><input type="text" size="70" name="search_ID" autocomplete="off" onkeyup='searchInvoice(this.value)'></td><td><input type="submit" value="Search"></td></tr>
                        <tr><td><div id="suggestion" class="suggestion"></div></td></tr>
                    </table>
                    </form>
                </div>
            </div>

            <div class="grid item2">
                <div class="top-info">
                    <div class="inside"><span>Ready For Pickup :</span></div>
                </div>
                <div class="info">
                    <table id="table">
                        <tr>
                            <th>Invoice Number</th><th>Customer / Company</th><th>Date</th><th>Status</th>
                        </tr>
                        <?php
                            $Temp = $_GET['temp'];
                            $timestamp = date("Y-m-d h:i:s");
                            $Status_ID = $_GET['status'];
                            if($Temp == '0')
                                $query = "UPDATE Invoice_Status SET Status = '1', pick_Up_Time='$timestamp' WHERE Status_ID = '$Status_ID'";
                            else
                               $query = "UPDATE Invoice_Status SET Status = '0' WHERE Status_ID = '$Status_ID'";

                            if($Temp == 0 || $Temp == 1)
                                runQuery($query);
                            
                            $search_ID = $_GET["search_ID"];
                            if($search_ID)
                                $query = "SELECT * FROM Invoice_Status WHERE Status_ID='$search_ID' AND Status='0'";// AND Create_Time LIKE '$timestamp%'";
                            else
                                $query = "SELECT * FROM Invoice_Status WHERE Status='0'";// AND Create_Time LIKE '$timestamp%'";
                            $result = runQuery($query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                $query = "SELECT * FROM Invoice AS I JOIN Customer As C ON I.Customer_ID = C.Customer_ID WHERE I.Invoice_ID='".$row['Invoice_ID']."'";
                                $result1 = runQuery($query);
                                $row1 = mysqli_fetch_assoc($result1);
                                $tempTime = $row1["Create_Time"];
                                $Create_Time = date('jS\, F Y \| h:i A \(l\)', (strtotime($tempTime)+10800));        
                                echo "<tr>";
                                echo "<td><label for=''>".$row1['Invoice_Num']."</label></td>";
                                echo "<td>".$row1['Name']." / ".$row1["Company_Name"]."</td>";
                                echo "<td><label for=''>".$Create_Time. "</label></td>";
                                echo "<td><a href='invoiceStatus.php?temp=0&status=" . $row['Status_ID'] . "'>--></a></td>";
                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div>
            </div>
            <div class="grid item3">
                <div class="top-info">
                    <div class="inside"><span>Picked Up :</span></div>
                </div>

                <div class="info">
                    <table>
                        <tr>
                            <th>Status</th><th>Invoice Number</th><th>Customer / Company</th><th>Date</th>
                        </tr>
                        <?php
                            $Status_ID = $_GET["status_id"];
                            if($Status_ID)
                                runQuery("UPDATE Invoice_Status SET Status = '2' WHERE Status_ID = '$Status_ID'");

                            $timestamp = date("Y-m-d");
                            $query = "SELECT * FROM Invoice_Status WHERE Status='1'";
                            $result = runQuery($query);
                            $i = 0;
                            while ($row = mysqli_fetch_assoc($result)) {
                                $query = "SELECT * FROM Invoice AS I JOIN Customer As C ON I.Customer_ID = C.Customer_ID WHERE I.Invoice_ID='".$row['Invoice_ID']."'";
                                //echo "query = $query<br/>";
                                $result1 = runQuery($query);
                                $row1 = mysqli_fetch_assoc($result1);
                                $tempTime = $row1["Create_Time"];
                                $Create_Time = date('jS\, F Y \| h:i A \(l\)', (strtotime($tempTime)+10800));
                                echo "<tr>";
                                echo "<td><a href='invoiceStatus.php?temp=1&status=" . $row['Status_ID'] . "'><--</a></td>";
                                echo "<td><label for=''>".$row1['Invoice_Num']."</label></td>";
                                echo "<td>".$row1['Name']." / ".$row1["Company_Name"]."</td>";
                                echo "<td><label for=''>".$Create_Time. "</label></td>";
                                echo "<td><a href='invoiceStatus.php?status_id=" . $row['Status_ID'] . "'><span class='icon-scope'></span></a></td>";
                                echo "</tr>";
                            }
                        ?>
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
        document.getElementById("invoiceStatus_go").addEventListener("click", submitInvoiceStatus, false);
        
        function searchInvoice(str) {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp=new XMLHttpRequest();
            } else {  // code for IE6, IE5
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange=function() {
                if (this.readyState==4 && this.status==200) {
                    var to_show = "";
                    console.log(this.responseText);
                    var results = JSON.parse(this.responseText)
                    if (results[0].length > 0){
                        to_show += "<tr><th>Invoice Number</th><th>Total Packages</th><th>Date</th><th>Status</th></tr>";
                        for (var i = 0; i < results[0].length; i++)
                        {                            
                            to_show += "<tr>";
                            to_show += "<td><label for=''>"+results[1][i]+"</label></td>";
                            to_show += "<td>"+results[3][i]+"</td>";
                            to_show += "<td><label for=''>"+results[4][i]+"</label></td>";
                            to_show += "<td><a href='invoiceStatus.php?temp=0&status=" +results[2][i]+ "'>--></a></td>";
                            to_show += "</tr>";

                        }
                    }
                    else
                        to_show = "No Result Found";
                    document.getElementById("table").innerHTML=to_show;
                    // document.getElementById("suggestion").style.border="1px solid #A5ACB2";
                }
            }
            xmlhttp.open("GET","php/invoiceSearchAjax.php?search="+str ,true);
            xmlhttp.send();
        }
    </script>
</body>
</html>