var latest = [];
var num = $(".catalogue > .container").length;
var empty = false;

$('select').change(function () {
    var a = $(this).parents().siblings(".base-price").html();
    $(this).parents().siblings(".price").html("€" + (a * $(this).val()).toFixed(2));
});

$(".fa.fa-trash").click(function(){
    latest.push($(this));
    $(this).closest(".container.wishlist-entry").animate({opacity: "0", height: "0px"});
    setTimeout( function() {
        $(".alert-primary").animate({padding: ".75rem 1.25rem", height: "64px"});
        $(".alert-primary > button").fadeIn();
    }, 400);
    if (latest.length === num) {
        setTimeout( function() {
            $(".empty").toggleClass("visible");
            $(".catalogue").css("text-align", "center");
            empty = true;
        }, 400);
    }
});

$(".alert-primary > button").click(function(){
    if (empty) {
        $(".empty").toggleClass("visible");
        setTimeout(function(){
            $(".catalogue").css("text-align", "inherit");
            empty = false;
            $(latest.pop()).closest(".container.wishlist-entry").animate({opacity: "100", height: "180px"});
            if (!latest.length) {
                $(".alert-primary > button").fadeOut();
                $(".alert-primary").animate({padding: "0px", height: "0px"});
            }
        }, 300);
    }
    else {
        $(latest.pop()).closest(".container.wishlist-entry").animate({opacity: "100", height: "180px"});
        if (!latest.length) {
            $(".alert-primary > button").fadeOut();
            $(".alert-primary").animate({padding: "0px", height: "0px"});
    }
    }
});
