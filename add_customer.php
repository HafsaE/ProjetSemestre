<?php
include_once("init.php");

?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>GS - Client</title>

    <!-- Stylesheets -->

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/add_customer.css">

    <!-- Optimize for mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- jQuery & JS files -->
    <?php include_once("tpl/common_js.php"); ?>
    <script src="js/script.js"></script>
    <script src="js/add_customer.js"></script>
</head>
<body>

<!-- TOP BAR -->
<?php include_once("tpl/top_bar.php"); ?>
<!-- end top-bar -->


<!-- HEADER -->
<div id="header-with-tabs">

    <div class="page-full-width cf">

       <ul id="tabs" class="fl">
            <li><a href="dashboard.php" class=" dashboard-tab">Acceuil</a></li>
            <li><a href="view_sales.php" class="sales-tab">Ventes</a></li>
            <li><a href="view_customers.php" class=" active-tab customers-tab">Clients</a></li>
            <li><a href="view_purchase.php" class="purchase-tab">Achats</a></li>
            <li><a href="view_supplier.php" class=" supplier-tab">Fournisseurs</a></li>
            <li><a href="view_product.php" class=" stock-tab">Stocks / Produits</a></li>
            <li><a href="view_payments.php" class="payment-tab">Paiments / Outstandings</a></li>
            <li><a href="view_report.php" class="report-tab">Rapports</a></li>
        </ul>
        <!-- end tabs -->

        

    </div>
    <!-- end full-width -->

</div>
<!-- end header -->


<!-- MAIN CONTENT -->
<div id="content">

    <div class="page-full-width cf">

        <div class="side-menu fl">

            
            <ul>
                <li><a href="add_customer.php">Ajouter Client</a></li>
                <li><a href="view_customers.php">Afficher Clients</a></li>
            </ul>

        </div>
        <!-- end side-menu -->

        <div class="side-content fr">

            <div class="content-module">

                
                <!-- end content-module-heading -->

                <div class="content-module-main cf">


                    <?php
                    //Gump is libarary for Validatoin

                    if (isset($_POST['name'])) {
                        $_POST = $gump->sanitize($_POST);
                        $gump->validation_rules(array(
                            'name' => 'required|max_len,100|min_len,3',
                            'address' => 'max_len,200',
                            'contact1' => 'alpha_numeric|max_len,20',
                            'contact2' => 'alpha_numeric|max_len,20'
                        ));

                        $gump->filter_rules(array(
                            'name' => 'trim|sanitize_string|mysqli_escape',
                            'address' => 'trim|sanitize_string|mysqli_escape',
                            'contact1' => 'trim|sanitize_string|mysqli_escape',
                            'contact2' => 'trim|sanitize_string|mysqli_escape'
                        ));

                        $validated_data = $gump->run($_POST);
                        $name = "";
                        $address = "";
                        $contact1 = "";
                        $contact2 = "";

                        if ($validated_data === false) {
                            echo $gump->get_readable_errors(true);
                        } else {


                            $name = mysqli_real_escape_string($db->connection, $_POST['name']);
                            $address = mysqli_real_escape_string($db->connection, $_POST['address']);
                            $contact1 = mysqli_real_escape_string($db->connection, $_POST['contact1']);
                            $contact2 = mysqli_real_escape_string($db->connection, $_POST['contact2']);

                            $count = $db->countOf("customer_details", "customer_name='$name'");
                            if ($count == 1) {
                                echo "<div class='error-box round'>Existe Deja</div>";
                            } else {

                                if ($db->query("insert into customer_details values(NULL,'$name','$address','$contact1','$contact2',0)"))
                                    echo "<div class='confirmation-box round'>Client [ $name ]  Ajoute avec succes !</div>";
                                else
                                    echo "<div class='error-box round'>Erreur</div>";

                            }
                        }
                    }

                    ?>

                    <form name="form1" method="post" id="form1" action="">

                        <p><strong>Ajouter Informations du Client </strong> - Ajouter ( Control +A)</p>
                        <table class="form" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><span class="man">*</span>Nom:</td>
                                <td><input name="name" placeholder="Nom Complet" type="text" id="name"
                                           maxlength="200" class="round default-width-input" onkeypress="return lettersOnly(event)"
                                           value="<?php echo isset($name) ? $name : ''; ?>"/></td>
                                <td><b><span class="man">*</span></b><b>Contact1</b><b></b></td>
                                <td><input name="contact1" placeholder="Contact 1" type="text"
                                           id="buyingrate" maxlength="20" class="round default-width-input" onkeypress="return numbersonly(event)"
                                           value="<?php echo isset($contact1) ? $contact1 : ''; ?>"/></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td><b>Adresse:</b></td>
                                <td><textarea name="address" placeholder="Adresse" cols="15"
                                              class="round full-width-textarea"><?php echo isset($address) ? $address : ''; ?></textarea>
                                </td>
                                <td><b>Contact2</b><b></b></td>
                                <td><input name="contact2" placeholder="Contact 2" type="text"
                                           id="sellingrate" maxlength="20" class="round default-width-input" onkeypress="return numbersonly(event)"
                                           value="<?php echo isset($contact2) ? $contact2 : ''; ?>"/></td>

                            </tr>

                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    &nbsp;
                                </td>
                                <td>
                                    <input class="button round blue image-right ic-add text-upper" type="submit"
                                           name="Submit" value="Ajouter">
                                    (Control + S)
                                <td>
                                    &nbsp;
                                </td>
                                <td align="right"><input class="button round red text-upper" type="reset" name="Reset"
                                                         value="Reinitialiser"></td>
                            </tr>
                        </table>
                    </form>


                </div>
                <!-- end content-module-main -->


            </div>
            <!-- end content-module -->


        </div>
        <!-- end full-width -->

    </div>
    <!-- end content -->


    <!-- FOOTER -->
    <div id="footer">
        

    </div>
    <!-- end footer -->

</body>
</html>