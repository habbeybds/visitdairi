// Make sure jQuery has been loaded
if (typeof jQuery === 'undefined') {
	throw new Error('template requires jQuery');
}

$(function () {
	'use strict';

	var __csrf = $('meta[name="csrf-token"]').attr('content');
	var baseUrl = $('meta[name="baseurl"]').attr('content'); 

	var __star = function(star) {
    	var max = 5;
    	var html = '';
    	html += '<li><i class="fas fa-star"></i></li>'.repeat(star);
    	html += '<li><i class="far fa-star"></i></li>'.repeat(max - star);
    	return html;
    };

    var __numberFormat = function(num) {
    	num = num.toString().replace(/\./g,'');
        num = num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
		return num;
    }

    $('[data-rating].has-star').each(function(i, item) {
    	var star = $(item).data('rating');
    	$(item).html(__star(star));
    });

	// destinations
	var destOptions = {
		_token: __csrf,
		limit: 5
	};
	$.post(baseUrl + '/ajax/get-destination', destOptions, function(response) {
		//console.log(response);
	});

	// recomendation
	var recomendationOptions = {
		_token: __csrf,
		limit: 5
	};
	$.post(baseUrl + '/ajax/get-recomendation', recomendationOptions, function(response) {
		//console.log(response);
	});

	// posting
	var latestpostOptions = {
		_token: __csrf,
		limit: 5
	};
	$.post(baseUrl + '/ajax/get-latestpost', latestpostOptions, function(response) {
		try {
			var elm = $('.news-content > .row');
			var html = '';
			if(response.length > 0) {
				$.each(response, function(i, item) {
					html += '<div class="col-12 col-md-4">' +
                        '<div class="wrap-news"><a href="'+baseUrl+'/post/'+item.slug+'">' +
                        '    <div class="wrap-img">' +
                        '        <div class="bg-news"></div>' +
                        '        <img src="'+ImgPath+item.image+'" alt="img-news"/>' +
                        '    </div></a>' +
                        '    <div class="caption-news">' +
                        '        <a href="'+baseUrl+'/post/'+item.slug+'"><h5>'+item.title+'</h5></a>' +
                        '        <p>'+item.description+'</p>' +
                        '        <div class="date">' +
                        '            <p>' +
                        '                <i class="far fa-calendar-alt"></i>' +
                        '                <span>'+item.date+'</span>' +
                        '            </p>' +
                        '        </div>' +
                        '    </div>' +
                        '</div>' +
                    '</div>';
				});
				elm.fadeOut(100).html(html).fadeIn(2000);
			} else {
				html += '<div class="col-12 col-md-12"><h6>Tidak ada artikel &amp; berita untuk ditayangkan.</h6></div>';
				elm.fadeOut(100).html(html).fadeIn(1000);
			}
		} catch(err) {
			var elm = $('.news-content > .row');
			var html = '<div class="col-12 col-md-12"><h6>Tidak ada artikel &amp; berita untuk ditayangkan.</h6></div>';
			elm.fadeOut(100).html(html).fadeIn(1000);
		}
	});

	$('.tab-product > .nav-tabs a').on('click', function(e) {
		var title = $('.wrap-title-desc-product > .title-product');
		var desc = $('.wrap-title-desc-product > .desc-product');
		var input = $(this).find('input').val();
		title.html($(this).html());
		desc.html(input);
	});
	$('.tab-product > .nav-tabs a')[0].click();
});

$(document).ready(function() {
    var $videoSrc;
    $('.btn-yt').click(function() {
        $videoSrc = $(this).data("src");
    });

    $('#yt-modal').on('shown.bs.modal', function (e) {
    	$("#video").attr('src',$videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0" );
    })

    $('#yt-modal').on('hide.bs.modal', function (e) {
        // a poor man's stop video
        $("#video").attr('src',$videoSrc);
    })

});