$(document).ready(function(){
$("[onchange]").on('input', function(){
    if ($('input[name="password"]').val() == $('input[name="passwordCheck"]').val()) {
        document.querySelector('input[value="Register"]').disabled = false;
        $("#simple_error").html("");
    } else {
        document.querySelector('input[value="Register"]').disabled = true;
        $("#simple_error").html("Passwords don't match!");
    }
})
$(".in_summary").on("input", function(){
    var input, filter, table, tr, td, i, txtValue;
    input = $(this);
    input = input[0];
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");
    if ($(this).attr('name') == 'username') {
        x = 0;
    } else {
        x = 1;
    }
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[x];
      if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }
    }
})
$loggedInUser = getCookie("loggedInUser");
if($loggedInUser != "") {
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
    $(".profile_pic").dblclick(function(){
        $("#change_pic").fadeIn(function(){
            $("#choose_pic").click();
            $("#choose_pic").change(function(){
                $("#up_pic").click();
            });
        });
    });
    $(".in").on("input", function(){
        var selected_user = getUrlParameter('selected_user');
        var input_name = $(this).attr('name');
        var input_value = $(this).val();
        $.ajax({
            
            // The URL for the request
            url: "instant.php",
                
            // The data to send (will be converted to a query string)
            data: {
                "selected_user": selected_user,
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
            if (return_values != "") {
                obj = JSON.parse(return_values);
                $("#" + obj.input_name).html(obj[input_name]);
            } else {
                console.log("Quit messing around!");
            } 
        })
    });
}
});

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};
