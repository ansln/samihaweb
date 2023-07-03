$(document).ready(function(){
    $('#product-list').on('click', function(e){
        e.preventDefault();
        
        var spinnerLoading = '<div class="loader-container"><span class="loader2"></span></div>';
        $("#post-data").html(spinnerLoading);
        
        $.ajax({
            url:"page/product",
            type:"POST",
            cache:false,
            processData:false,
            contentType:false,
            
            success:function(data){
                $("#post-data").html(data);
                hideSideBar();
            }
        });
    });
    
    $('#transaction').on('click', function(e){
        e.preventDefault();
        
        var spinnerLoading = '<div class="loader-container"><span class="loader2"></span></div>';
        $("#post-data").html(spinnerLoading);
        
        $.ajax({
            url:"page/transaction-list",
            type:"POST",
            cache:false,
            processData:false,
            contentType:false,
            
            success:function(data){
                $("#post-data").html(data);
                hideSideBar();
            }
        });
    });

    $('#content').on('click', function(e){
        e.preventDefault();
        
        var spinnerLoading = '<div class="loader-container"><span class="loader2"></span></div>';
        $("#post-data").html(spinnerLoading);
        
        $.ajax({
            url:"page/article.php",
            type:"POST",
            cache:false,
            processData:false,
            contentType:false,
            
            success:function(data){
                $("#post-data").html(data);
                hideSideBar();
            }
        });
    });


});

const logoutBtn = document.getElementById("logout");
logoutBtn.addEventListener("click", function() {
    window.location.replace('logout');
});

const getTime = function(){
	document.getElementById("dgTime").innerHTML = new Date().toLocaleString("en-US",{timeZone:'Asia/Bangkok', timeStyle:'medium', hourCycle:'h23'});
}
getTime();
setInterval(getTime,1000);

const reload = document.getElementById('reload');
reload.addEventListener('click', reloadPage);
const inputProduct = document.getElementById('add-product');
inputProduct.addEventListener('click', inputProductPage);
const inputPromotion = document.getElementById('promotion');
inputPromotion.addEventListener('click', inputPromotionPage);
const editPromotion = document.getElementById('edit-promotion');
editPromotion.addEventListener('click', editPromotionPage);

function loading(){
   var el = document.getElementById('post-data');
   el.innerHTML='<span class="loader2"></span>';
}

function reloadPage(){
    window.location.replace("../admin/");
}

function inputProductPage(){
    window.location.replace('input/index');
}

function inputPromotionPage(){
    window.location.replace('promotion-input/index');
}

function editPromotionPage(){
    window.location.replace('banner/');
}

function hideSideBar(){
    $(".rw").hide();
    $("#homeBtn").hide();
    $("#dashboardBtn").hide();
    $("#productListBtn").hide();
    $("#addProductBtn").hide();
    $("#transactionListBtn").hide();
    $("#userListBtn").hide();
    $("#promoBtn").hide();
    $("#logoutBtn").hide();
    $("#addContentBtn").hide();
    $("#addPromotionBtn").hide();
    $("#editPromotion").hide();
    $(".link-icon").css("justify-content", "center");
    $(".link-icon").css("padding", "20px");
    $(".link-icon").css("margin", "0 0 10px 0");
}