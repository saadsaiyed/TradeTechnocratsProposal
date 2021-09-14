<?php
    include "php/DBConnection.php";

    $search = $_GET["search"];
    if($search){
        $query = "SELECT * FROM Product WHERE Name LIKE '$search%'";
        $result = runQuery($query);
        //$result = runPreparedQuery("SELECT * FROM Product WHERE Name LIKE ?)", array($search . "%"));

        /* if($result->num_rows == 0){
            //$search = mysql_real_escape_string($search);
            //echo "Here |" . $search . "|<br/>";
            $query = "SELECT * FROM Product WHERE Item_Code='$search'";
            echo $query . "<br/>";
            $result = runQuery($query);
        } */
        if($result->num_rows == 0){
            $query = "SELECT * FROM Product WHERE Product_ID LIKE'%$search%' LIMIT 1";
            $result = runQuery($query);    
        }
        if($result->num_rows == 0){
            $query = "SELECT * FROM Barcode WHERE Barcode_ID LIKE '%$search%' LIMIT 1";
            $result = runQuery($query);
            if($result->num_rows != 0){
                $row = mysqli_fetch_array($result);
                $Product_ID = $row['Product_ID'];
                $query = "SELECT * FROM Product WHERE Product_ID LIKE '%$Product_ID%' LIMIT 1";
                $result = runQuery($query);
            }
        }
        if($result->num_rows != 0){
            $row = mysqli_fetch_array($result);
            $Product_ID = $row['Product_ID'];
            $Name[] = $row['Name'];
            $Item_Code[] = $row['Item_Code'];
            $Tax_Code[] = $row['Tax_Code'];
            $Zone[] = $row['Zone'];
            $DATA = array();

            //Barcode - START
                $Barcode_ID = array();
                $Online_Price = array();
                $Bulk_Price = array();
                $Total_Production = array();
                $Total_Sold = array();
                $Adjustment = array();
                $Mini_Reorder = array();
                $Type = array();
                $Location_ID = array();

                $query = "SELECT * FROM `Barcode` WHERE `Product_ID`='$Product_ID' ORDER BY Product_ID ASC, Type ASC";
                $resultBarcode = runQuery($query);
                $i = 0;

                while ($rowBarcode = mysqli_fetch_array($resultBarcode)) {
                    $Barcode_ID[$i] = $rowBarcode["Barcode_ID"];
                    $Online_Price[$i] = $rowBarcode["Online_Price"];
                    $Bulk_Price[$i] = $rowBarcode["Bulk_Price"];
                    $Total_Production[$i] = $rowBarcode["Total_Production"];
                    $Total_Sold[$i] = $rowBarcode["Total_Sold"];
                    $Adjustment[$i] = $rowBarcode["Adjustment"];
                    $Mini_Reorder[$i] = $rowBarcode["Mini_Reorder"];
                    $Type[$i] = $rowBarcode["Type"];

                    //Location - START
                        $query = "SELECT * FROM B_Location_Barcode WHERE Barcode_ID='$Barcode_ID[$i]' ORDER BY Create_Time DESC";
                        $resultLocation = runQuery($query);
                        $tempLocation = "";
                        while ($rowLocation = mysqli_fetch_array($resultLocation)) {
                            $tempLocation .= $rowLocation["Location_ID"] . ", ";                                
                        }
                        mysqli_free_result($resultLocation);

                        $Location_ID[$i] = substr($tempLocation, 0, -2);
                    //Location - END
                    $i++;
                }
                $Ratio_Of_1lb_To_114g = $Mini_Reorder[0]/$Mini_Reorder[1];
                
                mysqli_free_result($resultBarcode);

            //Barcode - END

            //Warehouse - START
                $Warehouse_Stock = array();
                $query = "SELECT Weight FROM B_Warehouse_Product WHERE Product_ID='$Product_ID'";
                $result = runQuery($query);
                $i = 0;
                $Warehouse_Stock[0] = 0; //There can only be two Warehouse stocks and both 1lb and 0.25lb has same stock we just need 1 so there is [0]
                while ($result->num_rows > 0 && $row = mysqli_fetch_assoc($result)) {
                    $Warehouse_Stock[0] += (float) $row["Weight"];
                }
                mysqli_free_result($result);

                if($Warehouse_Stock[0] == 0){

                }
            //Warehouse - END
            
            array_push($DATA, $Barcode_ID);                     //Barcode_ID - 0
            array_push($DATA, $Online_Price);                   //Online_Price - 1
            array_push($DATA, $Bulk_Price);                     //Bulk_Price - 2
            array_push($DATA, $Total_Production);               //Total_Production - 3
            array_push($DATA, $Total_Sold);                     //Total_Sold - 4
            array_push($DATA, $Adjustment);                     //Adjustment - 5
            array_push($DATA, $Mini_Reorder);                   //Mini_Reorder - 6
            array_push($DATA, $Type);                           //Type - 7

            array_push($DATA, $Location_ID);                    //Location_ID - 8

            array_push($DATA, $Product_ID=array($Product_ID));  //Product_ID - 9
            array_push($DATA, $Name);                           //Name - 10
            array_push($DATA, $Item_Code);                      //Item_Code - 11
            array_push($DATA, $Tax_Code);                       //Tax_Code - 12
            array_push($DATA, $Zone);                           //Zone - 13

            array_push($DATA, $Warehouse_Stock);                //Warehouse_Stock - 14
            

            $json_OBJ = json_encode($DATA);
            $json_OBJ = addslashes($json_OBJ);
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NFC View | TTParikh</title>

    <link rel="stylesheet" href="css/nfc.css">
    <link rel="stylesheet" href="css/iconStyle.css">

</head>
<body>
    <header>
        <a class="logo" href="index.php">Trade Technocrats Ltd.</a>
    </header>
    <div class="grid_container">
        <div class="grid item1">
            <div class="inside">
                <span>Product Name : </span><label><?=$DATA[10][0]?></label>
            </div>
        </div>

        <div class="grid item4">
            <div class="inside">
                <span>Details (1 LB) : </span><label for=""><?=$DATA[11][0]?></label>
            </div>
            <div class="details">
                <div class="iitem1">
                    <table>
                        <tr>
                            <td><label for="">Locations :</label></td><td><label for=""><?=$DATA[8][0]?></label></td>
                        </tr>
                        <tr>
                            <td><label for="">Zone :</label></td><td><label for=""><?=$DATA[13][0]?></label></td>
                        </tr>
                        <tr>
                            <td><label for="">In Stock :</label></td><td><label for=""><?php echo ($DATA[3][0] - $DATA[4][0] + $DATA[5][0]); ?></label></td>
                        </tr>
                        <tr>
                            <td><label for="">Total Sold :</label></td><td><label for=""><?=$DATA[4][0]?></label></td>
                        </tr>
                        <tr>
                            <td><label for="">Yearly Avg :</label></td><td><label for=""><?=$DATA[6][0]?></label></td>
                        </tr>
                        <tr>
                            <td><label for="">Warehouse Stock :</label></td><td><label for=""><?=$DATA[14][0]?></label></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid item6">
            <div class="inside">
                <span> Details (114g) : </span><label for=""><?=$DATA[11][0]?>A</label>
            </div>
            <div class="details">
                <div class="iitem1">
                    <table>
                        <tr>
                            <td><label for="">Locations :</label></td><td><label for=""><?=$DATA[8][1]?></label></td>
                        </tr>
                        <tr>
                            <td><label for="">Zone :</label></td><td><label for=""><?=$DATA[13][0]?></label></td>
                        </tr>
                        <tr>
                            <td><label for="">In Stock :</label></td><td><label for=""><?php echo ($DATA[3][1] - $DATA[4][1] + $DATA[5][1]); ?></label></td>
                        </tr>
                        <tr>
                            <td><label for="">Total Sold :</label></td><td><label for=""><?=$DATA[4][1]?></label></td>
                        </tr>
                        <tr>
                            <td><label for="">Yearly Avg :</label></td><td><label for=""><?=$DATA[6][1]?></label></td>
                        </tr>
                        <tr>
                            <td><label for="">Warehouse Stock :</label></td><td><label for=""><?=$DATA[14][0]?></label></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
<!-- 
<tr>
    <td><label for="">Zone :</label></td><td>
        <?php
            // if($Zone == 'G')
            //     echo '<label style="color:var(--green-theme)">'.$DATA[13][0].'</label>';
            // else if($Zone == 'R')
            //     echo '<label style="color:var(--red-theme)">'.$DATA[13][0].'</label>';
            // if($Zone == 'Y')
            //     echo '<label style="color:var(--yellow-theme)">'.$DATA[13][0].'</label>';
        ?>
    </td>
</tr> -->
