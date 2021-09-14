<?php
    include "DBConnection.php";
    checkIfLoggedIn(null, null, null, 4);   
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <style>
            #root *{
                padding-left: 10px;
                padding-right: 10px;
            }
            td, th{
                border-right: solid black 1px;
            }
        </style>
    </head>
    <body>
        <center>
        <div id="root">
            <?php
                $tableNameArrayResult = runQuery("SHOW TABLES");
                while($tableNameArray = mysqli_fetch_assoc($tableNameArrayResult)){
                    $tableName = $tableNameArray["Tables_in_".$GLOBALS['db']];
                    
                    $query = "DESC `$tableName`";
                    echo "<h3>$tableName</h3><table><tr>";

                    $columnNameString = '';
                    $columnNameArrayResult = runQuery("DESC $tableName");
                    while($columnNameArray = mysqli_fetch_assoc($columnNameArrayResult)){
                        $columnNameString .= "<th>".$columnNameArray['Field']."</th>";
                    }

                    $indexCounter = 0;
                    $tableContentArrayResult = runQuery("SELECT * FROM `$tableName`");
                    while ($tableContentArray = mysqli_fetch_assoc($tableContentArrayResult)){
                        if(fmod($indexCounter, 50) == 0){
                            echo $columnNameString;
                        }
                        echo "</tr><tr>";
                        foreach ($tableContentArray as $value) {
                            echo "<td><label>".trim($value)."</label></td>";
                        }
                        echo "</tr>";
                        $indexCounter++;
                    }
                    echo "</table><br/><br/>";
                }
            ?>
        </div>
        </center>
    </body>
</html>