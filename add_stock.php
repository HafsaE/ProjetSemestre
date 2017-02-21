<?php
include_once("init.php");

?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>GS - Stock</title>

    <!-- Stylesheets -->

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="js/date_pic/date_input.css">
    <link rel="stylesheet" href="lib/auto/css/jquery.autocomplete.css">

    <!-- Optimize for mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- jQuery & JS files -->
    <?php include_once("tpl/common_js.php"); ?>
    <script src="js/script.js"></script>
    <script src="js/date_pic/jquery.date_input.js"></script>
    <script src="lib/auto/js/jquery.autocomplete.js "></script>
    <script src="js/add_stock.js"></script>
</head>
<body>
<!-- TOP BAR -->
<?php include_once("tpl/top_bar.php"); ?>
<!-- end top-bar -->


<!-- HEADER -->
<div id="header-with-tabs">

    <div class="page-full-width cf">

        <ul id="tabs" class="fl">
            <li><a href="dashboard.php" class="dashboard-tab">Acceuil</a></li>
            <li><a href="view_sales.php" class="sales-tab">Ventes</a></li>
            <li><a href="view_customers.php" class=" customers-tab">Clients</a></li>
            <li><a href="view_purchase.php" class="purchase-tab">Achats</a></li>
            <li><a href="view_supplier.php" class=" supplier-tab">Fournisseurs</a></li>
            <li><a href="view_product.php" class="active-tab  stock-tab">Stocks / Produits</a></li>
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
                <li><a href="add_stock.php">Ajouter Stock/Produit</a></li>
                <li><a href="view_product.php">Afficher Stock/Produit</a></li>
                <li><a href="add_category.php">Ajouter Categorie de stock</a></li>
                <li><a href="view_category.php">Afficher Categories De stock</a></li>
                <li><a href="view_stock_availability.php">Afficher Stock Disponible</a></li>
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
                            'stockid' => 'required|max_len,200',
                            'sell' => 'required|max_len,200',
                            'cost' => 'required|max_len,200',
                            'supplier' => 'max_len,200',
                            'category' => 'max_len,200'

                        ));

                        $gump->filter_rules(array(
                            'name' => 'trim|sanitize_string|mysqli_escape',
                            'stockid' => 'trim|sanitize_string|mysqli_escape',
                            'sell' => 'trim|sanitize_string|mysqli_escape',
                            'cost' => 'trim|sanitize_string|mysqli_escape',
                            'category' => 'trim|sanitize_string|mysqli_escape',
                            'supplier' => 'trim|sanitize_string|mysqli_escape'

                        ));

                        $validated_data = $gump->run($_POST);
                        $name = "";
                        $stockid = "";
                        $sell = "";
                        $cost = "";
                        $supplier = "";
                        $category = "";


                        if ($validated_data === false) {
                            echo $gump->get_readable_errors(true);
                        } else {


                            $name = mysqli_real_escape_string($db->connection, $_POST['name']);
                            $stockid = mysqli_real_escape_string($db->connection, $_POST['stockid']);
                            $sell = mysqli_real_escape_string($db->connection, $_POST['sell']);
                            $cost = mysqli_real_escape_string($db->connection, $_POST['cost']);
                            $supplier = mysqli_real_escape_string($db->connection, $_POST['supplier']);
                            $category = mysqli_real_escape_string($db->connection, $_POST['category']);


                            $count = $db->countOf("stock_details", "stock_id ='$stockid'");
                            if ($count == 1) {
                                echo "<font color=red> Dublicat Entry. Please Verify</font>";
                            } else {

                                if ($db->query("insert into stock_details(stock_id,stock_name,stock_quatity,supplier_id,company_price,selling_price,category) values('$stockid','$name',0,'$supplier','$cost','$sell','$category')")) {
                                    echo "<br><font color=green size=+1 > Stock Ajouté avec succès</font>";
                                    $db->query("insert into stock_avail(name,quantity) values('$name',0)");
                                } else
                                    echo "<br><font color=red size=+1 >Erreur !</font>";

                            }


                        }

                    }


                    ?>

                    <form name="form1" method="post" id="form1" action="">


                        <table class="form" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <?php
                                $max = $db->maxOfAll("id", "stock_details");
                                $max = $max + 1;
                                $autoid = "ST" . $max . "";
                                ?>
                                <td><span class="man">*</span>ID&nbsp;Stock:</td>
                                <td><input name="stockid" type="text" id="stockid" readonly="readonly" maxlength="200"
                                           class="round default-width-input"
                                           value="<?php echo isset($autoid) ? $autoid : ''; ?>"/></td>

                                <td><span class="man">*</span>Nom:</td>
                                <td><input name="name" placeholder="Nom Du stock" type="text" id="name"
                                           maxlength="200" class="round default-width-input"
                                           value="<?php echo isset($name) ? $name : ''; ?>"/></td>

                            </tr>
                            <tr>
                                <td><span class="man">*</span>Prix:</td>
                                <td><input name="cost" placeholder="Prix du Stock" type="text" id="cost"
                                           maxlength="200" class="round default-width-input"
                                           onkeypress="return numbersonly(event)"
                                           value="<?php echo isset($cost) ? $cost : ''; ?>"/></td>

                                <td><span class="man">*</span>Prix de&nbsp;Vente</td>
                                <td><input name="sell" placeholder="Prix De Vente" type="text" id="sell"
                                           maxlength="200" class="round default-width-input"
                                           onkeypress="return numbersonly(event)"
                                           value="<?php echo isset($sell) ? $sell : ''; ?>"/></td>

                            </tr>
                            <tr>
                                <td>Fournisseur:</td>
                                <td><input name="supplier" placeholder="Nom Du Fournisseur" type="text" id="supplier"
                                           maxlength="200" class="round default-width-input"
                                           value="<?php echo isset($supplier) ? $supplier : ''; ?>"/></td>

                                <td>Categorie:</td>
                                <td><input name="category" placeholder="Catégorie" type="text" id="category"
                                           maxlength="200" class="round default-width-input"
                                           value="<?php echo isset($category) ? $category : ''; ?>"/></td>

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
                                           name="Submit" value="Enregistrer">
                                    (Control + S)

                                <td align="right"><input class="button round red   text-upper" type="reset" name="Reset"
                                                         value="Réinitialiser"></td>
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