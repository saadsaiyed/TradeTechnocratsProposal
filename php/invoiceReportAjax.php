 <?php
    include "DBConnection.php";
    
    $search = $_GET["search"];
    $DATA = array();

    $query = "SELECT * FROM Invoice WHERE Invoice_Num LIKE '$search%' LIMIT 3";
    $result = runQuery($query);
    if($result->num_rows > 0){
        $Customer_Info = array();
        $Invoice_ID = array();
        $Invoice_Num = array();
        $Total_Count = array();
        $Create_Time = array();

        $i = 0;
        while ($row = mysqli_fetch_assoc($result)){
            $Invoice_ID[$i] = $row["Invoice_ID"];
            $Invoice_Num[$i] = $row["Invoice_Num"];
            $Total_Count[$i] = $row["Total_Count"];
            $Create_Time[$i] = time2str(strtotime($row["Create_Time"]));
            // $Create_Time[$i] = date('jS\, F Y \| h:i A \(l\)', (strtotime($row["Create_Time"])));
            $Customer_ID = $row["Customer_ID"];
            $tempRow = mysqli_fetch_assoc(runQuery("SELECT * FROM Customer WHERE Customer_ID='$Customer_ID'"));
            $Customer_Info[$i] = $tempRow["Name"] . " - " . $tempRow["Company_Name"];

            $i++;
        }

    }
    array_push($DATA, $Invoice_ID);
    array_push($DATA, $Invoice_Num);
    array_push($DATA, $Customer_Info);
    array_push($DATA, $Total_Count);
    array_push($DATA, $Create_Time);

    $mysqli->close();
    echo json_encode($DATA);
?>