<?php
include_once("init.php");

?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>POSNIC - Update Payment</title>

    <!-- Stylesheets -->

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="js/date_pic/date_input.css">

    <!-- Optimize for mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- jQuery & JS files -->
    <?php include_once("tpl/common_js.php"); ?>
    <script src="js/date_pic/jquery.date_input.js"></script>
    <script src="js/script.js"></script>
    <script src="js/update_out_standing.js"></script>
   
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
            <li><a href="view_product.php" class=" stock-tab">Stocks / Produits</a></li>
            <li><a href="view_payments.php" class="active-tab payment-tab">Paiments </a></li>
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
                <li><a href="view_payments.php">Paiements</a></li>
                <li><a href="view_out_standing.php">Out standings</a></li>
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
                                $balance = mysqli_real_escape_string($db->connection, $_POST['balance']);
                                $payment = mysqli_real_escape_string($db->connection, $_POST['payment']);
                                $supplier = mysqli_real_escape_string($db->connection, $_POST['supplier']);
                                $subtotal = mysqli_real_escape_string($db->connection, $_POST['total']);
                                $newpayment = mysqli_real_escape_string($db->connection, $_POST['new_payment']);
                                $selected_date = $_POST['date'];
                                $selected_date = strtotime($selected_date);
                                $mysqldate = date('Y-m-d H:i:s', $selected_date);
                                $due = $mysqldate;
                                $balance = (int)$balance - (int)$newpayment;
                                $payment = (int)$payment + (int)$newpayment;

                                if ($db->query("UPDATE stock_entries  SET balance='$balance',payment='$payment',due='$due' where stock_id='$id'")) {
                                    $db->query("INSERT INTO transactions(type,supplier,payment,balance,rid,due,subtotal) values('entry','$supplier','$newpayment','$balance','$id','$due','$subtotal')");
                                    echo "<br><font color=green size=+1 > Modification Réussie</font>";
                                } else
                                    echo "<br><font color=red size=+1 >Erreur</font>";


                            }
                            ?>
                            <?php
                            if (isset($_GET['sid']))
                                $id = $_GET['sid'];

                            $line = $db->queryUniqueObject("SELECT * FROM stock_entries WHERE stock_id='$id'");
                            ?>
                            <form name="form1" method="post" id="form1" action="">

                                <input name="id" type="hidden" value="<?php echo $_GET['sid']; ?>">
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>Id Vente</td>
                                    <td><input name="stock_id" type="text" readonly="readonly" readonly="readonly"
                                               id="stockid" maxlength="200" class="round default-width-input"
                                               value="<?php echo $line->stock_id; ?> "/>
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>Client</td>
                                    <td><input name="customer" type="text" id="customer" maxlength="200"
                                               readonly="readonly" class="round default-width-input"
                                               value="<?php echo $line->stock_supplier_name; ?> "/></td>
                                    <td>Total</td>
                                    <td><input name="total" type="text" id="tatal" maxlength="20" readonly="readonly"
                                               class="round default-width-input"
                                               value="<?php echo $line->subtotal; ?>"/></td>
                                </tr>

                                <tr>
                                    <td>&nbsp;</td>
                                    <td>Payé</td>
                                    <td><input name="paid" type="text" id="paid" maxlength="20" readonly="readonly"
                                               class="round default-width-input"
                                               value="<?php echo $line->payment; ?>"
                                               onkeypress="return numbersonly(event)"/></td>
                                    <td>Balance</td>
                                    <td><input name="balance" type="text" id="balance" readonly="readonly"
                                               maxlength="20" class="round default-width-input"
                                               value="<?php echo $line->balance; ?>"
                                               onkeypress="return numbersonly(event)"/></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Nouvelle date</td>
                                    <td><input name="date" type="text" id="test1" maxlength="20"
                                               class="round default-width-input"
                                               value="<?php echo date("Y/m/d"); ?>"/></td>
                                    <td>Nouveau Paiement</td>
                                    <td><input name="new_payment" id="new_payment" type="text"
                                               onkeypress="return numbersonly(event)" maxlength="20"
                                               onkeyup="change_balance()" class="round default-width-input"
                                            /></td>
                                </tr>


                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>
                                        <input class="button round blue image-right ic-add text-upper" type="submit"
                                               name="Submit" value="Enregistrer">
                                        (Control + S)
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