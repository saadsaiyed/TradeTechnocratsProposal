<?php
    session_start();
    
    //connecting to database -START
        $host = '';
        $port = 3306;
        $db   = '';
        $user = '';
        $pass = '';
        $charset = '';

        try {
            $mysqli = new mysqli($host, $user, $pass, $db, $port);
            if($charset != '') $mysqli->set_charset($charset);
        } catch (\mysqli_sql_exception $e) {
            throw new \mysqli_sql_exception($e->getMessage(), $e->getCode());
        }
    //connecting to database -START

    //Server to company time conversion
    $serverTimeDifference = 10800;

    //Date From Starting of this software
    // $Start_Date = mktime(0,0,0,6,15,1975);
    function runQuery($query){
        $bt = debug_backtrace();
        $caller = array_shift($bt);
      
        if($result = mysqli_query($GLOBALS['mysqli'], $query))
            return $result;
        else{
            //print_r($GLOBALS['_POST']);

            echo "query = '$query'<br/>";
            die("Error in '".$caller['file']."' at line : ".$caller['line']." {" . mysqli_errno($GLOBALS['mysqli']) . "} : {" . mysqli_error($GLOBALS['mysqli']) . "} <br>");
        }
    }
    
    function runQueryGiveId($query){
        $bt = debug_backtrace();
        $caller = array_shift($bt);

        if(mysqli_query($GLOBALS['mysqli'], $query))
            return mysqli_insert_id($GLOBALS['mysqli']);
        else{
            print_r($GLOBALS['_POST']);
            die("Error in '".$caller['file']."' at line : ".$caller['line']." {" . mysqli_errno($GLOBALS['mysqli']) . "} : {" . mysqli_error($GLOBALS['mysqli']) . "} <br>");
        }
    }

    function lastID(){
        return $GLOBALS['mysqli']->insert_id;
    }

    function escapeChar($value){
        return mysqli_real_escape_string($GLOBALS['mysqli'], $value);
    }

    function checkIfLoggedIn($access1, $access2, $access3, $access4){ //$access* has the id of user and some user are allowed and some do not it depends on the user's priority set.
        // if(!$_SESSION["User_ID"]){
        //     header("Location:../login.php?location=" . urlencode($_SERVER['REQUEST_URI']));
        //     exit();
        // }
        // elseif ($_SESSION["User_ID"] == $access1 || $_SESSION["User_ID"] == $access2 || $_SESSION["User_ID"] == $access3 || $_SESSION["User_ID"] == $access4) {
        //     return true;
        // }
        // else{
        //     header("Location:../login.php?location=" . urlencode($_SERVER['REQUEST_URI']));
        //     exit();
        // }
        return true;
    }

    // Function for Time in the formate of how much long ago it was from current time
    function time2str($ts){
        if(!ctype_digit($ts))
            $ts = strtotime($ts);

        $diff = time() - $ts;
        if($diff == 0)
            return 'now';
        elseif($diff > 0)
        {
            $day_diff = $diff / 86400;
            if($day_diff <= 1)
            {
                if($diff < 60) return 'just now';
                if($diff < 120) return '1 minute ago';
                if($diff < 3600) return floor($diff / 60) . ' minutes ago';
                if($diff < 7200) return '1 hour ago';
                if($diff < 86400) return floor($diff / 3600) . ' hours ago';
            }
            if($day_diff == 1) return 'Yesterday';
            if($day_diff < 7) return round($day_diff, 1) . ' days ago';
            if($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
            if($day_diff < 60) return 'last month';
            return date('F Y', $ts);
        }
        else
        {
            $diff = abs($diff);
            $day_diff = floor($diff / 86400);
            if($day_diff == 0)
            {
                if($diff < 120) return 'in a minute';
                if($diff < 3600) return 'in ' . floor($diff / 60) . ' minutes';
                if($diff < 7200) return 'in an hour';
                if($diff < 86400) return 'in ' . floor($diff / 3600) . ' hours';
            }
            if($day_diff == 1) return 'Tomorrow';
            if($day_diff < 4) return date('l', $ts);
            if($day_diff < 7 + (7 - date('w'))) return 'next week';
            if(ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . ' weeks';
            if(date('n', $ts) == date('n') + 1) return 'next month';
            return date('F Y', $ts);
        }
    }

    // This function gives difference of months from starting of new system till Current date.
    function monthDifference(){
        $ts1 = strtotime('2020-06-15');

        $year1 = date('Y', $ts1);
        $year2 = date('Y');

        $month1 = date('m', $ts1);
        $month2 = date('m');

        return (int)(($year2 - $year1) * 12) + ($month2 - $month1);
    }
    monthDifference();
    function selectQuery($tableName, $columnsArray, $conArray, $orderArray){
        $columnsArray = array('Count', 'Invoice_ID', 'Create_Time');
        $colCount = count($columnsArray);
        $columns = '';
        if($colCount > 0){
            for ($i = 0; $i < $colCount; $i++) { 
                $columns .= $columnsArray[$i];
                if($i != ($colCount-1)) $columns .= ', ';
            }
        }

        $conArray = array(
            'LHS' => array('Barcode_ID', 'Invoice_ID'),
            'RHS' => array('772696000021', '1182'),
            'operator' => array('=', '='),
            'connector' => array('AND')
        );
        $conCount = count($conArray['LHS']);
        $conditions = '';
        if($conArray && $conCount > 0){
            $conditions = 'WHERE ';
            for($i = 0; $i < $conCount; $i++){
                $conditions .= $conArray['LHS'][$i] . $conArray['operator'][$i] . '\'' . $conArray['RHS'][$i]. '\'';
                if($i != ($conCount - 1)) $conditions .= $conArray['connector'][$i];
            }
        }
        else 
            $conditions = '*';

        $orderArray = array(
            'column' => array('Invoice_ID', 'Create_Time'),
            'order' => array('ASC', 'DESC')
        );
        $orderCount = count($orderArray['column']);
        if($orderCount > 0){
            $order = 'ORDER BY ';
            for ($i = 0; $i < $orderCount; $i++) { 
                $order .= $orderArray['column'][$i] . ' ' . $orderArray['order'][$i];
                if($i != ($orderCount - 1)) $order .= ', ';
            }
        }

        $query = "SELECT $conditions FROM $tableName $order";
        return runQuery($query);
    }

    function runPreparedQuery($query, $array){
            $datatype_string = "";
            foreach ($array as $value) {
                if(gettype($value) == "boolean") $datatype_string .= "b";
                else if(gettype($value) == "integer") $datatype_string .= "i";
                else if(gettype($value) == "double") $datatype_string .= "d";
                else if(gettype($value) == "string") $datatype_string .= "s";
                else {
                    $caller = array_shift(debug_backtrace());
                    die("<br/><h1>Error in '".$caller['file']."' at line : '".$caller['line']."'</h1><br/>");
                }            
            }
            // $datatype_string = "sss"
            
            // prepare and bind
            $stmt = $GLOBALS['mysqli']->prepare($query);
            $stmt->bind_param($datatype_string, ...$array);
            $stmt->execute();
            return $stmt->get_result();
    }