<?php
include_once("init.php");
?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>GS - Ventes</title>

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
        <script src="js/add_puchase.js"></script>
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
            <li><a href="view_purchase.php" class=" active-tab  purchase-tab">Achats</a></li>
            <li><a href="view_supplier.php" class=" supplier-tab">Fournisseurs</a></li>
            <li><a href="view_product.php" class=" stock-tab">Stocks / Produits</a></li>
            <li><a href="view_payments.php" class="payment-tab">Paiments / Outstandings</a></li>
            <li><a href="view_report.php" class="report-tab">Rapports</a></li>
        </ul>
                <!-- end tabs -->

                <!-- Change this image to your own company's logo -->
             

            </div>
            <!-- end full-width -->

        </div>
        <!-- end header -->


        <!-- MAIN CONTENT -->
        <div id="content">

            <div class="page-full-width cf">

                <div class="side-menu fl">

                 
                    <ul>
                        <li><a href="add_purchase.php">Ajouter Achat</a></li>
                        <li><a href="view_purchase.php">Afficher Achats </a></li>
                    </ul>

                </div>
                <!-- end side-menu -->

                <div class="side-content fr">

                    <div class="content-module">

                      
                        <!-- end content-module-heading -->

                        <div class="content-module-main cf">


                            <?php
                            //Gump is libarary for Validatoin
                           
                            if (isset($_GET['msg'])) {
                                echo $_GET['msg'];
                            }
                            if (isset($_POST['supplier']) and isset($_POST['stock_name'])) {
                                echo "<script>alert('fonction')</script>";
                                $_POST = $gump->sanitize($_POST);
                                $gump->validation_rules(array(
                                    'supplier' => 'required|max_len,100|min_len,3'
                                ));

                                $gump->filter_rules(array(
                                    'supplier' => 'trim|sanitize_string|mysqli_escape'
                                ));

                                $validated_data = $gump->run($_POST);
                                $supplier = "";
                                $purchaseid = "";
                                $stock_name = "";
                                $cost = "";
                                //$bill_no = "";


                                if ($validated_data === false) {
                                    echo $gump->get_readable_errors(true);
                                } else {
                                    $username = $_SESSION['username'];

                                    $purchaseid = mysqli_real_escape_string($db->connection, $_POST['purchaseid']);

                                    //$bill_no = mysqli_real_escape_string($db->connection, $_POST['bill_no']);
                                    $supplier = mysqli_real_escape_string($db->connection, $_POST['supplier']);
                                    $address = mysqli_real_escape_string($db->connection, $_POST['address']);
                                    $contact = mysqli_real_escape_string($db->connection, $_POST['contact']);
                                    $stock_name = $_POST['stock_name'];

                                    $count = $db->countOf("supplier_details", "supplier_name='$supplier'");
                                    
                                    if ($count == 0) {
                                        $db->query("insert into supplier_details(supplier_name,supplier_address,supplier_contact1) values('$supplier','$address','$contact')");
                                        echo "<script>alert('111111111')</script>";

                                    }
                                    $quty = $_POST['quty'];
                                    $date = date("d M Y h:i A");
                                    $sell = $_POST['sell'];
                                    $cost = $_POST['cost'];
                                    $total = $_POST['total'];
                                    $subtotal = $_POST['subtotal'];
                                    $description = mysqli_real_escape_string($db->connection, $_POST['description']);
                                    //$due = mysqli_real_escape_string($db->connection, $_POST['duedate']);
                                    //$payment = mysqli_real_escape_string($db->connection, $_POST['payment']);
                                    //$balance = mysqli_real_escape_string($db->connection, $_POST['balance']);
                                    $mode = mysqli_real_escape_string($db->connection, $_POST['mode']);

                                    $autoid = $_POST['purchaseid'];
                                    $autoid1 = $autoid;
                                    $selected_date = $_POST['date'];
                                    $selected_date = strtotime($selected_date);
                                    $date = date('Y-m-d H:i:s', $selected_date);
                                    //for ($i = 0; $i < count($stock_name); $i++) {
                                        $count = $db->countOf("stock_avail", "name='$stock_name'");
                                            echo "<script>alert('$stock_name')</script>";
                                        if ($count == 0) {
                                            $db->query("insert into stock_avail(name,quantity) values('$stock_name',$quty)");
                                            echo "<br><font color=green size=+1 >New Stock Entry Inserted !</font>";

                                            $db->query("insert into stock_details(stock_id,stock_name,stock_quatity,supplier_id,company_price,selling_price) values('$autoid','$stock_name',0,'$supplier','$cost','$sell')");


                                            $db->query("INSERT INTO stock_entries(stock_id,stock_name, stock_supplier_name, quantity, company_price, selling_price, opening_stock, closing_stock, date, username, type, total, payment, balance, mode, description, due, subtotal) VALUES ( '$autoid1','$stock_name','$supplier','$quty','$cost','$sell',0,'$quty','$date','$username','entry','$total','$payment','$balance','$mode','$description','$due','$subtotal')");
                                            echo "<script>alert('22222222')</script>";
                                        } else if ($count == 1) {

                                            $amount = $db->queryUniqueValue("SELECT quantity FROM stock_avail WHERE name='$stock_name'");
                                            $amount1 = $amount + $quty;
                                            $db->execute("UPDATE stock_avail SET quantity='$amount1' WHERE name='$stock_name'");
                                            $db->query("INSERT INTO stock_entries(stock_id,stock_name,stock_supplier_name,quantity,company_price,selling_price,opening_stock,closing_stock,date,username,type,total,mode,description,subtotal) VALUES ('$autoid1','$stock_name','$supplier','$quty','$cost','$sell','$amount','$amount1','$date','$username','entry','$total','$mode','$description','$subtotal')");
                                            //INSERT INTO `stock`.`stock_entries` (`id`, `stock_id`, `stock_name`, `stock_supplier_name`, `category`, `quantity`, `company_price`, `selling_price`, `opening_stock`, `closing_stock`, `date`, `username`, `type`, `salesid`, `total`, `payment`, `balance`, `mode`, `description`, `due`, `subtotal`, `count1`)
                                            //VALUES (NULL, '$autoid1', '$stock_name[$i]', '$supplier', '', '$quantity', '$brate', '$srate', '$amount', '$amount1', '$mysqldate', 'sdd', 'entry', 'Sa45', '432.90', '2342.90', '24.34', 'cash', 'sdflj', '2010-03-25 12:32:02', '45645', '1');
                                            echo "<script>alert('3333333333')</script>";
                                        }
                                    //}
                                    $msg = "<br><font color=green size=6px >Achat ajoute avec succes Ref: [" . $_POST['purchaseid'] . "] !</font>";
                                    echo "<script>window.location = 'add_purchase.php?msg=$msg';</script>";
                                }
                            }
                            ?>

                            <form name="form1" method="post" id="form1" action="">
                                <input type="hidden" id="posnic_total">

                                <p><strong>Ajouter Achat </strong> </p>
                                <table class="form" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                            <?php
                                $str = $db->maxOfAll("stock_id", "stock_entries"); 
                                $array = explode(' ', $str);                           
                                $autoid = ++$array[0];
                                if($str == ''){
                                  $autoid_new = "PR".$autoid;  
                                }
                                  ?>
                                        <?php if($str == ''){ ?>
                                        <td>ID:</td>
                                        <td><input name="purchaseid" type="text" id="purchaseid" readonly="readonly" maxlength="200"
                                                   class="round default-width-input" style="width:130px "
                                                   value="<?php echo $autoid_new ?>"/></td>
                                        
                                        <?php } ?>
                                        <?php if($str != ''){ ?>
                                        <td> ID:</td>
                                        <td><input name="purchaseid" type="text" id="purchaseid" readonly="readonly" maxlength="200"
                                                   class="round default-width-input" style="width:130px "
                                                   value="<?php echo $autoid ?>"/></td>
                                        <?php }?>
                                        <td>Date:</td>
                                        <td><input name="date" id="test1" placeholder=""  style="margin-left: 15px;" value="<?php date_default_timezone_set("Asia/Kolkata");
                                        echo date('Y-m-d H:i:s'); ?>"
                                                   type="text" id="name" maxlength="200" class="round default-width-input"/>
                                        </td>


                                    </tr>
                                    <tr>
                                        <td><span class="man">*</span>Fournisseur :</td>
                                        <td><input name="supplier" placeholder="" type="text" id="supplier"
                                                   maxlength="200" class="round default-width-input" style="width:130px "/></td>

                                        <td>Addresse:</td>
                                        <td><input name="address" placeholder="" type="text" id="address"
                                                   maxlength="200" class="round default-width-input"/></td>

                                        <td>contact:</td>
                                        <td><input name="contact" placeholder="" type="text" id="contact1"
                                                   maxlength="200" class="round default-width-input"
                                                   onkeypress="return numbersonly(event)" style="width:120px "/></td>

                                    </tr>
                                </table>
                                <input type="hidden" id="guid">
                                <input type="hidden" id="edit_guid">
                                <table class="form">
                                    <tr>
                                        <td>Produit</td>
                                        <td>Quantite</td>
                                        <td>Prix</td>
                                        <td>Prix de vente</td>
                                        <td>Disponible</td>
                                        <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total</td>
                                        <td> &nbsp;</td>
                                    </tr>
                                    <tr>

                                        <td><input name="stock_name" type="text" id="item" maxlength="200"
                                                   class="round default-width-input " style="width: 150px"/></td>

                                        <td><input name="quty" type="text" id="quty" maxlength="200"
                                                   class="round default-width-input my_with"
                                                   onKeyPress="quantity_chnage(event);return numbersonly(event);"
                                                   onkeyup="total_amount();unique_check()"/></td>

                                        <td><input name="cost" type="text" id="cost" readonly="readonly" maxlength="200"
                                                   class="round default-width-input my_with"/></td>


                                        <td><input name="sell" type="text" id="sell" readonly="readonly" maxlength="200"
                                                   class="round default-width-input my_with"/></td>


                                        <td><input name="" type="text" id="stock" readonly="readonly" maxlength="200"
                                                   class="round  my_with"/></td>


                                        <td><input name="total" type="text" id="total" maxlength="200"
                                                   class="round default-width-input " style="width:120px;  margin-left: 20px"/>
                                        </td>

                                    </tr>
                                </table>
                                <div style="overflow:auto ;max-height:300px;  ">
                                    <table class="form" id="item_copy_final">

                                    </table>
                                </div>

                                <table class="form">
                                    <tr>
                                        <td>Mode de paiement &nbsp;</td>
                                        <td>
                                            <select name="mode">
                                                
                                                <option value="cash">Cash</option>
                                                <option value="cheque">Cheque</option>

                                                <option value="other">Autre</option>
                                            </select>
                                        </td>
                                        <td>Description</td>
                                        <td><textarea name="description"></textarea></td>
                                        <td>Total:<input type="hidden" readonly="readonly" id="grand_total"
                                                               name="subtotal">
                                            <input type="text" id="main_grand_total" class="round default-width-input"
                                                   onkeypress="return numbersonly(event)" readonly="readonly"
                                                   style="text-align:right;width: 120px">
                                        </td>
                                        <td> &nbsp;</td>
                                        <td> &nbsp;</td>
                                        <td> &nbsp;</td>
                                    </tr>
                                </table>
                                <table class="form">
                                    <tr>
                                        <td>
                                            <input class="button round blue image-right ic-add text-upper" type="submit"
                                                   name="Submit" value="Ajouter" onclick="return checkValid(this);">
                                        </td>
                                        
                                        <td> &nbsp;</td>
                                        <td> <input class="button round red   text-upper" type="reset" id="Reset" name="Reset"
                                                   value="Reinitialiser"> </td>
                                    </tr>
                                </table>
                            </form>


                        </div>
                        <!-- end content-module-main -->


                    </div>
                    <!-- end content-module -->


                </div>
            </div>
            <!-- end full-width -->

        </div>
        <!-- end content -->


        <!-- FOOTER -->
       
        <!-- end footer -->

    </body>
</html>