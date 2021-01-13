$("#thumbnail__container img").click(function() {
  $("#thumbnail__container img").css("border", "1px solid #ccc");

  var src = $(this).attr("src");
  $("#preview__enlarged img").attr("src", src);
  $(this).css("border", "#fbb20f 2px solid");
});

function cartItemCount() {
  $.ajax({
    url: "ajax/cart-item-count.php",
    method : "POST",
    data : {
      cart_count : "count"
    },
    success : function(data) {
      $("#cartItem_count").html(data);
    }
  })
}

cartItemCount();

$(document).ready(function(){
  cartItemCount();
});

function cartAction(action, product_code){
  var queryString = "";
  var qty = $("#qty_"+product_code).val();

  if(action != ""){
    switch (action) {
      case "add":
        queryString = 'action='+action+'&code='+product_code+"&qty="+qty
        break;
      case "remove":
        queryString = 'action='+action+'&code='+product_code;
        break;
      case "empty":
        queryString = 'action='+action;
        break;
    }
  }

  $.ajax({
    url: "ajax/cart-action.php",
    data: queryString,
    type: "POST",
    success: function(data){
      cartItemCount();
      if(action != ""){
        switch (action) {
          case "add":
            console.log("add")
            break;
          case "remove":
            console.log("remove")
            $('#applyDiscountForm').html(data);
            break;
          case "empty":
            console.log("empty")
            $('#applyDiscountForm').html(data);
            break;
        }
      }
    }
  });
}