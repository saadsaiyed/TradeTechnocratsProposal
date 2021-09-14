<?php
    include 'DBConnection.php';

    $modifiedDate = date('Y-m-01 00:00:00');
    $Start = date('Y-m-d', $_GET["Start"]);
    $End = date('Y-m-d', $_GET["End"]);
    $Barcode1 = $_GET["Barcode1"];
    $Barcode2 = $_GET["Barcode2"];

    $Customer_Name = array();
    $Company_Name = array();
    $Invoice_ID = array();
    $Invoice_Num = array();
    $Total_Count = array();
    $Pickup = array();
    $Create_Time = array();

    $query = "SELECT * FROM B_Invoice_Barcode WHERE Barcode_ID = '$Barcode1' AND Create_Time BETWEEN '$Start' AND '$End'";
    $result = runQuery($query);
    if($result->num_rows > 0){
        while ($row = mysqli_fetch_assoc($result)) {
            $Invoice_ID = $row['Invoice_ID'];
            $Invoice_Row = mysqli_fetch_assoc(runQuery("SELECT * FROM Invoice AS I 
                                    JOIN Customer AS C
                                    On C.Cutomer_ID = I.Customer_ID
                                    WHERE B.Invoice_ID = '$Invoice_ID"));
            
            array_push($Customer_Name, $Invoice_Row["Name"]);
            array_push($Company_Name, $Invoice_Row["Company_Name"]);
            array_push($Invoice_ID, $Invoice_Row["Invoice_ID"]);
            array_push($Invoice_Num, $Invoice_Row["Invoice_Num"]);
            array_push($Total_Count, $Invoice_Row["Total_Count"]);
            array_push($Create_Time, $Invoice_Row["Create_Time"]);
        }
    }
    array_push($DATA, $Customer_Name);                  //Customer_Name - 9
    array_push($DATA, $Company_Name);                   //Company_Name - 10

    array_push($DATA, $Tracking_ID);                    //Tracking_ID - 11
    array_push($DATA, $Courier);                        //Courier - 12

    array_push($DATA, $Invoice_ID);                     //Invoice_ID - 13
    array_push($DATA, $Invoice_Num);                    //Invoice_Num - 14
    array_push($DATA, $Total_Count);                    //Total_Count - 15
    array_push($DATA, $Pickup);                         //Pickup - 16
    array_push($DATA, $Create_Time);                    //Create_Time - 17

    
    $query = "SELECT * FROM B_Invoice_Barcode WHERE Barcode_ID = '$Barcode2' AND Create_Time BETWEEN '$Start' AND '$End'";

    $Total_This_Month = runQuery("SELECT * FROM Invoice WHERE Create_Time >= '$modifiedDate' ORDER BY Create_Time DESC")->num_rows;
    $Last_Order_Num = mysqli_fetch_assoc(runQuery("SELECT Invoice_Num FROM Invoice ORDER BY Invoice_ID DESC LIMIT 1"))["Invoice_Num"];

    $todaysDate = date('Y-m-d') . ' 00:00:00';
    $Today_Order = runQuery("SELECT Invoice_Num FROM Invoice WHERE Create_Time >= '$todaysDate' ORDER BY Invoice_ID DESC")->num_rows;

    $data = array(
        "Total_This_Month" => $Total_This_Month,
        "Last_Order_Num" => $Last_Order_Num,
        "Today_Order" => $Today_Order
    );

    echo json_encode($data);
?>