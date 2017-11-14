$('#deleteAcc').find('form').on('submit', function(e)
{
      if(grecaptcha.getResponse() == "")
      {
            e.preventDefault();
            $(".hidden-tick").animate({opacity: "100", height: "50px", padding: "10px"});
      }
});
