$(document).ready(function() {
    $("#banner-slider").slick({
        dots: false,
        infinite: true,
        speed: 300,
    });
});

$("#externalNav a").click(function(a) {
    var e = $(this).parent().index();
    $("#banner-slider").slick("slickGoTo", parseInt(e));
});

$("#banner-slider").on("afterChange", function(a, e, i, l) {
    $("#externalNav .slide-nav-li").removeClass("active");
    $("#externalNav a").removeClass("active").removeClass("text-bold");
    $(".nav-" + (i + 1) + "").addClass("active");
    $(".nav-" + (i + 1) + " a").addClass("active").addClass("text-bold");
});
