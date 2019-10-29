<?php
    require ("htmlStructure.php");
    require "dbLogin.php";
    session_start();
    $bottom= "";


    if(isset($_POST["return"])){
        header("location: main.html");
        exit;
    }

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
        $result->close();
        $_SESSION['email']= null;
    }
    $header="";
    if(isset($_SESSION["fromSubmit"])){
      $header.= "The following entry has been added to the database";
    }
    else if($_SESSION["fromReview"]){
        $header.= "Application found in the database with the following values:";
    }
    else {
        $header.= "The entry has been updated in the database and the new values are:";
}
    $body= <<<BODY
            <h1>$header</h1>
            <strong>Name: </strong>$name<br>
            <strong>Email: </strong>$email<br>
            <strong>Gpa: </strong>$gpa<br>
            <strong>Year: </strong>$year<br>
            <strong>Gender: </strong>$gender<br><br>
            
            <form action='$_SERVER[PHP_SELF]' method="post">
                <input type="submit" name="return" value="Return to main menu"><br><br>
            </form>     
BODY;
    echo generatePage($body);

