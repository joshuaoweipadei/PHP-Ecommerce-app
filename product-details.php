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
  <div id="product__grid">
    <div class="product__details">
      <div>
        <div id="product__view">
          <?php 
            $productImageResult = $dbConn->runQuery("SELECT * FROM products WHERE code = '".$_GET["code"]."'");
            if(!empty($productImageResult)) {
          ?> 
          <div class="preview__image">
            <div id="preview__enlarged">
              <img src="<?php echo 'images/products/'.explode(',', $productImageResult[0]["image"])[0] ; ?>" />
            </div>
            <div id="thumbnail__container">
              <?php 
                $image_array = explode(',', $productImageResult[0]["image"]);
                foreach($image_array as $key => $value) { 
                  $focused = "";
                  if($key == 0) {
                    $focused = "focused";
                  }
              ?>
              <img class="thumbnail <?php echo $focused; ?>" src="<?php echo 'images/products/'.$image_array[$key] ; ?>" />
              <?php } ?>
            </div>
          </div>
          <?php } ?>

          <div class="product__info">
            <div class="product__title">
              <?php echo $productImageResult[0]["name"] ; ?>
            </div>
            <div>
              <?php echo $productImageResult[0]["price"] ; ?> NGN
            </div>
            <div>
              <button class="addCart">Add to Cart</button>
              <a href="index.php">
                <button class="info__link">Back to Shop</button>
              </a>
            </div>
          </div> 

        </div>
      </div>
    </div>
  </div>

  <script src="js/jquery/jquery-3.2.1.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>