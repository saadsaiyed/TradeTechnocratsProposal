<?php
    include "php/DBConnection.php";

    $search = $_GET["search"];
    if($search){
        $result = runQuery("SELECT P.Name, P.Product_ID, B.Type, BL.Count, B.Barcode_ID FROM B_Location_Barcode AS BL
                            JOIN Barcode AS B
                            ON BL.Barcode_ID = B.Barcode_ID
                            JOIN Product AS P
                            ON B.Product_ID = P.Product_ID
                            WHERE BL.Location_ID='$search'");
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile View Location | TTParikh</title>

    <link rel="stylesheet" href="css/qrLocation.css">
    <link rel="stylesheet" href="css/iconStyle.css">
    <script src="https://unpkg.com/feather-icons"></script>

    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
        }
        body, html {
            height: 100%;
        }
        table td:nth-child(2){
            padding-right: 30px;
        }
        td{
            padding: 10px;
            border-bottom: 1px black solid;
        }
    </style>

</head>
<body>
    <header>
        <a class="logo" href="index.php">Trade Technocrats Ltd.</a>
    </header>
    <div class="grid_container">
        <div class="item1">
            <span>Location : </span><label><?=$search?></label>
        </div>
    
        <div mbsc-page class="demo-remove-undo">
            <div style="height:100%">
                <table>
                <?if($result->num_rows != 0){
                    while($row = mysqli_fetch_assoc($result)){
                        ?>
                        <tr>
                            <td><? echo $row["Name"]; if($row["Type"] == 'B') echo " - 114g";?></td>
                            <td onclick="deleteRow('<?=$row['Barcode_ID']?>', '<?php echo addslashes($row['Name'])?>')">X</td>
                        </tr>
                        <?
                    }
                }?>
                </table>
            </div>
        </div>

    <script>
        feather.replace();
        function deleteRow(barcode, name){
            var location = '<?=$search?>';
            var r = confirm("Are you sure you want to remove following item? \nName: '"+name+"'\nLocation: '"+location+"'");
            if (r == true) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        alert(this.responseText);
                        window.location.reload();
                    }
                };
                xhttp.open("GET", "php/removeItemFromLocationAjax.php?Barcode="+barcode+"&Location="+location, true);
                xhttp.send();
            }
        }
    </script>
</body>
</html>