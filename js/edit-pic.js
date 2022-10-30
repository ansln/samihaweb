$(document).ready(function(){
    $(".editPicButton").click(function (e){
        e.preventDefault();

        $.get($(this).attr("href"), function(data){
            $("#editPic").html(data);
        });
    });
});

$(document).ready(function(){
    $('#form').on('submit', function(e){
        e.preventDefault();

        
        if (!$("#file").val()) {
            alert("Please select a file to upload!");
            window.location='./';
        }
        
        var formData = new FormData(this);
        var spinnerLoading = '<span class="loader2"></span>';
        
        $(".editPicBtn").html(spinnerLoading);
        
        $.ajax({
            url:"u/editPic_in.php",
            type:"POST",
            data:formData,
            cache:false,
            processData:false,
            contentType:false,
            
            success:function(data){
                $(".image").html(data);
            }
        });
    });

    $("#file").change(function (){
        var file = this.files[0];
        var fileType = file.type;
        var fileSize = file.size;
        var  match = ['image/jpeg', 'image/jpg', 'image/png'];

        if (!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]))){
           
            alert("Only image file!");
            window.location='./';
            return false;
            
        }if (fileSize > 5000000) {
            
            alert("File kegedean!");
            window.location='./';
            return false;

        }
    });
});