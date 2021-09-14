<?php
    include "DBConnection.php";
    if($_GET['search'] != "" && $_GET['search'] != " "){
        $search = $_GET['search'];
    }
    else
        $search = $_POST["search"];

    $DATA = array();
    if ($search) {
        $search = htmlspecialchars($search);
        
        $Item_Code = array();
        $Product_Name = array();
        $Vendor1 = array();
        $Vendor2 = array();
        $Total_Sold = array();
        $Stock = array();
        $Reorder = array();
        $Zone = array();

        //Vendor - START
            $query = "SELECT * FROM Vendor WHERE Name LIKE '$search%'";
            $result = runQuery($query);
            //echo "<br/>query = $query <br/>";
            //print_r($result);
            if($result->num_rows > 0){
                $row = mysqli_fetch_assoc($result);
                //print_r($row);
                $Vendor_Name = $row['Name'];
                //echo "Vendor_Name = $Vendor_Name";
                mysqli_free_result($result);
                //B_Vendor_Product - START
                    $query = "SELECT * FROM B_Vendor_Product WHERE Vendor_ID='".$row['Vendor_ID']."'";
                    $i = 0;
                    $result = runQuery($query);

                    //print_r($result);

                    if($result->num_rows > 0){
                        while ($row = mysqli_fetch_assoc($result)) {
                            //Vendor_Preference - START
                                if($row['Preference'] == 'A'){
                                    $Vendor1[$i] = $Vendor_Name;

                                    $query = "SELECT * FROM B_Vendor_Product WHERE Product_ID='".$row['Product_ID']."' AND Preference='B'";
                                    $result_preference = runQuery($query);
                                    if($result_preference->num_rows > 0){
                                        $temp_row = mysqli_fetch_assoc($result_preference);
                                        mysqli_free_result($result_preference);
                                        $query = "SELECT Name FROM Vendor WHERE Vendor_ID='".$temp_row['Vendor_ID']."'";
                                        $Vendor2[$i] = mysqli_fetch_assoc(runQuery($query))['Name'];
                                    }
                                    else{
                                        $Vendor2[$i] = "-";
                                    }
                                }
                                else if($row['Preference'] == 'B'){
                                    $Vendor2[$i] = $row['Name'];

                                    $query = "SELECT * FROM B_Vendor_Product WHERE Product_ID='".$row['Product_ID']."' AND Preference='A'";
                                    $result_preference = runQuery($query);
                                    if($result_preference->num_rows > 0){
                                        $temp_row = mysqli_fetch_assoc($result_preference);
                                        mysqli_free_result($temp_result);
                                        $query = "SELECT Name FROM Vendor WHERE Vendor_ID='".$temp_row['Vendor_ID']."'";
                                        $Vendor1[$i] = mysqli_fetch_assoc(runQuery($query))['Name'];
                                    }
                                }
                            //Vendor_Preference - END

                            $query = "SELECT * FROM Product WHERE Product_ID='".$row['Product_ID']."' ORDER BY Zone ASC";
                            $resultP = runQuery($query);
                            if($resultP->num_rows > 0){
                                $rowP = mysqli_fetch_assoc($resultP);

                                $Item_Code[$i] = $rowP["Item_Code"];
                                $Product_Name[$i] = $rowP["Name"];
                                $Zone[$i] = $rowP["Zone"];

                                $query = "SELECT * FROM Barcode WHERE Product_ID='".$rowP['Product_ID']."' ORDER BY Product_ID ASC, Type ASC";
                                $resultB = runQuery($query);
                                if($resultB->num_rows > 0){
                                    $temp_Sold = array();
                                    $temp_Production = array();
                                    $temp_Adjustment = array();
                                    $temp_Reorder = array();
                                    $j = 0;
                                    while ($rowB = mysqli_fetch_assoc($resultB)) {
                                        $temp_Sold[$j] = (float)$rowB["Total_Sold"];
                                        $temp_Adjustment[$j] = (float)$rowB["Adjustment"];
                                        $temp_Production[$j] = (float)$rowB["Total_Production"];
                                        $temp_Reorder[$j] = (float)$rowB["Mini_Reorder"];
                                        $j++;
                                    }
                                    $Stock1 = $temp_Production[0] -$temp_Sold[0] + $temp_Adjustment[0];
                                    $Stock2 = $temp_Production[1] -$temp_Sold[1] + $temp_Adjustment[1];
                                    $Stock[$i] = $Stock1 + ($Stock2/4);
                                    $Reorder[$i] = $temp_Reorder[0] + ($temp_Reorder[1]/4);
                                    $Total_Sold[$i] = $temp_Sold[0] + $temp_Sold[1];
                                }
                            }
                            $i++;
                        }
                    }
                //B_Vendor_Product - END
            }
            //Vendor - END
            else{
                mysqli_free_result($result);
                $query = "SELECT * FROM Product WHERE Name LIKE '%$search%'";
                $result = runQuery($query);
        
                if($result->num_rows == 0){
                    mysqli_free_result($result);
                    $query = "SELECT * FROM Product WHERE Item_Code LIKE '%$search%'";
                    $result = runQuery($query);
                }
                
                if($result->num_rows > 0){

                }
            }
        array_push($DATA, $Item_Code);              //0 
        array_push($DATA, $Product_Name);           //Item_Code - 1    
        array_push($DATA, $Vendor1);                //Item_Code - 2
        array_push($DATA, $Vendor2);                //Item_Code - 3
        array_push($DATA, $Total_Sold);             //Item_Code - 4
        array_push($DATA, $Stock);                  //Item_Code - 5
        array_push($DATA, $Reorder);                  //Item_Code - 6
        array_push($DATA, $Zone);                   //Item_Code - 7
    }
    //$json_OBJ = json_encode($DATA);

    // $url = 'http://ttparikh.club/vendor.php';

    // // use key 'http' even if you send the request to https://...
    // $options = array(
    //     'http' => array(
    //         'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
    //         'method'  => 'POST',
    //         'content' => http_build_query($DATA)
    //     )
    // );
    // $context  = stream_context_create($options);
    // $result = file_get_contents($url, false, $context);
    // if ($result === FALSE) { /* Handle error */ }

    // var_dump($result);
    
    header("Location: ../vendor.php?DATA=$json_OBJ");
