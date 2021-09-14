<?php
    include "php/DBConnection.php";
    checkIfLoggedIn(1, 2, 3, 4);
    $err = "";
    if(isset($_POST["submit"]) && isset($_POST["Emp_Name"])){
        for($i = 1; $i <= 12; $i++){
            $Question = addslashes($_POST["Question_$i"]);
            $Check = intval(isset($_POST["Check_$i"]));
            $Emp_Name = $_POST["Emp_Name"];
            runQuery("INSERT INTO Pest_Record (`Question`, `Answer`, `Emp_Name`) VALUE ('$Question', '$Check', '$Emp_Name')");
            $err = "Record Submited Successfully.";
        }
    }
    if($err != ""){ /*This part was tacken from W3Schools.com*/
        $err = ucwords($err);?>
        <div id="snackbar"><?=$err?></div>
        <script>
            var x = document.getElementById("snackbar");
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 10000);
        </script>
    <?}
?>
<html lang="en" onclick="removeSuggestion()">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pest Records | TTParikh</title>

        <!-- CSS File Link -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/iconStyle.css">

        <!-- JavaScript And Jquery Link -->
        <script src="js/allJavaScripts.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
        <script src="https://unpkg.com/feather-icons"></script>

        <style>
            .item3 {
                grid-column: 1 / 5;
                grid-row: 1 / 11;
            }
            .item3 .top-info, date-range{
                display:flex;
                justify-content: space-between;
            }
            .item3 input[type=text]{
                width: auto;
                background-color: var(--purple-theme);
                border: 1px solid var(--green-theme);
                font-size: 14px;
                line-height: 17px;
            }
            .item3 .date-range input[type=button]{
                width: auto;
                line-height: 22px;
            }
            .item3 .date-range label{
                margin: 10px;
            }
            .item3 table{
                width: 100%;
                text-align: center;
                margin-top: 20px;
                font-size: 20px;
            }
            .item3 td{
                padding: 20px;
            }
            .item3 td:nth-child(2){
                padding: 20px;
            }
            .item3 th{
                background-color: var(--green-theme);
                color: var(--purple-theme);
                padding: 15px;
                border-radius: 5px;
            }
            .item3 a{
                text-decoration: underline;
            }
            .item3 .info tr:hover{
                color: var(--purple-theme);
                background-color: var(--green-theme);
                cursor: pointer;
            }
            #submit_btn{
                margin-top:30px;
                border-radius: 15px 15px 15px 15px;
            }
            #submit_btn:hover{
                border: 1px solid var(--green-theme);
                background-color: var(--purple-theme);
                color: var(--font-color);
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
            <a onclick="dropdownToggle()"><div class="menu dropdown-btn"><span class="icon-presentation"></span><span>Record</span><span><i data-feather="chevron-down"></i></span></div></a>
            <div class="side-drop" id="side-drop-id">
                <a href='pestRecord.php'><div class="menu drop-content highlight"><span class="icon-gift"></span><span>Pest Record</span></div></a>
                <a href='temperatureRecord.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Temperature Record</span></div></a>
                <a href='sampleRecord.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Sample Record</span></div></a>
                <a href='calibrationRecord.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Calibration Record</span></div></a>
                <a href='maintenanceRecord.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Maintenance Record</span></div></a>
                <a href='complainRecord.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Complaint Record</span></div></a>
                <a href='recallRecord.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Recall Record</span></div></a>
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
                <div class="grid item3">
                    <div class="inside">
                        <span>Brief Summary :</span>
                    </div>
                    <div class="details">
                        <table id="Extraction_Table">
                            <tr><th>Total Quantity</th><th>Date / Time</th><th>Type</th></tr>
                        </table>
                    </div>
                </div>
                <div class="grid item3">
                    <form action="" method="post" enctype="multipart/form-data">
                    <div class="top-info">
                        <div class="inside"><span>Questions :</span></div>
                    </div>
                    <div class="info">
                        <table>
                            <tr>
                                <th>Questions</th><th>Yes / No</th>
                            </tr>
                            <tr>
                                <td><input type="hidden" name="Question_1" value="Are windows and doors sealed tightly to prevent entry of pests?"/>Are windows and doors sealed tightly to prevent entry of pests?</label></td> 
                                <td><input type="checkbox" name="Check_1" id="Check_1"></td>
                            </tr>
                            <tr>
                                <td><input type="hidden" name="Question_2" value="Do all windows have screen in good repair to keep out of insects?">Do all windows have screen in good repair to keep out of insects?</label></td> 
                                <td><input type="checkbox" name="Check_2" id="Check_2"></td>
                            </tr>
                            <tr>
                                <td><input type="hidden" name="Question_3" value="Are drains appropriately cleaned and free build-up possibly acting as an attranctant to rodents and other pests?">Are drains appropriately cleaned and free build-up possibly acting as an attranctant to rodents and other pests?</label></td> 
                                <td><input type="checkbox" name="Check_3" id="Check_3"></td>
                            </tr>
                            <tr>
                                <td><input type="hidden" name="Question_4" value="Is there sufficient clearance space (approxi. 1/2' between walls and equipment to inhibit rodent activity?">Is there sufficient clearance space (approxi. 1/2' between walls and equipment to inhibit rodent activity?</label></td> 
                                <td><input type="checkbox" name="Check_4" id="Check_4"></td>
                            </tr>
                            <tr>
                                <td><input type="hidden" name="Question_5" value="Are drains covers in good repair and properly fitted?">Are drains covers in good repair and properly fitted?</label></td> 
                                <td><input type="checkbox" name="Check_5" id="Check_5"></td>
                            </tr>
                            <tr>
                                <td><input type="hidden" name="Question_6" value="Is trash, debris, and clutter removed eliminating harbourage for pests?">Is trash, debris, and clutter removed eliminating harbourage for pests?</label></td> 
                                <td><input type="checkbox" name="Check_6" id="Check_6"></td>
                            </tr>
                            <tr>
                                <td><input type="hidden" name="Question_7" value="Are personnel change rooms and break rooms cleaned and sanitized appropriately to inhibit the attraction of roedent and other pest?">Are personnel change rooms and break rooms cleaned and sanitized appropriately to inhibit the attraction of roedent and other pest?</label></td> 
                                <td><input type="checkbox" name="Check_7" id="Check_7"></td>
                            </tr>
                            <tr>
                                <td><input type="hidden" name="Question_8" value="Are there signs of rodent, insect or bird habitation (e.g. droppings, hair, feathers, gnaw marks, urine/ammonia odours)">Are there signs of rodent, insect or bird habitation (e.g. droppings, hair, feathers, gnaw marks, urine/ammonia odours)?</label></td> 
                                <td><input type="checkbox" name="Check_8" id="Check_8"></td>
                            </tr>
                            <tr>
                                <td><input type="hidden" name="Question_9" value="Are traps sufficient in number, well maintained and good repair?">Are traps sufficient in number, well maintained and good repair?</label></td> 
                                <td><input type="checkbox" name="Check_9" id="Check_9"></td>
                            </tr>
                            <tr>
                                <td><input type="hidden" name="Question_10" value="Is waste material properly collected, stored an disposed of in order to inhibit the attraction of rodents and pests?">Is waste material properly collected, stored an disposed of in order to inhibit the attraction of rodents and pests?</label></td> 
                                <td><input type="checkbox" name="Check_10" id="Check_10"></td>
                            </tr>
                            <tr>
                                <td><input type="hidden" name="Question_11" value="Are waste receptacles, bins and/ or dumpsters properly cleaned and sanitized, to inhibit the attraction of rodents and other pests?">Are waste receptacles, bins and/ or dumpsters properly cleaned and sanitized, to inhibit the attraction of rodents and other pests?</label></td> 
                                <td><input type="checkbox" name="Check_11" id="Check_11"></td>
                            </tr>
                            <tr>
                                <td><input type="hidden" name="Question_12" value="Have prior indicators of pest harbourage been corrected and sanitized appropriately in order to note any new or continued activity?">Have prior indicators of pest harbourage been corrected and sanitized appropriately in order to note any new or continued activity?</label></td> 
                                <td><input type="checkbox" name="Check_12" id="Check_12"></td>
                            </tr>
                        </table>
                        <input type="submit" name="submit" value="Submit" id="submit_btn">
                    </div>
                    </form>
                </div>
            </div>
        </div>
        
        <script>
            window.onload = function() {
                feather.replace();
                document.getElementById("invoiceStatus_go").addEventListener("click", submitInvoiceStatus, false);
            };
        </script>
    </body>
</html>