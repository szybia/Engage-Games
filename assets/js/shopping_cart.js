//Stack used for remembering deleted products
var latest = [];

//Stack used for remembering product div sizes (JQuery animate doesn't allow for height resetting)
var heights = [];

//Number of items in shopping cart
var num = $(".catalogue > .container").length;

//Empty shopping cart check
var empty = false;

//JSON dictionary used to send AJAX updates on interactions
var update = {}

if (num === 0)
{
    $(".total").toggleClass("invisible");
    $(".empty").css("margin-top", "30px");
    $(".sad-face").css("display", "block");
    setTimeout( function()
    {
        $(".empty").toggleClass("visible");
        $(".catalogue").css("text-align", "center");
        empty = true;
    }, 400);
}
else
{
    //Updates total price
    function total_price()
    {
        var total = 0;
        //Iterate through all prices and add up
        $( ".price" ).each(function(index)
        {
          total += parseFloat($( this ).text().substring(1));
        });
        total = total.toFixed(2);
        //Fade out effect
        $(".total").toggleClass("invisible");
        setTimeout( function()
        {
            $(".total").html("Your total: €" + total);
            $(".total").toggleClass("invisible");
        }, 350);
    }

    //Call total price to initiate price
    total_price();

    //Change item price when new quantity is selected
    $('select').change(function ()
    {
        var a = $(this).parents().siblings(".base-price").html();
        $(this).parents().siblings(".price").html("€" + (a * $(this).val()).toFixed(2));
        total_price();

        //Item is deleted meaning 0 quantity
        update[$(this).parents().siblings(".game-id").html()] = $(this).val();

        //Asynchronous communication to set quantity to 0
        $.ajax({
          type: "POST",
          url: "includes/shopping_cart_update.php",
          data: "update=" + JSON.stringify(update),
          dataType: "json"
        });

        //Reset dictionary
        update = {};
    });

    //When item is deleted
    $(".fa.fa-trash").click(function()
    {
        $(".numberCircle").html(parseInt($(".numberCircle").text()) - 1)

        //Push deleted item to stack for undoing
        latest.push($(this));

        //Push deleted item height for setting height when undoing
        heights.push($(this).closest(".container.wishlist-entry").outerHeight());

        //Hide element and set price to 0 (To ensure total price function calculates the correct price)
        $(this).closest(".container.wishlist-entry").animate({opacity: "0", height: "0px"});
        $(this).siblings(".price").html("€0");

        //Refresh total price
        total_price();

        //Undo button appear
        setTimeout( function()
        {
            $(".alert-primary").animate({padding: ".75rem 1.25rem", height: "64px"});
            $(".alert-primary > button").fadeIn();
        }, 400);

        //If all products in stack (empty shopping cart) remove total price, inform user cart is empty
        if (latest.length === num)
        {
            $(".total").toggleClass("invisible");
            setTimeout( function()
            {
                $(".empty").toggleClass("visible");
                $(".catalogue").css("text-align", "center");
                empty = true;
            }, 400);
        }

        //Item is deleted meaning 0 quantity
        update[$(this).siblings(".game-id").html()] = 0;

        //Asynchronous communication to set quantity to 0
        $.ajax({
          type: "POST",
          url: "includes/shopping_cart_update.php",
          data: "update=" + JSON.stringify(update),
          dataType: "json"
        });

        //Reset dictionary
        update = {};
    });

    //If user wants to undo
    $(".alert-primary > button").click(function()
    {
        $(".numberCircle").html(parseInt($(".numberCircle").text()) + 1)

        //Pop off last item deleted and store in variable
        var last_pop = latest.pop();

        //Get sibling of class price (price * quantity) and sets its content to €(.base-price * select quantity)
        $(last_pop).siblings(".price").html("€" + (($(last_pop).siblings(".base-price").html()) * $(last_pop).siblings("form").find("select").val()).toFixed(2));

        //Update total price
        total_price();

        //If shopping cart is empty remove empty text and reappear the first deleted cart entry
        if (empty)
        {
            $(".empty").toggleClass("visible");

            //Reappear total price since cart is no longer empty and reveal last product
            setTimeout(function()
            {
                $(".total").toggleClass("invisible");
                $(".catalogue").css("text-align", "inherit");
                empty = false;
                $(last_pop).closest(".container.wishlist-entry").animate({opacity: "100", height: heights.pop()});

                //If no more products in stack remove Undo button
                if (!latest.length)
                {
                    $(".alert-primary > button").fadeOut();
                    $(".alert-primary").animate({padding: "0px", height: "0px"});
                }
            }, 300);
        }
        //If shopping cart isn't empty reappear the last item
        else
        {
            $(last_pop).closest(".container.wishlist-entry").animate({opacity: "100", height: heights.pop()});

            //If no more products in stack remove Undo button
            if (!latest.length)
            {
                $(".alert-primary > button").fadeOut();
                $(".alert-primary").animate({padding: "0px", height: "0px"});
            }
        }

        //Item is restored meaning X quantity
        update[$(last_pop).siblings(".game-id").html()] = $(last_pop).siblings("form").find("select").val();

        //Asynchronous communication to set quantity back to X
        $.ajax({
          type: "POST",
          url: "includes/shopping_cart_update.php",
          data: "update=" + JSON.stringify(update),
          dataType: "json"
        });

        //Reset dictionary
        update = {};
    });
}
