$(document).ready(function(){
    $('#testingAdd').on('submit', function(e){
        e.preventDefault();

        var formData = new FormData(this);
        var spinnerLoading = '<span class="loader2"></span>';
        $("#chooseAdddressBtn").html(spinnerLoading);
        
        $.ajax({
            url:"val?scAdd",
            type:"POST",
            data:formData,
            cache:false,
            processData:false,
            contentType:false,
            
            success:function(data){
                $("#show").html(data);
            }
        });
    });
});