?>
<html>
<head>
    <script type="text/javascript">
        var json_str = '<?php Print($json_data)?>';
        var json_arr = JSON.parse(json_str);
        console.log(json_arr);
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | TTParikh</title>

    <!-- CSS File Link -->
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../iconStyle.css">
    <script src="../js/allJavaScripts.js"></script>
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
            grid-column: 1 / 9;
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
        .item2 th{
            background-color: var(--green-theme);
            color: var(--purple-theme);
            padding: 15px;
        }
        .item2 a{
            text-decoration: underline;
        }

            #text-area td:nth-child(1){
                width: 200px;
            }
            #text-area td:nth-child(2){
                width: 550px;
            }
            #text-area td:nth-child(3){
                width: 300px;
            }
            #text-area td:nth-child(4){
                width: 400px;
            }
            #text-area td:nth-child(5){
                width: 100px;
            }
            #text-area td:nth-child(6){
                width: 100px;
            }
            #text-area td:nth-child(7){
                width: 100px;
            }
            #text-area td:nth-child(8){
                width: 100px;
            }

            #text-area thead th:nth-child(1){
                width: 200px;
            }
            #text-area thead th:nth-child(2){
                width: 600px;
            }
            #text-area thead th:nth-child(3){
                width: 400px;
            }
            #text-area thead th:nth-child(4){
                width: 400px;
            }
            #text-area thead th:nth-child(5){
                width: 100px;
            }
            #text-area thead th:nth-child(6){
                width: 100px;
            }
            #text-area thead th:nth-child(7){
                width: 100px;
            }
            #text-area thead th:nth-child(8){
                width: 100px;
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
        #text-area td:nth-child(8){
            border-right: none;
        }
        #text-area td{
            border-right: 1px solid var(--green-theme);
        }
        /* .item2 thead{
            background-color:var(--green-theme)
        } */
        .item2 .inside *{
            padding-bottom: 20px;
        }
        .item1 select, .item1 option{
            width: 500px;
            height: 30px;
            background-color: var(--purple-theme);
            border: 1px solid var(--green-theme);
            text-align:center;
        }
        .item1 select option{
            background: var(--purple-theme);
        }

        #suggestion{
            position:absolute;
            background-color:var(--purple-theme);
        }
        #suggestion a{
            padding-top: 50px;
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
            <a href='invoiceReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Invoice Report</span></div></a>
            <a href='productReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Product Report</span></div></a>
            <a href='customerReport.php'><div class="menu drop-content"><span class="icon-gift"></span><span>Customer Report</span></div></a>
        </div>
        <a href='production.php'><div class="menu"><span class="icon-gift"></span><span>Production</span></div></a>
        <a href='vendor.php'><div class="menu"><span class="icon-basket"></span><span>Vendor</span></div></a>
        <a href='customer.php'><div class="menu"><span class="icon-profile-male"></span><span>Customer</span></div></a>
        <a href='products.php'><div class="menu"><span class="icon-beaker"></span><span>Products</span></div></a>
        <a href='tracking.php'><div class="menu"><span class="icon-map"></span><span>Tracking Number</div></a>
        <a href='settings.php'><div class="menu"><span class="icon-gears"></span><span>Settings</span></div></a>
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
                
                    <span>Vendor Information</span>
                </div>
                <div class="inside">
                    <form action="php/vendorInfoSearch.php" method="post" enctype="multipart/form-data" id="myForm">
                    <table>
                        <tr>
                            <td><input type="text" size="70" autocomplete="off" id="search" name="search" style="text-transform:uppercase;" onkeyup="searchResult(this.value)"></td>
                            <td><input type="submit" value="Search"></td>
                        </tr>
                        <tr><td><div id="suggestion"></div></td></tr>
                    </table>
                    </form>
                </div>
            </div>

            <div class="grid item2 details">
                <table id="text-area">
                <thead>
                    <tr>
                        <th><label for="">Item Code</label></th>
                        <th><label for="">Product Name</label></th>
                        <th><label for="">Vendor 1</label></th>
                        <th><label for="">Vendor 2</label></th>
                        <th><label for="">Total Sold</label></th>
                        <th><label for="">Stock</label></th>
                        <th><label for="">Reorder</label></th>
                        <th><label for="">Zone</label></th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i=0; $i < count($DATA[1]); $i++) { 
                        echo "<tr>";
                        echo "<td><input type='text' value='".$DATA[0][$i]."'></td>";
                        echo "<td><input type='text' value='".$DATA[1][$i]."'></td>";
                        echo "<td><input type='text' value='".$DATA[2][$i]."'></td>";
                        echo "<td><input type='text' value='".$DATA[3][$i]."'></td>";
                        echo "<td><input type='text' value='".$DATA[4][$i]."'></td>";
                        echo "<td><input type='text' value='".$DATA[5][$i]."'></td>";
                        echo "<td><input type='text' value='".$DATA[6][$i]."'></td>";
                        echo "<td><input type='text' value='".$DATA[7][$i]."'></td>";
                        echo "</tr>";
                    }?>
                </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        feather.replace();
        var dropdown = document.querySelectorAll(".drop-content");
        for (let i = 0; i < dropdown.length; i++) {
            dropdown[i].style.display = "none";
        }
    </script>
</body>
</html>