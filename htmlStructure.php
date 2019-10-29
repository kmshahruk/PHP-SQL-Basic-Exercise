<?php

function generatePage($body) {
    $page = <<<MAIN
<!doctype html>
<html>
    <head> 
        <title>Project4</title>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1">	
    </head>
            
    <body>
    <div class="container-fluid">
            $body
    </div>
    <script src="bootstrap/jquery-3.2.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>
MAIN;

    return $page;
}
