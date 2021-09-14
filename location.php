<?php
    include "php/DBConnection.php";

    $Location_ID = $_POST['Location_ID'];
    if($Location_ID){
        $to_show = '<div id="snackbar">';
        runQuery("DELETE FROM B_Location_Barcode WHERE Location_ID='$Location_ID'");
        for ($i = 1; $i <= 40; $i++) {
            $Barcode_ID = $_POST['barcode_'.$i];
            if($Barcode_ID != ''){
                $Count = $_POST['count_'.$i];
                $query = "INSERT INTO B_Location_Barcode (`Location_ID`, `Barcode_ID`, `Count`) 
                VALUES('$Location_ID', '$Barcode_ID', '$Count')";
                if(runQuery($query)){
                    $check = true;
                }
            }
        }
        if($check)
            $to_show .= 'Successful</div>';
        else
        $to_show .= 'Failed</div>';
        
        echo $to_show;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location | TTParikh</title>

    <link rel="icon" href="images/favicon.gif">

    <!-- CSS File Link -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/iconStyle.css">
    <script src="js/allJavaScripts.js"></script>
    <script src="js/invoice_validation.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        var x = document.getElementById("snackbar");
        if(x){
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 10000);
        }
    </script>
    <style>
        .item1 {
            grid-column: 1 / 9;
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
            grid-column: 1 / 9;
            grid-row: 1 / 5;
        }
        .inside input[type=text]{
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
            margin-top: 20px;
            font-size: 20px;
        }
        .details table:nth-child(1){
            margin-top: -20px;
        }
        .details{
            margin-top: -20px;
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
                width: 1000px;
            }
            #text-area td:nth-child(2){
                width: 300px;
            }
            #text-area td:nth-child(3){
                width: 300px;
            }
            #text-area td:nth-child(4){
                width: 300px;
            }

            #text-area thead th:nth-child(1){
                width: 1000px;
            }
            #text-area thead th:nth-child(2){
                width: 300px;
            }
            #text-area thead th:nth-child(3){
                width: 300px;
            }
            #text-area thead th:nth-child(4){
                width: 300px;
            }

            #text-area thead{
            }
            #text-area  tbody{
                height: 440px;
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
        #text-area td:nth-child(5){
            border-right: none;
        }
        #text-area td{
            border-right: 1px solid var(--green-theme);
        }

        .item2 .inside table *{
            padding-bottom: 20px;
        }

        #topTableID{
            float: left;
        }
        #topTableID td{
            padding-left: 100px;
        }
        .suggestion{
            position:absolute;
            background-color:var(--purple-theme);
            font-size:smaller;
            cursor: pointer;
        }
        .suggestion_i:hover{
            color:var(--purple-theme);
            background-color:var(--green-theme);
        }
        .suggestion_i{
            cursor: pointer;
            text-align:center;
            text-justify:center;
            padding: 10px 10px -5px 10px;
        }
        #sorted_div{
            background-color: var(--purple-theme);
            /*position:absolute;*/
            top: 123px;
        }
        #sorted_div tr:nth-child(2n + 1){
            background:var(--lightpurple-theme)
        }
    </style>

