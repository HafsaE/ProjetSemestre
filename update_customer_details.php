<?php
include_once("init.php");

?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>GS - Clients</title>

    <!-- Stylesheets -->

    <link rel="stylesheet" href="css/style.css">

    <!-- Optimize for mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- jQuery & JS files -->
    <?php include_once("tpl/common_js.php"); ?>
    <script src="js/script.js"></script>
    <script src="js/update_customer_details.js"></script>
    
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
            <li><a href="view_customers.php" class="active-tab customers-tab">Clients</a></li>
            <li><a href="view_purchase.php" class="purchase-tab">Achats</a></li>
            <li><a href="view_supplier.php" class=" supplier-tab">Fournisseurs</a></li>
            <li><a href="view_product.php" class=" stock-tab">Stocks / Produits</a></li>
            <li><a href="view_payments.php" class="payment-tab">Paiments </a></li>
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
                <li><a href="view_customers.php">Voir Clients</a></li>
            </ul>

        </div>
        <!-- end side-menu -->

        <div class="side-content fr">

            <div class="content-module">

                

                <div class="content-module-main cf">
                    <form name="form1" method="post" id="form1" action="">
                        <p><strong>Ajouter Détails du Client </strong> - Ajouter ( Control +A)</p>
                        <table class="form" border="0" cellspacing="0" cellpadding="0">
                            <?php
                            if (isset($_POST['id'])) {

                                $id = mysqli_real_escape_string($db->connection, $_POST['id']);
                                $name = trim(mysqli_real_escape_string($db->connection, $_POST['name']));
                                $address = trim(mysqli_real_escape_string($db->connection, $_POST['address']));
                                $contact1 = trim(mysqli_real_escape_string($db->connection, $_POST['contact1']));
                                $contact2 = trim(mysqli_real_escape_string($db->connection, $_POST['contact2']));


                                if ($db->query("UPDATE customer_details  SET customer_name='$name',customer_address='$address',customer_contact1='$contact1',customer_contact2='$contact2' where id='$id'"))
                                    echo "<br><font color=green size=+1 > Modification Réussie </font>";
                                else
                                    echo "<br><font color=red size=+1 >Problem in Updation !</font>";


                            }

                            ?>
                            <?php
                            if (isset($_GET['sid']))
                                $id = $_GET['sid'];

                            $line = $db->queryUniqueObject("SELECT * FROM customer_details WHERE id=$id");
                            ?>
                            <form name="form1" method="post" id="form1" action="">
                                <input name="id" type="hidden" value="<?php echo $_GET['sid']; ?>">
                                <tr>
                                    <td>Nom</td>
                                    <td><input name="name" type="text" id="name" maxlength="200"
                                               class="round default-width-input" onkeypress="return lettersOnly(event)"
                                               value="<?php echo $line->customer_name; ?> "/></td>
                                    <td><b><span class="man">*</span></b><b>Contact</b><b>-1</b></td>
                                    <td><input name="contact1" type="text" id="buyingrate" maxlength="20"
                                               class="round default-width-input" onkeypress="return numbersonly(event)"
                                               value="<?php echo $line->customer_contact1; ?>"/></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td><b>Addresse:</b></td>
                                    <td><textarea name="address" cols="15"
                                                  class="round full-width-textarea"><?php echo $line->customer_address; ?>
					  </textarea></td>

                                    <td><b>Contact</b><b>-2</b></td>
                                    <td><input name="contact2" type="text" id="sellingrate" maxlength="20"
                                               class="round default-width-input" onkeypress="return numbersonly(event)"
                                               value="<?php echo $line->customer_contact2; ?>"/></td>
                                </tr>


                                <tr>
                                    <td>
                                        &nbsp;
                                    </td>
                                    <td>
                                        <input class="button round blue image-right ic-add text-upper" type="submit"
                                               name="Submit" value="Enregistrer">
                                        (Control + S)
                                    </td>
                                    <td align="right"><input class="button round red   text-upper" type="reset"
                                                             name="Reset" value="Réinitialiser"></td>
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