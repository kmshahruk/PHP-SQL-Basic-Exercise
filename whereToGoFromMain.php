<?php

session_start();

if(isset($_POST["submit"])){
    $_SESSION["fromSubmit"]=true;
    $_SESSION["fromUpdate"]=null;
    $_SESSION["fromReview"]=null;
    header("location: studentForm.php");
}
else if (isset($_POST["review"])){
    $_SESSION["fromReview"]=true;
    $_SESSION["fromUpdate"]=null;
    $_SESSION["fromSubmit"]=null;
    header("location: login.php");
}
else if (isset($_POST["update"])){
    $_SESSION["fromUpdate"]=true;
    $_SESSION["fromSubmit"]=null;
    $_SESSION["fromReview"]=null;
    header("location: login.php");
}
else {
    $_SESSION["fromAdmin"]=true;
    $_SESSION["fromUpdate"]=null;
    $_SESSION["fromSubmit"]=null;
    $_SESSION["fromReview"]=null;
    if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])
        && password_verify($_SERVER['PHP_AUTH_USER'],
        "\$2y\$10\$sVci071GGMmgl/NLJ/vBLOqJSpKDRbkQuJ21AerEQmuUe6JRJOQua")
        && password_verify($_SERVER['PHP_AUTH_PW'],
        "\$2y\$10\$ssaEI73bad7y9fFbEz6I6etHpqDDGamZtm1uZQRAq1swb/i28DTeG")){
                header("location: queryForm.php");

    } else {
        header('WWW-Authenticate: Basic realm=\"Example System\"');
        header("HTTP/1.0 401 Unauthorized");
    }
}