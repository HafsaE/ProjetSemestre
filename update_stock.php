<?php
include_once("init.php");

?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Gs - Stock</title>

    <!-- Stylesheets -->

    <link rel="stylesheet" href="css/style.css">

    <!-- Optimize for mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- jQuery & JS files -->
    <?php include_once("tpl/common_js.php"); ?>
    <script src="js/script.js"></script>
    <script src="js/update_stock.js"></script>
  
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
            <li><a href="view_customers.php" class=" customers-tab">Clients</a></li>
            <li><a href="view_purchase.php" class="purchase-tab">Achats</a></li>
            <li><a href="view_supplier.php" class=" supplier-tab">Fournisseurs</a></li>
            <li><a href="view_product.php" class=" active-tab stock-tab">Stocks / Produits</a></li>
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
                <li><a href="add_stock.php">Ajouter Stock/Produit</a></li>
                <li><a href="view_product.php">Afficher Stock/Produit</a></li>
                <li><a href="add_category.php">Ajouter Catégorie De Stock</a></li>
                <li><a href="view_category.php">Afficher Catégorie De Stock</a></li>
                <li><a href="view_stock_availability.php">Afficher Disponible Aux Stocks</a></li>
            </ul>

        </div>
        <!-- end side-menu -->

        <div class="side-content fr">

            <div class="content-module">

                

                <div class="content-module-main cf">
                    <form name="form1" method="post" id="form1" action="">
                       
                        <table class="form" border="0" cellspacing="0" cellpadding="0">
                            <?php
                            if (isset($_POST['id'])) {

                                $id = mysqli_real_escape_string($db->connection, $_POST['id']);
                                $name = trim(mysqli_real_escape_string($db->connection, $_POST['name']));
                                $sell = trim(mysqli_real_escape_string($db->connection, $_POST['sell']));
                                $cost = trim(mysqli_real_escape_string($db->connection, $_POST['cost']));
                                $Category = trim(mysqli_real_escape_string($db->connection, $_POST['Category']));
                                $date = trim(mysqli_real_escape_string($db->connection, $_POST['date']));
                                $supplier = trim(mysqli_real_escape_string($db->connection, $_POST['supplier']));


                                if ($db->query("UPDATE stock_details  SET stock_name ='$name',supplier_id='$supplier',company_price='$cost',selling_price='$sell',category='$Category',date='$date'  where id=$id"))
                                    echo "<br><font color=green size=+1 > Succes </font>";
                                else
                                    echo "<br><font color=red size=+1 >Erreur !</font>";


                            }

                            ?>
                            <?php
                            if (isset($_GET['sid']))
                                $id = $_GET['sid'];

                            $line = $db->queryUniqueObject("SELECT * FROM stock_details WHERE id=$id");
                            ?>
                            <form name="form1" method="post" id="form1" action="">

                                <input name="id" type="hidden" value="<?php echo $_GET['sid']; ?>">
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>ID Stock</td>
                                    <td><input name="stock_id" type="text" readonly="readonly" id="name" maxlength="200"
                                               class="round default-width-input"
                                               value="<?php echo $line->stock_id; ?> "/>
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>Nom</td>
                                    <td><input name="name" type="text" id="name" maxlength="200"
                                               class="round default-width-input"
                                               value="<?php echo $line->stock_name; ?> "/></td>
                                    <td>Catégorie</td>
                                    <td><input name="Category" type="text" id="category" maxlength="20"
                                               class="round default-width-input"
                                               value="<?php echo $line->category; ?>"/></td>
                                </tr>

                                <tr>
                                    <td>&nbsp;</td>
                                    <td>Prix</td>
                                    <td><input name="cost" type="text" id="cost" maxlength="20"
                                               class="round default-width-input"
                                               value="<?php echo $line->company_price; ?>"
                                               onkeypress="return numbersonly(event)"/></td>
                                    <td>Prix De Vente</td>
                                    <td><input name="sell" type="text" id="selling" maxlength="20"
                                               class="round default-width-input"
                                               value="<?php echo $line->selling_price; ?>"
                                               onkeypress="return numbersonly(event)"/></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Nom Fournisseur</td>
                                    <td><input name="supplier" type="text" id="supplier" maxlength="20"
                                               class="round default-width-input"
                                               value="<?php echo $line->supplier_id; ?>"/></td>
                                    <td>Date d'expiration</td>
                                    <td><input name="date" type="text" id="date" maxlength="20"
                                               class="round default-width-input"
                                               value="<?php echo $line->date; ?>"/></td>
                                </tr>


                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>
                                        <input class="button round blue image-right ic-add text-upper" type="submit"
                                               name="Submit" value="Enregistrer">
                                        
                                    </td>
                                    <td align="right"><input class="button round red   text-upper" type="reset"
                                                             name="Reset" value="Réinitialiser">
                                    </td>
                                    <td>&nbsp;</td>
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