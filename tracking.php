<?php
    include "php/DBConnection.php";
    checkIfLoggedIn(1, 3, 4, null);
    
    if($_GET["search"]){
        window.find($_GET["search"]);
    }
    $temp = $_GET['Courier'];
    if($temp){
        $result = runQuery("UPDATE Tracking SET Status='1' WHERE Courier='$temp' AND Status='0'");
        if ($result)
            $str = '<script>alert("'.$temp.'\'s end of the day is set.");</script>';
        else
            $str = '<script>alert("Something went wrong!");</script>';
        
        header("Location:tracking.php");
        echo $str;
        exit();
    }
    $temp = $_GET['info'];
    if($temp){
        echo '<script>alert("'.$temp.'");</script>';
    }
    
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production | TTParikh</title>

    <!-- CSS File Link -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/iconStyle.css">
    <script src="js/allJavaScripts.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>

    <style>
        .item1 {
            grid-column: 1 / 10;
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
            grid-column: 1 / 10;
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
        .item2 td{
            padding: 20px;
        }
        .item2 th{
            background-color: var(--green-theme);
            color: var(--purple-theme);
            padding: 15px;
        }
        .item2 a{
            text-decoration: underline;
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
            grid-row: 5 / 9;
        }
        .item4 {
            grid-column: 4 / 7;
            grid-row: 5 / 9;
        }
        .item5 {
            grid-column: 7 / 10;
            grid-row: 5 / 9;
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
        <a href='tracking.php'><div class="menu highlight"><span class="icon-map"></span><span>Tracking Number</div></a>
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
                <div class="page-title"><span>Tracking Number Entry :</span></div>
            </div>

            <div class="grid item2">
                <div class="inside"><span>Entry Feed</span></div>
                <div class="details">
                    <form action="php/trackingFetch.php" method="post" enctype="multipart/form-data">
                        <table id="add_here_table">
                            <tr>
                                <td>Taken From :</td>
                                <td><select name="tracking_company" id="tracking_company">
                                    <option value="Canada_Post">Canada Post</option>
                                    <option value="Canpar">Canpar</option>
                                    <option value="UPS">UPS</option>
                                    <option value="Other">Other</option>
                                </select></td>
                                <td>Invoice Number :</td>
                                <td><input type="text" name="invoice_num" id="invoice_num" onchange="invoiceSpliter(this.value)"></td>
                            </tr>
                            <tr>
                                <td>Tracking Number :</td>
                                <td><input type="text" id="tracking" name="tracking_num_1"></td>
                                <td>Boxes :</td>
                                <td><input type="number" name="Boxes" value="1" onchange="addNewTrackingNum(this.value)"></td>
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

            <div class="grid item3">
                <div class="top-info">
                    <div class="inside"><span id="Canada_Post_Title">Canada Post :</span></div>
                    <div class="date-range">
                        <a href="./tracking.php?Courier=Canada_Post"><input type="button" value="End Of The Day" style="border-radius:20px;"></a>
                    </div>
                </div>
                <div class="info">
                    <table>
                        <tr>
                            <th>Invoice Number</th><th>Customer_Name</th><th>Tracking Number</th>
                        </tr>
                        <?php 
                            $str = '';
                            $result = runQuery("SELECT * FROM Tracking WHERE Courier='Canada_Post' AND Status='0' ORDER BY Create_Time DESC");
                            $Count = $result->num_rows;
                            while($row = mysqli_fetch_assoc($result)) {
                                $Invoice_ID = $row["Invoice_ID"];
                                $row1 = mysqli_fetch_assoc(runQuery("SELECT * FROM Invoice WHERE Invoice_ID = '$Invoice_ID'"));
                                $Invoice_Num = $row1["Invoice_Num"];
                                $Customer_ID = $row1["Customer_ID"];
                                $Customer_Info = mysqli_fetch_assoc(runQuery("SELECT * FROM Customer WHERE Customer_ID='$Customer_ID'"));
                                $Customer_Name = $Customer_Info['Name'];
                                $Company_Name = $Customer_Info['Company_Name'];
                                $str .= "<tr>";
                                $str .= "<td>".$Invoice_Num."</td><td>".$Customer_Name."-".$Company_Name."</td><td><label>".$row["Tracking_ID"]. "</label></td>";
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
                    <div class="date-range">
                        <a href="./tracking.php?Courier=UPS"><input type="button" value="End Of The Day" style="border-radius:20px;"></a>
                    </div>
                </div>
                <div class="info">
                    <table>
                        <tr>
                            <th>Invoice Number</th><th>Customer_Name</th><th>Tracking Number</th>
                        </tr>
                        <?php 
                            $str = '';
                            $result = runQuery("SELECT * FROM Tracking WHERE Courier='UPS' AND Status='0' ORDER BY Create_Time DESC");
                            $Count = $result->num_rows;
                            while($row = mysqli_fetch_assoc($result)) {
                                $Invoice_ID = $row["Invoice_ID"];
                                $row1 = mysqli_fetch_assoc(runQuery("SELECT * FROM Invoice WHERE Invoice_ID = '$Invoice_ID'"));
                                $Invoice_Num = $row1["Invoice_Num"];
                                $Customer_ID = $row1["Customer_ID"];
                                $Customer_Info = mysqli_fetch_assoc(runQuery("SELECT * FROM Customer WHERE Customer_ID='$Customer_ID'"));
                                $Customer_Name = $Customer_Info['Name'];
                                $Company_Name = $Customer_Info['Company_Name'];
                                $str .= "<tr>";
                                $str .= "<td>".$Invoice_Num."</td><td>".$Customer_Name."-".$Company_Name."</td><td><label>".$row["Tracking_ID"]. "</label></td>";
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
                    <div class="date-range">
                        <a href="./tracking.php?Courier=Canpar"><input type="button" value="End Of The Day" style="border-radius:20px;"></a>
                    </div>
                </div>
                <div class="info">
                    <table>
                        <tr>
                            <th>Invoice Number</th><th>Customer_Name</th><th>Tracking Number</th>
                        </tr>
                        <?php 
                            $str = '';
                            $result = runQuery("SELECT * FROM Tracking WHERE Courier='Canpar' AND Status='0' ORDER BY Create_Time DESC");
                            $Count = $result->num_rows;
                            while($row = mysqli_fetch_assoc($result)) {
                                $Invoice_ID = $row["Invoice_ID"];
                                $row1 = mysqli_fetch_assoc(runQuery("SELECT * FROM Invoice WHERE Invoice_ID = '$Invoice_ID'"));
                                $Invoice_Num = $row1["Invoice_Num"];
                                $Customer_ID = $row1["Customer_ID"];
                                $Customer_Info = mysqli_fetch_assoc(runQuery("SELECT * FROM Customer WHERE Customer_ID='$Customer_ID'"));
                                $Customer_Name = $Customer_Info['Name'];
                                $Company_Name = $Customer_Info['Company_Name'];
                                $str .= "<tr>";
                                $str .= "<td>".$Invoice_Num."</td><td>".$Customer_Name."-".$Company_Name."</td><td><label>".$row["Tracking_ID"]. "</label></td>";
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
        feather.replace();
        window.onload = function() {
            var dropdown = document.querySelectorAll(".drop-content");
            for (let i = 0; i < dropdown.length; i++) {
                dropdown[i].style.display = "none";
            }
            document.getElementById("tracking_company").focus();
            document.getElementById("invoiceStatus_go").addEventListener("click", submitInvoiceStatus, false);
        }
        function invoiceSpliter(str) {
            var invoices = str.split("} ");
            if(!invoices[1])
                return;
            var temp = invoices[0].split("{");
            var invoiceNumber = temp[1];

            document.getElementById("invoice_num").value = invoiceNumber;
        }
        function addNewTrackingNum(count){
            count = parseInt(count);
            var invoice_num = document.getElementById('invoice_num').value;
            var tracking_company = document.getElementById('tracking_company').value;

            var tracking_num_1 = document.getElementById('tracking').value;

            var str = '<tr>';
            str += '    <td>Taken From :</td>';
            str += '    <td><select name="tracking_company" id="tracking_company">';
            str += '        <option value="Canada_Post">Canada Post</option>';
            str += '        <option value="Canpar">Canpar</option>';
            str += '        <option value="UPS">UPS</option>';
            str += '        <option value="Other">Other</option>';
            str += '    </select></td>';
            str += '    <td>Invoice Number :</td>';
            str += '    <td><input type="text" value="'+invoice_num+'" name="invoice_num" id="invoice_num" onchange="invoiceSpliter(this.value)"></td>';
            str += '</tr>';
            str += '<tr>';
            str += '    <td>Tracking Number 1 :</td>';
            str += '    <td><input type="text" id="tracking_1" value="'+tracking_num_1+'" name="tracking_num_1"></td>';
            str += '    <td>Boxes :</td>';
            str += '    <td><input type="number" value="'+count+'" name="Boxes" tabindex="-1" onchange="addNewTrackingNum(this.value)"></td>';
            str += '</tr>';

            if(count > 10) count = 10;
            for (let i = 1; i < count; i++) {
                var temp_count = i + 1;
                str += '<tr><td>Tracking Number ' + temp_count + ' :</td><td><input type="text" id="tracking_' + temp_count + '" name="tracking_num_'+ temp_count +'"></td></tr>';
            }
            document.getElementById('add_here_table').innerHTML = str;

            document.getElementById('tracking_company').value = tracking_company;
            document.getElementById('tracking_2').focus();
            // document.getElementById('invoice_num').value = invoice_num;
            // document.getElementById('tracking').value = tracking_num_1;
        }
    </script>
</body>
</html>