$(window).on("load", function() {

    $(".register-form").hide();
    $(".forgot-form").hide();

    if ($('.register-box').find('.alert').length)
    {
        $(".login-form").hide();
        $(".register-form").show();
        $(".register-box").css("height", "500px");
        $(".main-logo-box").css("height", "40%");
    }

    if ($('.forgot-box').find('.alert').length)
    {
        $(".login-form").hide();
        $(".forgot-form").show();
        $(".forgot-box").css("height", "260px");
        // $(".main-logo-box").css("height", "40%");
    }

    if ($('.login-box').find('.alert').length)
    {
        $(".login-box").css("height", "370px");
    }
    $(".remember-me-button").click(function(){
        $(this).toggleClass("ticked");
        $(".remember-me-checkbox").prop("checked", true);
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
