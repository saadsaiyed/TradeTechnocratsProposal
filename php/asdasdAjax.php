<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        *{
            padding: 8px;
        }
        #something *{
            padding: 10px 20px;
        }
        #something tr:nth-child(2n + 1){
            background: #0914281a;
        }
    </style>
</head>
<body>
    <?php
        include "DBConnection.php";
    
        $Count = array();
        $Product_ID = array();
        $Product_Name = array();
        $query = "SELECT P.Name, sum(I.Count) AS 'Total Sold'
                FROM B_Invoice_Barcode AS I
                INNER JOIN Barcode AS B
                ON I.Barcode_ID = B.Barcode_ID
                INNER JOIN Product AS P
                ON P.Product_ID = B.Product_ID
                WHERE P.Item_Code LIKE 'T.%'
                GROUP BY I.Barcode_ID
                ORDER BY P.Name ASC";

        $result = runQuery($query);

        echo "<table id='something'> <tr><th>Product Name</th><th>Total Sold</th><th>In Stock</th><th>Not In Stock</th><th>Full</th></tr>";

        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                echo "<td>$value</td>";
            }
            echo "<td>INS</td>";
            echo "<td>NS</td>";
            echo "<td>Full</td>";
            echo "</tr>";
        }
        echo "</table>";    
    
    ?>
</body>
</html>