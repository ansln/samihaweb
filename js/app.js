var swiper = new Swiper(".mySwiper", {
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    pagination: {
        el: ".swiper-pagination",
        type: 'bullets',
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
});
$(document).ready(function() {
    $("#main-logo").click(function(){
        window.location.replace(".");
    });
    $("#cart-btn").click(function(){
        window.location.replace("cart/");
    });
    $("#profile-pict").click(function(){
        window.location.replace("user/");
    });
});