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
    if($err != ""){
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
        <title>Temperature Records | TTParikh</title>

        <!-- CSS File Link -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/iconStyle.css">

        <!-- JavaScript And Jquery Link -->
        <script src="js/allJavaScripts.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
        <script src="https://unpkg.com/feather-icons"></script>

        <style>
            .item2 {
                grid-column: 1 / 5;
                grid-row: 1 / 5;
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
                margin-top: 10px;
                font-size: 20px;
            }
            .item2 td, .item3 td{
                padding: 15px;
            }
            .item2 th, .item3 th{
                background-color: var(--green-theme);
                color: var(--purple-theme);
                padding: 15px;
            }
            .item2 a{
                text-decoration: underline;
            }
            
            .item3 {
                grid-column: 5 / 9;
                grid-row: 1 / 5;
            }
            .item3 .invoice-details{
                width: 100%;
            }
            .item3 table{
                width: 100%;
                font-size: 20px;
            }
            .item3 .details table{
                text-align: center;
            }
            .item3 .details table *{
                padding: 15px;
            }
            .item3 .invoice-details div{
                padding: 20px;
            }
            .item3 .top-info, date-range{
                display:flex;
                justify-content: space-between;
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
                <a href='pestRecord.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Pest Record</span></div></a>
                <a href='temperatureRecord.php'><div class="menu drop-content highlight"><span class="icon-gift"></span><span>Temperature Record</span></div></a>
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
                <div class="grid item2">
                    <div class="inside">
                        <span>Entry Feed</span>
                    </div>
                    <div class="details">
                        <form id='form_id' action="" method="post" enctype="multipart/form-data">

                            <table>
                                <tr>
                                    <td><label for="Emp_Name">Employer Name: </label></td><td><input type="text" name="Emp_Name" id="Emp_Name"></td>
                                </tr>
                                <tr>
                                    <td><label for="Tempreture">Tempreture :</label></td><td><input type="text" name="Tempreture"/></td> 
                                </tr>
                                <tr>
                                    <td><label for="Humidity">Humidity :</label></td><td><input type="text" name="Humidity"></td>
                                </tr>
                            </table>
                            <input type="submit" name="submit" value="Submit" id="submit_btn">
                        </form>
                    </div>
                </div>
                <div class="grid item3">
                    <form action="" method="post" enctype="multipart/form-data">
                    <div class="top-info">
                        <div class="inside"><span>Recent Entries :</span></div>
                        <div class="date-range">
                            <label for="Emp_Name">Employer Name: </label><input type="text" name="Emp_Name" id="Emp_Name">
                        </div>
                    </div>
                    <div class="info">
                        <?php
                            $query = "SELECT * FROM Temp_Record ORDER BY Create_Time LIMIT 5";
                        ?>
                        <table>
                            <tr><th>Date</th><th>Temperature</th><th>Humidity</th><th>Emp Name</th></tr>
                            <tr><td>12th March, 2021</td><td>21&deg;C</td><td>40%</td><td>Saad Saiyed</td></tr>
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