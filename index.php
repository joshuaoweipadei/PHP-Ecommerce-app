<?php
session_start();

require_once("dbconnect.php");
$dbConn = new DBconnection();


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

  <div>
    <div id="product__grid">
      <div class="shopping__cartHeader">
        <div class="text__heading">Products</div>
        <a id="viewCart" href="cart-items.php">View Cart <span id="cartItem_count"></span></a>
      </div>
      <div class="product__container">
        <?php
          $product_array = $dbConn->runQuery("SELECT * FROM products ORDER BY id ASC");
            if(!empty($product_array)) {
              foreach($product_array as $key => $value) {
                // print_r($product_array);
        ?>
          <div class="product__item">
            <form>
              <a href="product-details.php?code=<?php echo $product_array[$key]["code"]; ?>" class="product__link">
                <div class="product__image">
                  <img src="<?php echo 'images/products/'.explode(',', $product_array[$key]["image"])[0]; ?>">
                </div>
                <div class="product__tile__footer">
                  <div class="product__title"><?php echo $product_array[$key]["name"]; ?></div>
                  <div class="product__price"><?php echo "$" . $product_array[$key]["price"]; ?></div>
                </div>
              </a>
              <div class="cart__action">
                <input type="text" class="product__quantity" id="qty_<?php echo $product_array[$key]["code"]; ?>" name="quantity" value="1" size="2" />
                <input type="button" value="Add to Cart" class="btnAddAction" onClick="cartAction('add', '<?php echo $product_array[$key]["code"]; ?>')" />
              </div>
            </form>
          </div>
        <?php
              }
            }
        ?>
      </div>
    </div>
  </div>

  <script src="js/jquery/jquery-3.2.1.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>

<?php
// unset($_SESSION["cart_item"]);