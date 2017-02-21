<?php


include("lib/db.class.php");
include_once "config.php";


// Open the base (construct the object):
$db = new DB($config['database'], $config['host'], $config['username'], $config['password']);

# Note that filters and validators are separate rule sets and method calls. There is a good reason for this.

require "lib/gump.class.php";

$gump = new GUMP();
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>GS</title>

    <!-- Stylesheets -->

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cmxform.css">
    <link rel="stylesheet" href="js/lib/validationEngine.jquery.css">

    <!-- Scripts -->
    <script src="js/lib/jquery.min.js" type="text/javascript"></script>
    <script src="js/lib/jquery.validate.min.js" type="text/javascript"></script>

    <script>
        /*$.validator.setDefaults({
         submitHandler: function() { alert("submitted!"); }
         });*/

        $(document).ready(function () {

            // validate signup form on keyup and submit
            $("#login-form").validate({
                rules: {
                    name: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Entrez la reponse"
                    }
                }
            });

        });

    </script>

    <!-- Optimize for mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>

<!--    Only Index Page for Analytics   -->

<!-- TOP BAR -->
<div id="top-bar">

    <div class="page-full-width">

        <!--<a href="#" class="round button dark ic-left-arrow image-left ">See shortcuts</a>-->

    </div>
    <!-- end full-width -->

</div>
<!-- end top-bar -->


<!-- HEADER -->
<div id="header">

    <div class="page-full-width cf">

        <div id="login-intro" class="fl">

            <h1> Mot de passe oublié </h1>


        </div>
        

    </div>
    <!-- end full-width -->

</div>
<!-- end header -->


<!-- MAIN CONTENT -->
<div id="content">

    <?php if (isset($_POST['submit']) and isset($_POST['name'])){ ?>
    <fieldset style="margin-left: 600px"><p><?php
            $name = $_POST['name'];
            $count = $db->queryUniqueValue("select sum(id) FROM stock_user where answer ='" . $name . "'");

            if ($count > 0){
            $line = $db->queryUniqueObject("SELECT * FROM stock_user where answer ='" . $name . "'");

            echo " User Name: <strong style=color:blue> $line->username </strong> <br><br>";
            echo " Password: <strong style=color:blue>  $line->password </strong> ";
            ?>
            <br> <br> <br> <a href="index.php" class="button blue round side-content">Dashboard</a>
        <?php
        } else {
            $data = "Réponse erronée";
            echo "<script>window.location = 'forget_pass.php?msg=$data';</script>";
        }
        echo "</p></fieldset>";
        } else {

            ?>
            <fieldset>
            <p style="margin-left: 600px;color: red;font-size: 20px"> <?php

                if (isset($_REQUEST['msg'])) {

                    $msg = $_REQUEST['msg'];
                    echo $msg;
                }
                ?>

            </p>

            <form action="forget_pass.php" method="POST" id="login-form" class="cmxform" enctype="multipart/form-data">
                Film Favori ?
                <input type="text" name="name" id="name" class="round full-width-input"><br><br>
                <input type="submit" name="submit" value="Envoyer" class="button round blue image-right ic-right-arrow">
                <a href="index.php" class="button blue round side-content">Acceuil</a>
            </form>
            </fieldset>
        <?php } ?>

</div>
<!-- end content -->


<!-- FOOTER -->
<div id="footer">
   
    


</div>
<!-- end footer -->

</body>
</html>

