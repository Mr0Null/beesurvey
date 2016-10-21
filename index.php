<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>نظرسنجی</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">



</head>
<body style="background-color: #E5E5E5; direction: rtl;">
<script type="application/javascript" src="js/jquery-2.2.0.min.js"></script>
<script type="application/javascript" src="js/bootstrap.min.js"></script>
<script type="application/javascript" src="js/script.js"></script>
<div class="container well"
     style="background-color: #FFFFFF; margin-top: 50px; border-radius: 20px; border-style: solid; border-width:1px; padding: 20px;">
    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4" style="float: right;">
        <img style="max-width: 100%;" src="img/logo.png">
    </div>
    <div class="col-lg-10 col-md-9 col-sm-8 col-xs-8 well" style="overflow: auto; word-wrap: break-word;">
        <?php
        $INDEX_IS_LOADED = 1;
        require_once "survey.php"; ?>
    </div>
</div>
</body>
</html>