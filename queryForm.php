<?php
    require ("htmlStructure.php");
    require "dbLogin.php";
    session_start();
    $bottom= "";
    $body="";
    $stayInForm= true;

    if(isset($_POST["return"])){
        header("location: main.html");
        exit;
    }
    if(isset($_POST["submit"])){
        if(!isset($_POST["fields"])){
            $bottom.= "<br> <strong>You must select a field to view</strong>";
        }
        else {
            $query = "SELECT ";
            foreach ($_POST["fields"] as $key) {
                $query .= "$key,";
            }
            $query = trim($query, ",");
            $query .= " FROM applicants";
            if (isset($_POST["filter"]) && $_POST['filter'] != "") {
                $filter = trim($_POST['filter']);
                $query .= " WHERE " . $filter;
            }
            $query .= " ORDER BY {$_POST['sortBy']}";

            $_SESSION['query'] = $query;


            $dbConnection = new mysqli($host, $user, $passwordDB, $database);
            if ($dbConnection->connect_error) {
                die($dbConnection->connect_error);
            }
            $result = $dbConnection->query($_SESSION['query']);
            if (!$result) {
                $stayInForm = true;
                $bottom .= "<br><strong>Something went wrong please try again</strong>";
            } else {
                $stayInForm = false;
                $result->data_seek(0);
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $fields = array_keys($row);


                $body = <<<BODY
            <h1>Applications</h1> <br>
             <table border="1">
                <tr>
BODY;
                foreach ($fields as $keys) {
                    $body .= "<th>$keys</th>";
                }
                $body .= "</tr>";
                $numRows = $result->num_rows;
                for ($rowIndex = 0; $rowIndex < $numRows; $rowIndex++) {
                    $result->data_seek($rowIndex);
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    $body .= "<tr>";
                    foreach ($row as $value) {
                        $body .= "<td>$value</td>";
                    }
                    $body .= "</tr>";
                }
                $body .= "</table>";

                $result->close();
                $body .= <<<BODY
                <br>
                <form action='$_SERVER[PHP_SELF]' method="post">
                    <input type="submit" name="return" value="Return to main menu">
                </form>
BODY;


            }
        }
    }
    if(isset($_POST["return"])){
        header("location: main.html");
        exit;
    }


    if($stayInForm) {
        $body .= <<<BODY
    
            <form action='$_SERVER[PHP_SELF]' method="post">
                <h1>Applications</h1>
                
                <strong>Select fields to display</strong><br>
                <select id="fields" multiple name="fields[]">
                    <option value="name">name</option>
                    <option value="email">email</option>
                    <option value="gpa">gpa</option>
                    <option value="year">year</option>
                    <option value="gender">gender</option>
                </select>
                <br><br>
                <strong>Select field to sort applications</strong>
                <select name="sortBy">
                    <option value="name">name</option>
                    <option value="email">email</option>
                    <option value="gpa">gpa</option>
                    <option value="year">year</option>
                    <option value="gender">gender</option>
                </select>
                <br><br>
                <strong>Filter Conditions</strong>
                <input type="text" name="filter"><br><br>
                
                <input type="submit" name="submit" value="Display Applications">
                <br>
                <br>
                <input type="submit" name="return" value="Return to main menu">
            </form>
BODY;
    }

    echo generatePage($body.$bottom);