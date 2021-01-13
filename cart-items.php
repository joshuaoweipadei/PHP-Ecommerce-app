<?php
session_start();

require_once("dbconnect.php");
$dbConn = new DBconnection();


if($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST["applyDiscount"])){
  if(!empty($_GET["action"]) && $_GET["action"] === "discount_price"){
    if(!empty($_SESSION["cart_item"])){
      if(!empty($_POST["discountCode"])){
        $priceByCode = $dbConn->runQuery("SELECT price FROM discount_coupon WHERE discount_code = '".$_POST["discountCode"]."'");
        if(!empty($priceByCode)){
          foreach ($priceByCode as $key => $value) {
            $discountPrice = $priceByCode[$key]["price"];
          }
          if(!empty($discountPrice) && $discountPrice > $_POST["totalPrice"]){
            $message = "Invalid Discount Coupon";
          }
        } else {
          $message = "Invalid Discount Coupon";
        }
      }
    } else {
      $message = "Not applicable. The cart is empty";
    }
  }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/main.css">
  <title>PHP E-Commerce</title>
</head>
<body>
  <!-- <div id="josh">ff</div> -->
  <form id="applyDiscountForm" method="POST" action="?action=discount_price" onsubmit="return validateDiscount();">
    <div id="shopping__cart">
      <div class="shopping__cartHeader">
        <div class="txt__heading">Your Cart Items</div>
        <?php if(!isset($_SESSION["cart_item"])) { ?>
        <a id="viewCart" href="index.php">Continue Shopping</a>
        <?php } else { ?>
        <button type="button" id="btnEmpty" onClick="cartAction('empty')">Empty Cart</button>
        <?php } ?>
      </div>
        <?php
        if(isset($_SESSION["cart_item"])) {
          $total_quantity = 0;
          $total_price = 0;
        ?>	
          <table class="tbl__cart" cellpadding="10" cellspacing="1">
            <thead>
              <tr>
                <th scope="col" style="text-align: left;" width="50%">Name</th>
                <th scope="col" style="text-align: left;" width="15%">Code</th>
                <th scope="col" style="text-align: right;" width="5%">Qty</th>
                <th scope="col" style="text-align: right;" width="10%">Unit Price</th>
                <th scope="col" style="text-align: right;" width="10%">Price</th>
                <th scope="col" style="text-align: center;" width="10%">Remove</th>
              </tr>	
            </thead>
            <tbody>
              <?php
              foreach ($_SESSION["cart_item"] as $item) {
                $item_price = $item["qty"] * $item["price"];
              ?>
              <tr>
                <td data-label="Name">
                  <img src="<?php echo "images/products/".explode(',', $item["image"])[0]; ?>" class="cart__itemImage" />
                  <?php echo $item["name"]; ?>
                </td>
                <td data-label="Code"><?php echo $item["code"]; ?></td>
                <td data-label="Qty" style="text-align: right;"><?php echo $item["qty"]; ?></td>
                <td data-label="Unit Price" style="text-align: right;"><?php echo "$ " . $item["price"]; ?></td>
                <td data-label="Price" style="text-align: right;"><?php echo "$ " . number_format($item_price, 2); ?></td>
                <td style="text-align: center;">
                  <button type="button" onCLick="cartAction('remove', '<?php echo $item["code"]; ?>')" class="btnRemoveAction">
                    <img src="images/brand/delete-icon.png" alt="Remove Item" />
                  </button>
                </td>
              </tr>
              <?php
                $total_quantity += $item["qty"];
                $total_price += ($item["price"] * $item["qty"]);
              }
              ?>
              <tr>
                <td colspan="2" align="right">Total:
                  <input type="hidden" name="totalPrice" id="totalPrice" value="<?php echo $total_price; ?>">
                </td>
                <td align="right"><?php echo $total_quantity; ?></td>
                <td align="right" colspan="2"><strong><?php echo "$ " . number_format($total_price, 2); ?></strong></td>
                <td></td>
              </tr>
              <?php     
              if(!empty($discountPrice) && $total_price > $discountPrice) {
                $total_price_after_discount = $total_price - $discountPrice;
              ?>
              <tr>
                <td colspan="3" align="right">Discount:
                  <input type="hidden" name="discountPrice" id="discountPrice" value="<?php echo $discountPrice; ?>">
                </td>
                <td align="right" colspan="2"><strong><?php echo "$ " . number_format($discountPrice, 2); ?></strong></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="3" align="right">Total after Discount:</td>
                <td align="right" colspan="2"><strong><?php echo "$ " . number_format($total_price_after_discount, 2); ?></strong></td>
                <td></td>
              </tr>
              <?php 
                }
              ?>
            </tbody>
          </table>		
          <?php
            } else {
          ?>
        <div class="no-records">Your Cart is Empty</div>
      <?php
        }
      ?>

      <?php if(isset($_SESSION["cart_item"])) { ?>
      <div id="discount-grid">
        <div class="discount-section">
          <div class="discount-action">
            <div class="txt__heading">Apply Coupon Code</div>
            <input type="text" class="discount-code" id="discountCode" name="discountCode"  placeholder="Enter Coupon Code" />
            <button id="btnDiscountAction" type="submit" name="applyDiscount" class="btnDiscountAction" >Apply Discount</button>
          </div>
          <span id="error-msg-span" class="error-message">
            <?php
              if(!empty($message)) {
                echo $message;
              }
            ?>
          </span>
        </div>
      </div>
      <?php } ?>
      
    </div>
  </form>

  <script src="js/jquery/jquery-3.2.1.min.js"></script>
  <script src="js/main.js"></script>
  <script>
  function validateDiscount() {
    var valid = true;

    if($("#discountCode").val() == "") {
      valid = false;
    }

    if(valid == false) {
      $("#error-msg-span").text("Discount Coupon Required");
    }
    return valid;
  }
</script>
</body>
</html>

<?php
// unset($_SESSION["cart_item"]);