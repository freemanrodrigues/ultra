$(document).ready(function() 
{
//alert("Loaded");
    $("#icon_click").click(function() 
    {
        $(this).toggleClass("fas fa-eye fas fa-eye-slash");
        var type = $(this).hasClass("fas fa-eye-slash") ? "text" : "password";
        $("#new_password").attr("type", type);
    });
});
