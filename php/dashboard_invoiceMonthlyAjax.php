<?php
    include 'DBConnection.php';

    $modifiedDate = date('Y-m-01 00:00:00');

    $query = "SELECT MONTH(Create_Time) AS 'MonthCount', sum(MONTH(Create_Time))
    ";
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