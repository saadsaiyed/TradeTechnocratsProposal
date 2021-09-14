<?php
    include "php/DBConnection.php";
    checkIfLoggedIn(1, 2, 3, 4);

    if ($_GET["info"]) {
        $info = $_GET["info"];
        $to_show = "<script>alert('".$info."')</script>";
        echo $to_show;
    }

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Stock | TTParikh</title>

    <!-- CSS File Link -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/iconStyle.css">
    <script src="js/allJavaScripts.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>

    <style>
        input[type=date]{
            width: 100%;
            /* background-image: var(--purple-theme); */
            background-image: url('images/date.png');
            border: 1px solid var(--green-theme);
            font-size: 14px;
            line-height: 17px;
            height: 18px;
        }
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
                width: 300px;
            }
            #text-area td:nth-child(2){
                width: 600px;
            }
            #text-area td:nth-child(3){
                width: 400px;
            }
            #text-area td:nth-child(4){
                width: 400px;
            }
            #text-area td:nth-child(5){
                width: 300px;
            }
            #text-area td:nth-child(6){
                width: 200px;
            }
            #text-area td:nth-child(7){
                width: 200px;
            }

            #text-area thead th:nth-child(1){
                width: 300px;
            }
            #text-area thead th:nth-child(2){
                width: 675px;
            }
            #text-area thead th:nth-child(3){
                width: 400px;
            }
            #text-area thead th:nth-child(4){
                width: 400px;
            }
            #text-area thead th:nth-child(5){
                width: 300px;
            }
            #text-area thead th:nth-child(6){
                width: 200px;
            }
            #text-area thead th:nth-child(7){
                width: 200px;
            }

            #text-area thead{
                display: block;
            }
            #text-area  tbody{
                height: 440px;
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
        #text-area td:nth-child(7){
            border-right: none;
        }
        #text-area td{
            border-right: 1px solid var(--green-theme);
        }

        .item2 .inside table *{
            padding-bottom: 20px;
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
        <a href='warehouse.php'><div class="menu highlight"><span class="icon-gift"></span><span>Warehouse</span></div></a>
        <a href='adjustment.php'><div class="menu"><span class="icon-gears"></span><span>Adjustment</span></div></a>
    </div>

    <div class="root" id="root_Id">
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
            <div class="grid item2">
                <div class="inside">
                    <form action="php/warehouseFetch.php" method="post" enctype="multipart/form-data" id='myForm'>
                    <table>
                        <tr>
                            <td><label for="">Vendor Name :</label></td>
                            <td><input type="text" autocomplete="off"  name="Vendor_Name" id="Vendor_Name" onkeyup="vendorSearch(this.value)" style="width: 500;"></td>
                            <td><label for="">Invoice Number :</label></td>
                            <td><input type="text" name="invoice_num" id="invoice_num"></td>
                        </tr>
                        <tr>
                            <td><label for="">Link :</label></td>
                            <td><input type="url" name="invoice_link" id="invoice_link"></td>
                            <td><label for="">Container Arrival Date :</label></td>
                            <td><input type="date" name="Arrival_Date" id="Arrival_Date"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><label>Suggestion : </label></td>
                            <td><div id="product_suggestion" class="suggestion"></div></td>
                        </tr>
                    </table>
                </div>
                <div class="details">
                    <!-- Sorted Element - START -->
                        <div id="sorted_div">
                        </div>
                        <input type="button" id="sorted" value="Sort" style="width:100px;border-radius: 15px 15px 15px 15px;" onclick="sortNow()" tabindex='-1'>
                    <!-- Sorted Element - END -->

                    <table id="text-area">
                        <thead>
                            <tr>
                            <th><label for="">Item Code</label></th>
                            <th><label for="">Product Name</label></th>
                            <th><label for="">Botanical Name</label></th>
                            <th><label for="">Country Name</label></th>
                            <th><label for="">Lot Number</label></th>
                            <th><label for="">Total Weight</label></th>
                            <th><label for="">Bags</label></th>
                            <!-- Zaid Edit <th><label for="">Location</label></th>-->
                            </tr>
                        </thead>
                        <tbody id="addInside_tr_20">
                            <?php
                                $stringToDisplay = ''; 
                                for ($i=1; $i <= 200; $i++) { 
                                    $stringToDisplay .= '<tr>';
                                    $stringToDisplay .= '<td><input type="text" autocomplete="off" id="item_code_'.$i.'" name="item_code_'.$i.'">';
                                    $stringToDisplay .= '<td><input type="text" autocomplete="off" id="product_name_'.$i.'" name="product_name_'.$i.'" onkeyup="itemCodeOnChange(this.value, '.$i.')" >';
                                    $stringToDisplay .= '<td><input type="text" autocomplete="off" id="bot_name_'.$i.'" name="bot_name_'.$i.'" onkeyup="botOnChange(this.value, '.$i.')">';
                                    $stringToDisplay .= '<td><input type="text" autocomplete="off" id="country_name_'.$i.'" name="country_name_'.$i.'" >';
                                    $stringToDisplay .= '<td><input type="text" autocomplete="off" id="lot_num_'.$i.'" name="lot_num_'.$i.'" >';
                                    $stringToDisplay .= '<td><input type="text" autocomplete="off" id="weight_'.$i.'" name="weight_'.$i.'">';
                                    $stringToDisplay .= '<td><input type="text" autocomplete="off" id="count_'.$i.'" name="count_'.$i.'">';
                                    //Zaid Edit $stringToDisplay .= '<td><input type="text" autocomplete="off" id="location_'.$i.'" name="location_'.$i.'">';
                                    $stringToDisplay .= '<input type="hidden" id="product_id_'.$i.'" name="product_id_'.$i.'">';
                                    $stringToDisplay .= '</tr>';
                                }
                                echo $stringToDisplay;
                            ?>
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
        // window.onbeforeunload = function(){
        //     return 'Are you sure you want to leave?';
        // };
        window.onload = function() {
            feather.replace();
            var dropdown = document.querySelectorAll(".drop-content");
            for (let i = 0; i < dropdown.length; i++) {
                dropdown[i].style.display = "none";
            }
            document.getElementById("Vendor_Name").focus();
            document.getElementById("invoiceStatus_go").addEventListener("click", submitInvoiceStatus, false);

            (function ($) {
                $.fn.formNavigation = function () {
                    $(this).each(function () {
                        $(this).find('input').on('keydown', function(e) {
                            switch (e.which) {
                                case 39:
                                    $(this).closest('td').next().find('input').focus(); break;
                                case 37:
                                    $(this).closest('td').prev().find('input').focus(); break;
                                case 40:
                                    $(this).closest('tr').next().children().eq($(this).closest('td').index()).find('input').focus(); break;
                                case 38:
                                    $(this).closest('tr').prev().children().eq($(this).closest('td').index()).find('input').focus(); break;
                            }
                        });
                    });
                };
            })(jQuery);
            $('#addInside_tr_20').formNavigation();
        };
        function removeSuggestion() {
            if(!$("#product_suggestion").is(":focus")){
                document.getElementById("product_suggestion").innerHTML="";
                document.getElementById("product_suggestion").style.padding="0px";
                document.getElementById("product_suggestion").style.border="none";
            }
        }

        function itemCodeOnChange(str, count) {
            if (str.length==0) {
                document.getElementById("product_suggestion").innerHTML="";
                document.getElementById("product_suggestion").style.padding="0";
                document.getElementById("product_suggestion").style.border="none";
                // document.getElementById("product_suggestion").style.border="0px";
                return;
            }

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var results = JSON.parse(this.responseText);
                    var to_show = "";
                    for (var i = 0; i < results[0].length; i++){
                        to_show += "<div class='suggestion_i' onclick='insertValues(\"" + results[4][i] + "\", \"" + results[0][i] + "\", \"" + results[5][i] + "\"," + count + ")'>" + results[0][i] + " {" + results[4][i] + "} </div>";
                    }
                    document.getElementById("product_suggestion").innerHTML=to_show;
                    document.getElementById("product_suggestion").style.padding="20px";
                    document.getElementById("product_suggestion").style.border="1px solid var(--green-theme)";    
                }
            };
            xhttp.open("GET", "./php/settingsSearchAjax.php?search=" + str, true);
            xhttp.send();
        }
        function insertValues(item_code, product_name, product_id, count){
            document.getElementById("product_name_" + count).value=product_name + " {" + item_code + "}";
            document.getElementById("product_id_" + count).value=product_id;

            removeSuggestion();
            document.getElementById("bot_name_" + count).focus();
        }

        function vendorSearch(str) {
            if (str.length==0) {
                document.getElementById("product_suggestion").innerHTML="";
                document.getElementById("product_suggestion").style.padding="0";
                document.getElementById("product_suggestion").style.border="none";
                // document.getElementById("product_suggestion").style.border="0px";
                return;
            }
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp=new XMLHttpRequest();
            } else {  // code for IE6, IE5
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange=function() {
                if (this.readyState==4 && this.status==200) {
                    var to_show = "";
                    var results = JSON.parse(this.responseText)
                    if (results.length > 0){
                        for (var i = 0; i < results.length; i++)
                        {
                            to_show += "<div class='suggestion_i' onclick='insertValueVendor(\""+results[i]+"\")'>" + results[i] + "</div>";
                            // if(i != results.length-1)
                            //     to_show += "<br/>";
                        }

                        document.getElementById("product_suggestion").innerHTML=to_show;
                        document.getElementById("product_suggestion").style.padding="20px";
                        document.getElementById("product_suggestion").style.border="1px solid var(--green-theme)";    
                    }
                    else{
                        document.getElementById("product_suggestion").innerHTML="";
                        document.getElementById("product_suggestion").style.padding="0";
                        document.getElementById("product_suggestion").style.border="none";        
                    }
                    // document.getElementById("suggestion").style.border="1px solid #A5ACB2";
                }
            }
            xmlhttp.open("GET","php/productReportSearchAjax.php?search="+str ,true);
            xmlhttp.send();
        }
        function insertValueVendor(str) {
            document.getElementById("Vendor_Name").value=str;

            removeSuggestion();
            document.getElementById("invoice_num").focus();
        }
    </script>
    
</body>
</html>