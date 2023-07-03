$(document).ready(function(){
    $("#searchBox").keyup(function(){

        var input  = $(this).val();

        if (input != ""){            
            $.ajax({
                url:"/shop/s/search?q=" + input,
                method:"POST",
                data:{input:input},
                cache:false,
                success:function(data){
                    $(".content").html(data);
                    $(".show").addClass("active");
                }
            });
        }else if(input === ""){
            $(".show").removeClass("active");
        }
    });
});