</head>
<body onclick='removeSuggestion()'>

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
                <a href="#ContactUs" onclick=someFunc()><i data-feather="github"></i></a>
                <input type="color" name="" id="colorPicker2" style="width:auto; background-color:var(--green-theme);border:none;">
            </ul>
        </header>

        <div class="grid_container">
            <div class="grid item2">
                <div class="inside" id="topTableID">
                    <form action="" method="post" enctype="multipart/form-data" id='myForm' onkeypress="return event.keyCode != 13;">
                    <table>
                        <tr>
                            <td><label for="">Location :</label></td>
                            <td><input type="text" autocomplete="off" name="Location_ID" id="Location_ID" onkeyup="locationSearch(this.value)" style="width: 500;"></td>
                            <td><label>Product Suggestion : </label></td>
                            <td><div id="product_suggestion" class="suggestion"></div></td>
                        </tr>
                    </table>
                </div>
                <div class="details">
                    <table id="text-area">
                        <thead>
                            <tr>
                                <th><label>Product Name</label></th>
                                <th><label>Current Locations</label></th>
                                <th><label>Count</label></th>
                                <th><label>Stock</label></th>
                            </tr>
                        </thead>
                        <tbody id="addInside_tr_20">
                        <?php for ($i=1; $i<=40; $i++) { ?>
                            <tr>
                                <input type="hidden" name="barcode_<?=$i?>" id="barcode_<?=$i?>">
                                <td><input type="text" autocomplete="off" id="product_name_<?=$i?>" name="product_name_<?=$i?>" onkeyup="productSearch(this.value, <?=$i?>)" style="font-size:larger;"></td>
                                <td><input type="text" autocomplete="off" id="location_<?=$i?>" name="location_<?=$i?>" tabindex="-1" style="font-size:larger;"></td>
                                <td><input type="text" autocomplete="off" id="count_<?=$i?>" name="count_<?=$i?>" tabindex="-1" style="font-size:larger;"></td>
                                <td><input type="text" autocomplete="off" id="stock_<?=$i?>" name="stock_<?=$i?>" tabindex="-1" style="font-size:larger;"></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                    <table>
                        <tr>
                            <td><input type="reset" value="Reset" tabindex="-1"></td>
                            <td><input type="submit" value="Submit"></td>
                        </tr>
                    </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function(){
            feather.replace();
            var dropdown = document.querySelectorAll(".drop-content");
            for (let i = 0; i < dropdown.length; i++) {
                dropdown[i].style.display = "none";
            }
            document.getElementById("Location_ID").focus();
            document.getElementById("invoiceStatus_go").addEventListener("click", submitInvoiceStatus, false);
        }
        function barcodeOnChange(count) {
            var currentElement = document.getElementById("barcode_" + count);
            var location = document.getElementById("Location_ID").value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(this.responseText);
                    
                    var productElement = document.getElementById("product_name_" + count);
                    result[3] == "B" ? productElement.value = result[0] + " - 114g" : productElement.value = result[0];
                    
                    var locationElement = document.getElementById("location_" + count);
                    locationElement.value = result[1];
                    
                    var countElement = document.getElementById("count_" + count);
                    countElement.value = result[5];

                    var stockElement = document.getElementById("stock_" + count);
                    stockElement.value = result[4];

                    if(result[2] == 'Y'){
                        productElement.style.color = 'var(--yellow-theme)';
                        locationElement.style.color = 'var(--yellow-theme)';
                        countElement.style.color = 'var(--yellow-theme)';
                        stockElement.style.color = 'var(--yellow-theme)';
                    }
                    else if(result[2] == 'R'){
                        productElement.style.color = 'var(--red-theme)';
                        locationElement.style.color = 'var(--red-theme)';
                        countElement.style.color = 'var(--red-theme)';
                        stockElement.style.color = 'var(--red-theme)';
                    }
                    else{
                        productElement.style.color = 'var(--green-theme)';
                        locationElement.style.color = 'var(--green-theme)';
                        countElement.style.color = 'var(--green-theme)';
                        stockElement.style.color = 'var(--green-theme)';
                    }
                }
            };
            xhttp.open("GET", "../php/locationGetProductAjax.php?BarcodeID=" + currentElement.value + "&Location=" + location, true);
            xhttp.send();
        }

        function locationSearch(str) {
            var check = true;
            if(str == ''){
                document.getElementById("product_suggestion").innerHTML="";
                document.getElementById("product_suggestion").style.padding="0px";
                document.getElementById("product_suggestion").style.border="none";
                return;
            }
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(this.responseText);
                    check = false;
                    var to_show ='';
                    for(var i = 0; i < result[0].length; i++){
                        to_show += "<div class='suggestion_i' onclick='insertValueInLocation(\"" + result[0][i] + "\", \"" + addSlashes(JSON.stringify(result[1][i])) +"\")'><label>" + result[0][i] + "</label></div>";
                        if (i != result[0].length - 1) to_show += "<br/>";
                    }

                    document.getElementById("product_suggestion").innerHTML = to_show;
                    document.getElementById("product_suggestion").style.padding = "20px";
                    document.getElementById("product_suggestion").style.border = "1px solid var(--green-theme)";
                }
                
                if(check){
                    document.getElementById("product_suggestion").innerHTML = "";
                    document.getElementById("product_suggestion").style.padding = "0";
                    document.getElementById("product_suggestion").style.border = "none";
                }
            };
            xhttp.open("GET","../php/getLocationAjax.php?search=" + str,true);
            xhttp.send();
        }
        function insertValueInLocation(str, barcodeString) {
            document.getElementById("myForm").reset();
            document.getElementById("Location_ID").value = str;

            barcodeArray = JSON.parse(barcodeString);            
            for(var i = 1; i <= barcodeArray.length; i++){
                document.getElementById("barcode_" + i).value = barcodeArray[i-1];
                barcodeOnChange(i);
            }
        }

        function addSlashes(string) {
            return string.replace(/\\/g, '\\\\').
                replace(/\u0008/g, '\\b').
                replace(/\t/g, '\\t').
                replace(/\n/g, '\\n').
                replace(/\f/g, '\\f').
                replace(/\r/g, '\\r').
                replace(/'/g, '\\\'').
                replace(/"/g, '\\"');
        }
        function removeEntry(count){
            document.getElementById("location_"+count).value = '';
            document.getElementById("stock_"+count).value = '';
            document.getElementById("barcode_"+count).value = '';
        }
        function removeSuggestion() {
            if(!$("#product_suggestion").is(":focus")){
                document.getElementById("product_suggestion").innerHTML="";
                document.getElementById("product_suggestion").style.padding="0px";
                document.getElementById("product_suggestion").style.border="none";
            }
        }
    </script>
</body>
</html>