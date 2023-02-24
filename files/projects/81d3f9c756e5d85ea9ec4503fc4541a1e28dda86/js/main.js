// Sticky Header
function scrollnav(){
    var scroll = $(window).scrollTop();

    if (scroll >= 100) {
        $(".top-nav").addClass("light-header");
      
    } else {
        $(".top-nav").removeClass("light-header");

    }

     if (scroll >= 170) {
          $("#cart-sidebar").addClass("sticky");
    } else {

        $("#cart-sidebar").removeClass("sticky");
    }
}
scrollnav();
$(window).scroll(function() {   
    scrollnav();
    var scroll = $(window).scrollTop();

    if (scroll >= 100) {
        $(".top-nav").addClass("light-header");
      
    } else {
        $(".top-nav").removeClass("light-header");

    }

     if (scroll >= 170) {
          $("#cart-sidebar").addClass("sticky");
    } else {

        $("#cart-sidebar").removeClass("sticky");
    }
});


