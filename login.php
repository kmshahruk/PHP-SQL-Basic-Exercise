<?php
    require ("htmlStructure.php");
    require "dbLogin.php";
    session_start();

    $bottom="";
    if(isset($_POST["submit"])){
        $email=trim($_POST["email"]);
        $password=trim($_POST["password"]);

        $dbConnection = new mysqli($host, $user, $passwordDB, $database);
        if ($dbConnection->connect_error) {
            die($dbConnection->connect_error);
        }

        $query = "select * from applicants where email= \"$email\"";
        $result = $dbConnection->query($query);
        if (!$result) {
            die("Retrieval failed: " . $dbConnection->error);
           $bottom.=  "<strong>No entry exists in the database for the specified email and password</strong>";
        } else {
            $result->data_seek(0);
            $row = $result->fetch_array(MYSQLI_ASSOC);

            $passwordFromTable = $row["password"];

            $result->close();
            if(password_verify($password, $passwordFromTable)){
                $_SESSION["email"]= $email;
                $_SESSION["password"]= $password;
                if (isset($_SESSION["fromReview"])){
                    header("location: reviewStudentInfo.php");
                }
                else{
                    header("location: studentForm.php");
                }
            }
            else{
                $bottom.=  "<strong>No entry exists in the database for the specified email and password</strong>";
                $password="";
            }
        }



//        This is where I need to check if the email is in the database.
//        If the email does exist, I must extract the password, and either hash
//        the submitted password and compare both or unhash the password
//        that is stored in the database and compare.

//          if the password is correct, I go to the student form if I'm coming from update button
//          else I go to the student review page if im coming from the review button
    }
    else{
        $email="";
        $password="";
    }
    if(isset($_POST["return"])){
        header("location: main.html");
        exit;
    }




    $body= <<<BODY
           <form action='$_SERVER[PHP_SELF]' method="post">
                <strong>Email associated with application:</strong>
                <input type="email" name="email" value=$email><br><br>
                <strong>Password associated with application:</strong>
                <input type="password" name="password" value=$password><br><br>

                <input type="submit" name="submit"><br><br>
                <input type="submit" name="return" value="Return to main menu"><br><br>
BODY;

    echo generatePage($body.$bottom);
