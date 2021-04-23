
$('.banner-home').carousel({
    interval: 2000
});


//Wrapper Input Animation
$('.floating-label input, .floating-label textarea').on('focus blur', function (e) {
    $(this).parents('.floating-label').toggleClass('is-focused', (e.type === 'focus' || this.value.length > 0));
}).trigger('blur');
//END Wrapper Input Animation

$('#slider-destination').owlCarousel({
    center: true,
    items:2,
    loop:true,
    margin:50,
    nav:true,
    lazyLoad:true,
    navText : ["<i class='far fa-arrow-left'></i>","<i class='far fa-arrow-right'></i>"],
    responsive:{
        300:{
            items:1,
            margin: 20,
        },
        800:{
            items:3,
            margin: 50,
        }
    }
});
$('#slider-product, #slider-wisata, #slider-souvenir, #slider-kuliner, #slider-akomodasi, #slider-transportasi').owlCarousel({
    center: true,
    items:3,
    loop:true,
    margin:30,
    nav:true,
    lazyLoad:true,
    navText : ["<i class='far fa-arrow-left'></i>","<i class='far fa-arrow-right'></i>"],
    responsive:{
        300:{
            items:2,
            margin: 5,
        },
        800:{
            items:3,
            margin: 30,
        }
    }
});


$('#single-destination').owlCarousel({
    center: true,
    items:1,
    loop:true,
    nav:true,
    lazyLoad:true,
    navText : ["<i class='far fa-arrow-left'></i>","<i class='far fa-arrow-right'></i>"],
});

$('#tour-gallery-2').owlCarousel({
    center: true,
    items:1,
    loop:true,
    nav:true,
    margin:0,
    lazyLoad:true,
    stagePadding: 0,
    URLhashListener:true,
    autoplayHoverPause:true,
    startPosition: 'URLHash',
    // autoplay:true,
    // autoplayTimeout:2000,
    // autoplayHoverPause:true,
    navText : ["<i class='far fa-arrow-left'></i>","<i class='far fa-arrow-right'></i>"],
});
$('#tour-gallery').owlCarousel({
    center: true,
    items:3,
    loop:true,
    nav:true,
    margin:0,
    lazyLoad:true,
    stagePadding: 50,
    // autoplay:true,
    // autoplayTimeout:2000,
    // autoplayHoverPause:true,
    navText : ["<i class='far fa-arrow-left'></i>","<i class='far fa-arrow-right'></i>"],
});

$(document).ready(function(){
    // $('.provinsi, .kota').select2();

    $('.control-search').click( function(){
        $('body').addClass('search-active');
        $('.input-search').focus();
    });

    $('.icon-close').click( function(){
        $('body').removeClass('search-active');
    });


});
