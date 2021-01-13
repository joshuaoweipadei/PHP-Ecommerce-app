<?php
session_start();

function is_ajax(){
  return isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == 'xmlhttprequest';
}

if(is_ajax()){
  require_once("../dbconnect.php");
  $dbConn = new DBconnection();

  // cart-items count
  if(isset($_POST["cart_count"]) && $_POST["cart_count"] == "count"){
    if(!empty($_SESSION["cart_item"]))
      echo count($_SESSION["cart_item"]);
  }

} else {
  // This will redirect the user back to the home page if the request wasn't an ajax request
  header("Location: ../");
}