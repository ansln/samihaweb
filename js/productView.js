const imgContElm = document.querySelector(".img-container");
const imgElm = document.querySelector(".img-container img");
const listProductsElm = document.querySelector(".img-container");
const imgZoom = 200;

// small image onclick
$('.image-link').on('click', function(){
    $('.image-link').removeClass('active');
    var getImg = document.querySelector('#bigImage');
    getImg.src = this.src;
    $(this).addClass('active');
});

//event mouse enter
imgContElm.addEventListener('mouseenter', function(){
    imgElm.style.width = imgZoom + '%';
});
imgContElm.addEventListener('mouseleave', function(){
    imgElm.style.width = '100%';
    imgElm.style.top = '0';
    imgElm.style.left = '0';
});
imgContElm.addEventListener('mousemove', function(mouseEvent){
    let obj = imgContElm;
    let obj_left = 0;
    let obj_top = 0; 
    let xpos; 
    let ypos;

    while (obj.offsetParent) {
        obj_left += obj.offsetLeft;
        obj_top += obj.offsetTop;
        obj = obj.offsetParent;
    }
    if (mouseEvent) {
        xpos = mouseEvent.pageX;
        ypos = mouseEvent.pageY;
    }else{
        xpos = window.event.x + document.body.scrollLeft - 2;
        ypos = window.event.y + document.body.scrollTop - 2;
    }
    xpos -= obj_left;
    ypos -= obj_top;

    const imgWidth = imgElm.clientWidth;
    const imgHeight = imgElm.clientHeight;

    imgElm.style.top = -(((imgHeight - this.clientHeight) * ypos) / this.clientHeight) + 'px';
    imgElm.style.left = -(((imgWidth - this.clientWidth) * xpos) / this.clientWidth) + 'px';
});

function changeHeight(){
    imgContElm.style.height = imgContElm.clientWidth + 'px';
}
changeHeight();

window.addEventListener('resize', changeHeight);

$(document).ready(function(){

    $("#main-logo").click(function(){
        window.location.replace("../");
    });
    $("#cart-btn").click(function(){
        window.location.replace("../cart/");
    });
    $("#profile-pict").click(function(){
        window.location.replace("../user/");
    });

    //add to wishlist function 
    $('#wishadd').on('click', function(e){
        e.preventDefault();
        
        var formData = new FormData(this);
        var spinnerLoading = '<span class="loader2"></span>';
        $("#wishlistBtn").html(spinnerLoading);
        
        $.ajax({
            url:"val?add",
            type:"POST",
            data:formData,
            cache:false,
            processData:false,
            contentType:false,
            
            success:function(data){
                $('.small').html(data);
            }
        });
    });
    
    //remove from wishlist function 
    $('#wishdel').on('click', function(e){
        e.preventDefault();
        
        var formData = new FormData(this);
        var spinnerLoading = '<span class="loader2"></span>';
        $("#wishlistedBtn").html(spinnerLoading);
        
        $.ajax({
            url:"val?del",
            type:"POST",
            data:formData,
            cache:false,
            processData:false,
            contentType:false,
            
            success:function(data){
                location.reload();
            }
        });
    });
    
    //chat button function 
    $('#chatBtn').on('click', function(e){
        e.preventDefault();
        
        window.location.reload();
    });
    
    //share button function
    $('#shareBtn').on('click', function(e){
        e.preventDefault();
        
        var spinnerLoading = '<span class="loader2"></span>';
        $("#shareBtn").html(spinnerLoading);
        
        if (navigator.share) {
            navigator.share({
                title: document.title,
                text: 'Yuk! Beli ' + document.title + ' di Samiha',
                url: window.location.href
            }).then(() => {
                    location.reload();
                }).catch(console.error);
            }
        });

        //add to cart function 
        $('#addToCart').on('click', function(e){
            e.preventDefault();
            
            var formData = new FormData(this);
            
            $.ajax({
                url:"val?addtocart",
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