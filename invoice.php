<?php
    include "php/DBConnection.php";
    checkIfLoggedIn(2, 3, 1, 4);

    if($_GET["info"]){
        $err = ucwords($_GET['info']);?>
        <div id="snackbar"><?=$err?></div>
        <script>
            var x = document.getElementById("snackbar");
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 10000);
        </script>
    <?}
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Invoice | TTParikh</title>
        <link rel="icon" href="images/favicon.gif">

        <!-- CSS File Link -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/iconStyle.css">
        <script src="js/allJavaScripts.js"></script>
        <script src="js/invoice_validation.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
        <script src="https://unpkg.com/feather-icons"></script>

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
                
                #text-area tbody {
                    height: 440px;
                    display: block;
                    overflow: auto;
                    width: 100%;
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
            /* The Modal (background) */
            .modal {
                display: none; /* Hidden by default */
                position: fixed; /* Stay in place */
                z-index: 1; /* Sit on top */
                padding-top: 300px; /* Location of the box */
                left: 0;
                top: 0;
                width: 100%; /* Full width */
                height: 100%; /* Full height */
                overflow: auto; /* Enable scroll if needed */
                background-color: rgb(0,0,0); /* Fallback color */
                background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            }

            /* Modal Content */
            .modal-content {
                background-color: var(--purple-theme);
                margin: auto;
                padding: 20px;
                border: 1px solid #888;
                width: 20%;
                height: 180px;
            }
            /* The Close Button */
            .close {
                color: #ffffff;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }
            .close:hover, .close:focus {
                color: #aaa;
                text-decoration: none;
                cursor: pointer;
            }
            .not-close{
                display: flex;
                justify-content: space-between;
                padding-right: 20px;
            }

            #sorted_div{
                
            }
            #sorted_div td:nth-child(1){
                width: 200px;
                text-align: right;
                padding-right: 10px;
            }
            #sorted_div td:nth-child(2){
                width: 200px;
                text-align: left;
                padding-left: 10px;
            }
            #sorted_div th:nth-child(1){
                width: 200px;
            }
            #sorted_div th:nth-child(2){
                width: 200px;
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
            <a href='index.php'><div class="menu "><span class="icon-clipboard"></span><span>Dashboard</span></div></a>
            <a href='invoice.php'><div class="menu highlight"><span class="icon-browser"></span><span>Invoice</span></div></a>
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
                    <div class="inside" id="topTableID">
                        <form action="php/invoiceFetch.php" method="post" enctype="multipart/form-data" id='myForm' onkeypress="return event.keyCode != 13;">
                        <table>
                            <tr>
                                <td><label for="">Total Quantity :</label></td><td><input type="text" name="total_quantity" id="total_quantity" style="font-size:larger;"></td>
                                <td><label for="">Total QB Price :</label></td><td><input type="text" name="qb_price" id="qb_price"></td>
                                <td><label for="">Total Online Price :</label></td><td><input type="text" name="online_price" id="online_price"></td>
                            </tr>
                            <tr>
                                <td><label for="">Invoice Number / Name :</label></td>
                                <td><input type="text" autocomplete="off" name="invoice_name" id="invoice_name" onchange="invoiceSpliter()" onkeyup="customerSearch(this.value)" style="width: 500;"></td>
                                <td><label for="">Invoice Number :</label></td>
                                <td><input type="text" name="invoice_num" id="invoice_num" onchange="duplicateInvoiceChecker(this.value)" tabindex="-1"></td><td><label>Product Suggestion : </label></td><td><div id="product_suggestion" class="suggestion"></div></td>
                            </tr>
                            <tr><td></td><td><div id="suggestion" class="suggestion"></div></td><td></td><td></td></tr>
                        </table>
                    </div>
                    <div class="details">
                        <!-- Sorted Element - START -->
                            <div id="sorted_div" style="">
                            </div>
                        <!-- Sorted Element - END -->
                        <table>
                            <tr>
                                <td><input type="button" id="sorted" value="Sort" style="width:100px;border-radius: 15px 15px 15px 15px;" onclick="sortNow()" tabindex='-1'></td>
                            </tr>
                        </table>
                        <table id="text-area">
                            <thead>
                                <tr>
                                    <th><label>Barcode</label></th>
                                    <th><label>Product Name</label></th>
                                    <th><label>Count</label></th>
                                    <th><label>Zone / Stock</label></th>
                                </tr>
                            </thead>
                            <tbody id="addInside_tr_20">
                                <?php
                                    $stringToDisplay = '';
                                        for ($i=1; $i <= 200; $i++) { 
                                            $stringToDisplay .= '<tr>';
                                            $stringToDisplay .= '<td><input type="text" autocomplete="off" id="barcode_'.$i.'" name="barcode_'.$i.'" onchange="barcodeOnChange('.$i.')" style="font-size:larger;"></td>';
                                            $stringToDisplay .= '<td><input type="text" autocomplete="off" id="product_name_'.$i.'" name="product_name_'.$i.'" onkeyup="productSearch(this.value, '.$i.')" tabindex="-1" style="font-size:larger;"></td>';
                                            $stringToDisplay .= '<td><input type="text" autocomplete="off" id="count_'.$i.'" name="count_'.$i.'" onfocus="this.value=\'\'" onchange="quantityDisplay()" tabindex="-1" style="font-size:larger;"></td>';
                                            $stringToDisplay .= '<td><input type="text" autocomplete="off" id="price_'.$i.'" name="price_'.$i.'" tabindex="-1" style="font-size:larger;"></td>';
                                            $stringToDisplay .= '</tr>';
                                        }
                                    echo $stringToDisplay;
                                ?>
                            </tbody>
                        </table>
                        <table>
                            <tr>
                                <td><input type="reset" value="Reset" tabindex="-1"></td>
                                <td><input type="button" onclick="document.getElementById('myModal').style.display = 'block';" value="Submit"></td>
                            </tr>
                        </table>
                        <!-- Modal content - START -->
                            <div id="myModal" class="modal">
                                <div class="modal-content">
                                    <span class="close" onclick="document.getElementById('myModal').style.display = 'none';">&times;</span>
                                    <div class="not-close">
                                        <div>
                                            <label for="">Ready for Pickup</label>
                                            <input type="checkbox" style="zoom:2;" name="Status">
                                        </div>
                                        <div>
                                            <label for="">Pickedup</label>
                                            <input type="checkbox" style="zoom:2;" name="Status_PickedUp">
                                        </div>
                                    </div>
                                    <input type="submit" value="Submit" style="border-radius: 15px 15px 15px 15px; margin-top:40px;">
                                </div>
                            </div>
                        <!-- Modal content - END -->
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            feather.replace();
            window.onbeforeunload = function(){
                return 'Are you sure you want to leave?';
            };
            window.onload = function() {
                var dropdown = document.querySelectorAll(".drop-content");
                for (let i = 0; i < dropdown.length; i++) {
                    dropdown[i].style.display = "none";
                }
                document.getElementById("invoiceStatus_go").addEventListener("click", submitInvoiceStatus, false);

                document.getElementById("invoice_name").focus();
                // document.getElementById("myForm").addEventListener("submit", formValidation, false);
                // document.getElementById("myForm").addEventListener("reset", resetForm, false);

                // Form Navigation - START
                    (function ($) {
                        $.fn.formNavigation = function () {
                            $(this).each(function () {
                                $(this).find('input').on('keyup', function(e) {
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
                    $('#text-area').formNavigation();
                // Form Navigation - END
            };
            function removeSuggestion() {
                if(!$("#product_name_1").is(":focus")){
                    document.getElementById("suggestion").innerHTML="";
                    document.getElementById("suggestion").style.padding="0px";
                    document.getElementById("suggestion").style.border="none";
                }
            }
            function duplicateInvoiceChecker(str) {
                str=str.toUpperCase();
                if (str.length==0) {
                    document.getElementById("suggestion").innerHTML="";
                    document.getElementById("suggestion").style.padding="0";
                    document.getElementById("suggestion").style.border="none";
                    // document.getElementById("suggestion").style.border="0px";
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
                            var json_result =  "{" + results[0] + "} " + results[1] + " : " + results[2] + " [" + results[3] + "]";
                            to_show = "<div>" + json_result + "</div>";
                        }
                        else
                            to_show = "No Result Found";
                        document.getElementById("suggestion").innerHTML = to_show;
                        document.getElementById("suggestion").style.padding="20px";
                        document.getElementById("suggestion").style.border="1px solid var(--green-theme)";
                        // document.getElementById("suggestion").style.border="1px solid #A5ACB2";
                    }
                }
                xmlhttp.open("GET","php/duplicateInvoiceAjax.php?search="+str ,true);
                xmlhttp.send();    
            }
        </script>
    </body>
</html>