$(window).on("load", function() {
    $(".register-form").hide();
    $(".forgot-form").hide();
    setTimeout(function() { $('#loading').fadeOut(); }, 400);
    $(".remember-me-button").click(function(){
        $(this).toggleClass("ticked");
    });
    $(".register-switch").click(function(){
        $(".login-form").fadeOut();
        setTimeout(function() { $(".register-form").fadeIn(); }, 400);
    });
    $(".login-switch").click(function(){
        $(".register-form").fadeOut();
        setTimeout(function() { $(".login-form").fadeIn(); }, 400);
    });
    $(".forgot-pass-text").click(function(){
        $(".login-form").fadeOut();
        setTimeout(function() { $(".forgot-form").fadeIn(); }, 400);
    });
    $(".login-register-return").click(function(){
        $(".forgot-form").fadeOut();
        setTimeout(function() { $(".login-form").fadeIn(); }, 400);
    });
    $("input[type=\"range\"]").click(function(){
        $(".forgot-form").fadeOut();
        setTimeout(function() { $(".login-form").fadeIn(); }, 400);
    });
});
