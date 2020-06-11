function checkPass() 
{
    pass1 = document.querySelector('input[name="password"]').value;
    pass2 = document.querySelector('input[name="passwordCheck"]').value;
    if (pass1 == pass2) {
        document.querySelector('input[name="passwordCheck"]').style.boxShadow = "10px 20px 30px darkgreen";
        document.querySelector('input[value="Register"]').disabled = false;
    } else {
        document.querySelector('input[name="passwordCheck"]').style.boxShadow = "10px 20px 30px darkred";
        document.querySelector('input[value="Register"]').disabled = true;
    }

}
$(document).ready(function(){
    
    $(".switchTo").dblclick(function(){
        var attr = $(this).attr('id');
        $(this).fadeOut(400, "swing", function(){
            $(".in[name=" + attr).fadeIn().select().focusout(function(){
                $(this).fadeOut(1, "linear", function(){
                    $(".switchTo").fadeIn();
                });
            });
        });
    });
    $("#profile_pic").dblclick(function(){
        $("#change_pic").fadeIn(function(){
            $("#choose_pic").click();
            $("#choose_pic").change(function(){
                $("#up_pic").click();
            });
        });
    });
    $(".in").bind("keyup change", function(){
        var input_name = $(this).attr('name');
        var input_value = $(this).val();
        $.ajax({
    
            // The URL for the request
            url: "instant.php",
        
            // The data to send (will be converted to a query string)
            data: {
                "input_name": input_name,
                "input_value": input_value
            },
        
            // Whether this is a POST or GET request
            type: "POST",

            success: function(data){
                return_values = data;
            }
        });
        $(document).ajaxComplete(function(){
            obj = JSON.parse(return_values);
            $("#" + obj.input_name).html(obj[input_name]);
        })
    });
});
