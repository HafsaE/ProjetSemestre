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
    <script src="js/add_sales.js"></script>

    

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
            <li><a href="view_sales.php" class="active-tab sales-tab">Ventes</a></li>
            <li><a href="view_customers.php" class=" customers-tab">Clients</a></li>
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
                <li><a href="add_sales.php">Ajouter Vente</a></li>
                <li><a href="view_sales.php">Afficher Ventes</a></li>
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

                    if (isset($_POST['stock_name'])) {
                        echo "<script>alert('1')</script>";
                        $validated_data = $gump->run($_POST);
                        $stock_name = "";
                        $stockid = "";
                        $payment = "";
                        $bill_no = "";

                        if ($validated_data === false) {
                            echo $gump->get_readable_errors(true);
                        } else {
                            $username = $_SESSION['username'];

                            $stockid = mysqli_real_escape_string($db->connection, $_POST['stockid']);
                            //$bill_no = mysqli_real_escape_string($db->connection, $_POST['bill_no']);
                            $customer = mysqli_real_escape_string($db->connection, $_POST['supplier']);
                            $address = mysqli_real_escape_string($db->connection, $_POST['address']);
                            $contact = mysqli_real_escape_string($db->connection, $_POST['contact']);
                            $count = $db->countOf("customer_details", "customer_name='$customer'");
                            if ($count == 0) {
                                $db->query("insert into customer_details(customer_name,customer_address,customer_contact1) values('$customer','$address','$contact')");
                            }
                            $stock_name = $_POST['stock_name'];
                            $quty = $_POST['quty'];
                            $date = mysqli_real_escape_string($db->connection, $_POST['date']);
                            $sell = $_POST['sell'];
                            $total = $_POST['total'];
                            $payable = $_POST['subtotal'];
                            $description = mysqli_real_escape_string($db->connection, $_POST['description']);
                            //$due = mysqli_real_escape_string($db->connection, $_POST['duedate']);
                            //$payment = mysqli_real_escape_string($db->connection, $_POST['payment']);
                            $discount = mysqli_real_escape_string($db->connection, $_POST['discount']);
                            if ($discount == "") {
                                $discount = 00;
                            }
                            $dis_amount = mysqli_real_escape_string($db->connection, $_POST['dis_amount']);
                            if ($dis_amount == "") {
                                $dis_amount = 00;
                            }
                            $subtotal = mysqli_real_escape_string($db->connection, $_POST['payable']);
                            //$balance = mysqli_real_escape_string($db->connection, $_POST['balance']);
                            $mode = mysqli_real_escape_string($db->connection, $_POST['mode']);
                            $tax = mysqli_real_escape_string($db->connection, $_POST['tax']);
                            if ($tax == "") {
                                $tax = 00;
                            }
                            $tax_dis = mysqli_real_escape_string($db->connection, $_POST['tax_dis']);
                            $temp_balance = $db->queryUniqueValue("SELECT balance FROM customer_details WHERE customer_name='$customer'");
                            //$temp_balance = (int)$temp_balance + (int)$balance;
                            $db->execute("UPDATE customer_details SET balance=$temp_balance WHERE customer_name='$customer'");
                            //$selected_date = $_POST['due'];
                            //$selected_date = strtotime($selected_date);
                            //$mysqldate = date('Y-m-d H:i:s', $selected_date);
                            //$due = $mysqldate;
                            $str = $db->maxOfAll("transactionid", "stock_sales");
                          
                           
                            $array = explode(' ', $str);                           
                            $autoid = ++$array[0];
                            if($str == ''){
                            $autoid_new = "SL".$autoid;
                            }
                            //for ($i = 0; $i < count($stock_name); $i++) {
                                $name1 = $stock_name;
                                $quantity = $_POST['quty'];
                                $rate = $_POST['sell'];
                                $total = $_POST['total'];


                                $selected_date = $_POST['date'];
                                $selected_date = strtotime($selected_date);
                                $mysqldate = date('Y-m-d H:i:s', $selected_date);
                                $username = $_SESSION['username'];

                                $count = $db->queryUniqueValue("SELECT quantity FROM stock_avail WHERE name='$name1'");

                                if ($count >= 1) {

                                    if($str == ''){
                                    $db->query("insert into stock_sales (tax,tax_dis,discount,dis_amount,grand_total,transactionid,stock_name,selling_price,quantity,amount,date,username,customer_id,subtotal,payment,mode,description,billnumber)
                            values('$tax','$tax_dis','$discount','$dis_amount','$payable','$autoid_new','$name1','$rate','$quantity','$total','$mysqldate','$username','$customer','$subtotal','$payment','$mode','$description','$bill_no')");
                                    }
                                     if($str != ''){
                                    $db->query("insert into stock_sales (tax,tax_dis,discount,dis_amount,grand_total,transactionid,stock_name,selling_price,quantity,amount,date,username,customer_id,subtotal,payment,mode,description,billnumber)
                            values('$tax','$tax_dis','$discount','$dis_amount','$payable','$autoid','$name1','$rate','$quantity','$total','$mysqldate','$username','$customer','$subtotal','$payment','$mode','$description','$bill_no')");
                                     }
                                    $amount = $db->queryUniqueValue("SELECT quantity FROM stock_avail WHERE name='$name1'");
                                    $amount1 = $amount - $quantity;

                                    //$db->query("insert into stock_entries (stock_id,stock_name,quantity,opening_stock,closing_stock,date,username,type,salesid,total,selling_price,count1,billnumber) values('$autoid','$name1','$quantity','$amount','$amount1','$mysqldate','$username','sales','$autoid','$total','$rate',$i+1,'$bill_no')");
                                    //echo "<br><font color=green size=+1 >New Sales Added ! Transaction ID [ $autoid ]</font>" ;


                                    $amount = $db->queryUniqueValue("SELECT quantity FROM stock_avail WHERE name='$name1'");
                                    $amount1 = $amount - $quantity;
                                    $db->execute("UPDATE stock_avail SET quantity='$amount1' WHERE name='$name1'");

                                } else {
                                    echo "<br><font color=green size=+1 >Pas de Disponibles au stock</font>";
                                }


                            //}
                            $msg = "<br><font color=green size=6px >Vente Enregistrée avec Succès Ref: [" . $_POST['stockid'] . "] !</font>";
                            echo $msg;
                            if($str == ''){
                            echo "<script>window.open('add_sales_print.php?sid=$autoid_new','myNewWinsr','width=620,height=800,toolbar=0,menubar=no,status=no,resizable=yes,location=no,directories=no');</script>";
                            }
                            if($str != ''){
                            echo "<script>window.open('add_sales_print.php?sid=$autoid','myNewWinsr','width=620,height=800,toolbar=0,menubar=no,status=no,resizable=yes,location=no,directories=no');</script>";
                                
                            }
                            }
                        }

                    

                    ?>

                    <form name="form1" method="post" id="form1" action="">
                        <input type="hidden" id="posnic_total">

                        <p><strong>Ajouter Vente </strong> - Add New ( Control +2)</p>
                        <table class="form" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <?php
                                $str = $db->maxOfAll("transactionid", "stock_sales"); 
                                $array = explode(' ', $str);                           
                                $autoid = ++$array[0];
                                if($str == ''){
                                $autoid_new = "SL".$autoid;
                                }
                                  ?>
                                <?php if($str == ''){?>
                                <td>Note No:</td>
                                <td><input name="stockid" type="text" id="stockid" readonly="readonly" maxlength="200"
                                           class="round default-width-input" style="width:130px "
                                           value="<?php echo $autoid_new ?>"/></td>
                                <?php }?>
                                <?php if($str != ''){?>
                                <td>Note No:</td>
                                <td><input name="stockid" type="text" id="stockid" readonly="readonly" maxlength="200"
                                           class="round default-width-input" style="width:130px "
                                           value="<?php echo $autoid ?>"/></td>
                                <?php }?>
                                <td>Date:</td>
                                <td><input name="date" id="test1" placeholder="" value="<?php date_default_timezone_set("Asia/Kolkata");echo date('Y-m-d H:i:s');?>"
                                style="margin-left: 15px;"type="text" id="name" maxlength="200" class="round default-width-input"/>
                                </td>
                               
                                
                 
                            </tr>
                            <tr>
                                <td>Client:</td>
                                <td><input name="supplier" placeholder="ENTER CUSTOMER" type="text" id="supplier"
                                           value="anonymous" maxlength="200" class="round default-width-input" style="width:130px "/></td>

                                <td>Addresse:</td>
                                <td><input name="address" placeholder="ENTER ADDRESS" type="text" id="address"
                                           value="coast street"maxlength="200" class="round default-width-input"/></td>

                                <td>contact:</td>
                                <td><input name="contact" placeholder="ENTER CONTACT" type="text" id="contact1"
                                           value="9876543210"maxlength="200" class="round default-width-input"
                                           onkeypress="return numbersonly(event)" style="width:120px "/></td>

                            </tr>
                        </table>
                        <input type="hidden" id="guid">
                        <input type="hidden" id="edit_guid">
                        <table class="form">
                            <tr>
                                <td>Produit</td>
                                <td>Quantité</td>

                                <td>Prix</td>
                                <td>Disponibilité au stock</td>
                                <td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total</td>
                                <td> &nbsp;</td>
                            </tr>
                            <tr>

                                <td><input name="stock_name" type="text" id="item" maxlength="200"
                                           class="round default-width-input " style="width: 150px"/></td>

                                <td><input name="quty" type="text" id="quty" maxlength="200"
                                           class="round default-width-input my_with"
                                           onKeyPress="quantity_chnage(event);return numbersonly(event)"
                                           onkeyup="total_amount();unique_check();stock_size();"/></td>


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
                                <td> &nbsp;</td>
                                <td> &nbsp;</td>
                                <td><input type="checkbox" id="round" onclick="discount_type()">Réduction Par Montant</td>
                            </tr>
                            <tr>
                                <td> &nbsp;</td>
                                <td>Réduction %<input type="text" maxlength="3" class="round"
                                                     onkeyup=" discount_amount(); "
                                                     onkeypress="return numbersonly(event);" name="discount"
                                                     id="discount">
                                </td>

                                <td>Montant de Réduction:<input type="text" readonly="readonly"
                                                           onkeypress="return numbersonly(event);"
                                                           onkeyup=" discount_as_amount(); " class="round"
                                                           id="disacount_amount" name="dis_amount">
                                </td>
                                <td> &nbsp;</td>
                                <td> &nbsp;</td>
                                <td> &nbsp;</td>
                                <td> &nbsp;</td>
                                <td> &nbsp;</td>
                                <td> &nbsp;</td>
                                <td> &nbsp;</td>
                                <td> &nbsp;</td>
                                <td>Grand Total:<input type="hidden" readonly="readonly" id="grand_total"
                                                       name="subtotal">
                                    <input type="text" id="main_grand_total" readonly="readonly"
                                           class="round default-width-input" style="text-align:right;width: 120px">
                                </td>
                                
                            </tr>
                            <tr>
                                <td> &nbsp;</td>
                                <td> Taxe:<input type="text" id="tax" name="tax" onkeypress="return numbersonly(event);" onkeyup="add_tax();"></td>
                                <td> Description de Taxe:<input type="text" name="tax_dis"></td>
                                <td> &nbsp;</td>
                                <td> &nbsp;</td>
                                <td> &nbsp;</td>
                                <td> &nbsp;</td>
                                <td> &nbsp;</td>
                                <td> &nbsp;</td>
                                <td> &nbsp;</td>
                                <td> &nbsp;</td>
                                <td>Montant A Payer:<input type="hidden" readonly="readonly" id="grand_total">
                                    <input type="text" id="payable_amount" readonly="readonly" name="payable"
                                           class="round default-width-input" style="text-align:right;width: 120px">
                                </td>
                                
                            </tr>
                        </table>
                        <table class="form">
                            <tr>
                                <td>Mode &nbsp;</td>
                                <td>
                                    <select name="mode">
                                        <option value="cash">Cash</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="other">Autre</option>
                                    </select>
                                </td>                         
                                <td> &nbsp;</td>
                                <td> &nbsp;</td>
                                <td>Description</td>
                                <td><textarea name="description"></textarea></td>
                                <td> &nbsp;</td>
                                <td> &nbsp;</td>
                            </tr>
                        </table>
                        <table class="form">
                            <tr>
                                <td>
                                    <input class="button round blue image-right ic-add text-upper" type="submit"
                                           name="Submit" value="Enregistrer">
                                </td>
                                <td> (Control + S)
                                    </td>
                                <td> &nbsp;</td>
                                <td> <input class="button round red   text-upper" type="reset" id="Reset" name="Reset"
                                           value="Réinitialiser"></td>
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
<div id="footer">
    

</div>
<!-- end footer -->

</body>
</html>