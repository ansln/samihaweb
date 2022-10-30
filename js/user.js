// XHR FUNCTION
const resetPasswordBtn = document.getElementById("resetPasswordBtn");
resetPasswordBtn.addEventListener('click', goResetPage);
const addressPageBtn = document.getElementById("addressPageBtn");
addressPageBtn.addEventListener('click', goAddressPage);
const userOutBtn = document.getElementById("userOutBtn");
userOutBtn.addEventListener('click', userLogout);

function goResetPage() {
    window.location.replace("../reset-password");
}
function goAddressPage() {
    window.location.replace("address");
}
function userLogout() {
    window.location.replace("../logout.php");
}

$(document).ready(function(){
    $("#getName").on("submit", function(e){
        e.preventDefault();
        
        var formData = new FormData(this);
        
        $.ajax({
            url:"val?change-name",
            type:"POST",
            data:formData,
            cache:false,
            processData:false,
            contentType:false,
            
            success:function(data){
                $("#editPic").html(data);
                
                $("#modal-close").click(function (e) { 
                    e.preventDefault();
                    
                    window.location.replace(".");
                });
                document.addEventListener("keyup", e =>{
                    if (e.key === "Escape") {
                        location.reload();
                    }
                });
            }
        });
    });
});