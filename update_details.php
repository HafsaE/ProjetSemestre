<?php include_once("init.php");
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
    <script src="js/update_details.js" type="text/javascript"></script>

  

    <!-- Optimize for mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
<?php if (isset($_POST['submit']) and $_POST['submit'] === 'Submit') {

    $allowedExts = array("gif", "jpeg", "jpg", "png");
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp);
    if ((($_FILES["file"]["type"] == "image/gif")
            || ($_FILES["file"]["type"] == "image/png"))
        && ($_FILES["file"]["size"] < 30000)
        && in_array($extension, $allowedExts)
    ) {
        if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
        } else {
            $upload = $_FILES["file"]["name"];
            $type = $_FILES["file"]["type"];


            if (file_exists("upload/" . $_FILES["file"]["name"])) {

                unlink($upload);
            }


            $name = $_FILES["file"]["name"];
            move_uploaded_file($_FILES["file"]["tmp_name"],
                "upload/" . $name);
            //echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
            $upload;
            $_SESSION['logo'] = $upload;

            # Note that filters and validators are separate rule sets and method calls. There is a good reason for this.

            $db->query("UPDATE store_details  SET log ='" . $upload . "',type='" . $type . "'");
            ?>
            <script type="text/javascript">
                setTimeout("window.location.reload();", 4000);
            </script>
            <?php
            echo "<script>window.location = 'update_details.php';</script>";

        }
    } else {
        echo "<p  style=color:red;margin-left:550px;font-size:20px >Fichier Invalide</p>";
    }
}

?>

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

            <h1>Paramètres</h1>


        </div>
        <!-- login-intro -->

        

    </div>
    <!-- end full-width -->

</div>
<!-- end header -->

<?php

if (isset($_POST['submit']) and isset($_POST['sname']) and isset($_POST['address']) and $_POST['submit'] == 'Update') {
    $name = $_POST['sname'];
    $address = $_POST['address'];
    $place = $_POST['place'];
    $city = $_POST['city'];
    $phone = $_POST['phone'];
    $web = $_POST['website'];
    $email = $_POST['email'];
    $pin = $_POST['pin'];
    if ($db->query("UPDATE store_details  SET pin='" . $pin . "',city='" . $city . "',name='" . $name . "',email='" . $email . "',web='" . $web . "',address='" . $address . "',place='" . $place . "',phone='" . $phone . "' "))
        echo "<br><font color=green size=+1 > Store Details Updated!</font>";

    // header("location: logo_set.php");
    //  exit;
}
?>


<!-- MAIN CONTENT -->
<div id="content">


    <form action="" method="POST" id="login-form" class="cmxform" autocomplete="off">
        <?php
        $line = $db->queryUniqueObject("SELECT * FROM store_details ");
        ?>
        <table>
            <tr>
                <td>

                    <p>
                        <label>Nom</label>
                        <input type="text" name="sname" id="name" class="round full-width-input"
                               value="<?php echo $line->name ?>" autofocus/>
                    </p></td>
                <td>
                    <p>
                        <label>Addresse</label>
                        <input type="text" name="address" id="address" class="round full-width-input"
                               value="<?php echo $line->address ?>" autofocus/>
                    </p></td>
            </tr>
            <tr>
                <td>
                    <p>
                        <label>Place</label>
                        <input type="text" name="place" id="place" class="round full-width-input"
                               value="<?php echo $line->place ?>" autofocus/>
                    </p>
                </td>
                <td>
                    <p>
                        <label>Ville</label>
                        <input type="text" name="city" id="city" class="round full-width-input"
                               value="<?php echo $line->city ?>" autofocus/>
                    </p></td>
            </tr>
            <tr>
                <td>
                    <p>
                        <label>Pin</label>
                        <input type="text" name="pin" id="pin" class="round full-width-input"
                               value="<?php echo $line->pin ?>" autofocus/>
                    </p>

                </td>
                <td>
                    <p>
                        <label>Tel</label>
                        <input type="text" name="phone" id="phone" class="round full-width-input"
                               value="<?php echo $line->phone ?>" autofocus/>
                    </p></td>
            </tr>
            <tr>
                <td>
                    <p>
                        <label>Site</label>
                        <input type="text" name="website" id="website" class="round full-width-input"
                               value="<?php echo $line->web ?>" autofocus/>
                    </p></td>
                <td>
                    <p>
                        <label>Email</label>
                        <input type="text" name="email" id="email" class="round full-width-input"
                               value="<?php echo $line->email ?>" autofocus/>
                    </p>

                </td>
            </tr>
            <tr></tr>
            <tr>
                <td>


                    <!--<a href="dashboard.php" class="button round blue image-right ic-right-arrow">LOG IN</a>-->
                    <input type="submit" class="button round blue image-right ic-right-arrow" name="submit"
                           value="Update"/>
                </td>
                <td><a href="index.php" class="button blue round side-content">Acceuil</a></td>
            </tr>
        </table>

    </form>
    <div style="float: right;margin-top: -350px">
        <form action="" method="POST" id="login-form" class="cmxform" enctype="multipart/form-data">
            <p>Modifier Logo</p>
            <input type="file" name="file" id="file" class="round full-width-input"><br><br><br>
            <input type="submit" name="submit" value="Enregistrer" class="button round blue image-right ic-right-arrow">
        </form>
    </div>
</div>
<!-- end content -->


<!-- FOOTER -->
<div id="footer">
    


</div>
<!-- end footer -->

</body>
</html>

