const userEmail = document.getElementById("userEmail");

$(document).ready(function(){
    $('#form').on('submit', function(e){
        e.preventDefault();

        if (userEmail.value != "") {
            var formData = new FormData(this);
            var spinnerLoading = '<span class="loader2"></span>';
            
            $(".submitBtn").html(spinnerLoading);
            nextVer(formData);
        }else{
            $("span").fadeIn();
            $("span").fadeOut(5000);
        }
    });

    function nextVer(formData){
        $.ajax({
            url:"../auth/reset.php",
            type:"POST",
            data:formData,
            cache:false,
            processData:false,
            contentType:false,
            
            success:function(data){
                $(".msg").html(data);
            }
        });
    }
});