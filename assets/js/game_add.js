var max_quantity_per_product = 9;

$(".btn.btn-outline-dark").click(function()
{
    update = {};

    var quantity = $(".game-quantity-input").val();

    if (quantity)
    {
        //  0 < Quantity <= Max Quantity
        if (quantity <= max_quantity_per_product && quantity > 0)
        {
            update[$(".hidden-id").text()] = $(".game-quantity-input").val();
            pressed = true;

            //Asynchronous communication to set quantity to 0
            $.ajax({
              type: "POST",
              url: "includes/shopping_cart_update.php",
              data: "update=" + JSON.stringify(update),
              dataType: "json"
            });

            window.location.href = "shopping_cart.php";
        }
        else
        {
            $(".quantity-error").html("Please enter a number between 1-" + max_quantity_per_product).fadeIn();
        }
    }
    else
    {
        $(".quantity-error").html("Please enter a quantity.").fadeIn();
    }
});
