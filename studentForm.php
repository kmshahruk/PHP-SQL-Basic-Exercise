<?php
    require ("htmlStructure.php");
    require "dbLogin.php";
    session_start();
    $bottom= "";

    if(!isset($_POST["submit"])) {
        if (!isset($_SESSION["fromUpdate"])) {
            $name = "";
            $email = "";
            $gpa = "";
            $year = "";
            $gender = "";
            $password = "";
            $verifyPassword="";
        } else {


            $dbConnection = new mysqli($host, $user, $passwordDB, $database);
            if ($dbConnection->connect_error) {
                die($dbConnection->connect_error);
            }
            $x = $_SESSION['email'];
            $query = "select * from applicants where email= \"$x\"";
            $result = $dbConnection->query($query);
            if (!$result) {
                die("Retrieval failed: " . $dbConnection->error);
            } else {
                $result->data_seek(0);
                $row = $result->fetch_array(MYSQLI_ASSOC);

                $name = $row["name"];
                $email = $row["email"];
                $gpa = $row["gpa"];
                $year = $row["year"];
                $gender = $row["gender"];
                $password = $_SESSION["password"];
                $verifyPassword=  $_SESSION["password"];
                $result->close();
            }

        }
    }
    else {
        $name = trim($_POST["name"]);
        $email = trim($_POST["email"]);
        $gpa = trim($_POST["gpa"]);
        $year = trim($_POST["year"]);
        $gender = trim($_POST["gender"]);
        $password = trim($_POST["password"]);
        $verifyPassword= trim($_POST["verifyPassword"]);


//        make sure you password isnt being hashed double when you update
            if(!is_numeric($gpa)){
                $bottom.= "<br><strong>You must enter an appropriate gpa into the gpa field</strong>";
                $gpa="";
            }
            else if($verifyPassword == $password) {


//        insert information into the database, possibly altering
            $dbConnection = new mysqli($host, $user, $passwordDB, $database);
            if ($dbConnection->connect_error) {
                die($dbConnection->connect_error);
            }
            $gpa= floatval($gpa);
            if (isset($_SESSION["fromSubmit"])) {
                //            dont forget to hash the password
                $password = password_hash($password, PASSWORD_DEFAULT);
                $query = "insert into applicants values('$name', '$email', $gpa, $year, '$gender', '$password')";
            } else {

                $password = password_hash($password, PASSWORD_DEFAULT);
                $_SESSION["password"]= null;
                $query = "replace into applicants values('$name', '$email', $gpa, $year, '$gender', '$password')";
            }
            $result = $dbConnection->query($query);
            if (!$result) {
                $bottom= "<br><strong>This email has already been used</strong>";
                $name = "";
                $email = "";
                $gpa = "";
                $year = "";
                $gender = "";
                $password = "";
                $verifyPassword="";
            }
            else {
                if(isset($_SESSION['email']) && $_SESSION['email'] != $email) {
                    $emailAddr=$_SESSION['email'];
                    $query = "DELETE from applicants where email= '$emailAddr'";
                    $result = $dbConnection->query($query);
                    if (!$result) {
                        die("Retrieval failed: ". $db_connection->error);
                    }
                }
                $dbConnection->close();
                $_SESSION["email"] = $email;
                header("location: reviewStudentInfo.php");
            }
        }
        else {
            $bottom.= "<br> <strong>Passwords must match</strong>";
            $verifyPassword ="";
        }

    }
    if(isset($_POST["return"])){
        header("location: main.html");
        exit;
    }

    $body= <<<BODY
            <form action='$_SERVER[PHP_SELF]' method="post">
            
            <strong>Name:</strong>
            <input type="text" name="name" value="$name" required><br><br>
            
            <strong>Email:</strong>
            <input type="email" name="email" value="$email" required><br><br>

            <strong>GPA:</strong>
            <input type="text" name="gpa" value="$gpa" required><br><br>

            <strong>Year:</strong>
BODY;
    if(isset($year) && $year == 11){
        $body.= <<<BODY
            <input class="radio-inline" type="radio" id="10"  name="year" value=10>
            <label for= "10">10</label>
            
            <input class="radio-inline" type="radio" id="11"  name="year" value=11 checked>
            <label for= "11">11</label>

            <input class="radio-inline" type="radio" id="12"  name="year" value=12>
            <label for= "12">12</label><br><br>
BODY;

    }
    else if(isset($year) && $year == 12){
        $body.= <<<BODY
            <input class="radio-inline" type="radio" id="10"  name="year" value=10>
            <label for= "10">10</label>
            
            <input class="radio-inline" type="radio" id="11"  name="year" value=11>
            <label for= "11">11</label>

            <input class="radio-inline" type="radio" id="12"  name="year" value=12 checked>
            <label for= "12">12</label><br><br>
BODY;

    }
    else {
        $body.= <<<BODY
            <input class="radio-inline" type="radio" id="10"  name="year" value=10 checked>
            <label for= "10">10</label>
            
            <input class="radio-inline" type="radio" id="11"  name="year" value=11>
            <label for= "11">11</label>

            <input class="radio-inline" type="radio" id="12"  name="year" value=12>
            <label for= "12">12</label><br><br>
BODY;

    }
    $body.= "<strong>Gender:</strong>";
    if(isset($gender) &&  $gender == "F"){
        $body.= <<<BODY
            <input class="radio-inline" type="radio" id="M"  name="gender" value="M">
            <label for= "M">M</label>
            
            <input class="radio-inline" type="radio" id="F"  name="gender" value="F" checked>
            <label for= "F">F</label><br><br>
BODY;
    }
    else{
        $body.= <<<BODY
            <input class="radio-inline" type="radio" id="M"  name="gender" value="M" checked>
            <label for= "M">M</label>
            
            <input class="radio-inline" type="radio" id="F"  name="gender" value="F">
            <label for= "F">F</label><br><br>
BODY;
    }

    $body.= <<<BODY
            <strong>Password:</strong>
            <input type="password" name="password" value="$password" required><br><br>
            
            <strong>Verify Password:</strong>
            <input type="password" name="verifyPassword" value="$verifyPassword" required><br><br>

            <input type="submit" name="submit" value="Submit Data"><br><br>
             
            </form>
            
            <form action='$_SERVER[PHP_SELF]' method="post">
                <input type="submit" name="return" value="Return to main menu"><br><br>
            </form>
BODY;

    echo generatePage($body.$bottom);