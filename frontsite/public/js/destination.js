// Make sure jQuery has been loaded
if (typeof jQuery === 'undefined') {
	throw new Error('template requires jQuery');
}

$(function () {
	'use strict';

	var __csrf = $('meta[name="csrf-token"]').attr('content');
	var baseUrl = $('meta[name="baseurl"]').attr('content'); 

	if($('.destination-list > .row').length > 0) {

		var __buildDestinationList = function(data) {
			var html = '';
			$.each(data, function(i, item) {
				var Url = baseUrl+'/destination/'+item.destination_id+'-'+item.slug;
				html += '<div class="col-12 col-md-4 mb-5">' +
                    '<div class="wrap-news">' +
                    '    <div class="wrap-img">' +
                    '        <div class="bg-news"></div>' +
                    '        <img src="'+item.image+'" alt="VisitDairi"/>' +
                    '    </div>' +
                    '    <div class="caption-news">' +
                    '        <a href="'+Url+'">' +
                    '            <h5>'+item.name+'</h5>' +
                    '        </a>' +
                    '        <p>'+item.highlight+'</p>' +
                    '        <div class="wrap-star">' +
                    '            <ul class="review-title has-star" data-rating="'+parseFloat(item.review_avg)+'"></ul>' +
                    '            <span class="point-star">'+func.starValue(item.review_avg)+'</span>' +
                    '        </div>' +
                    '    </div>' +
                    '</div>' +
                '</div>';
			});
			return html;
		};

		var __buildPagination = function(data, current) {
			var html = '';
			$.each(data.links, function(i, item) {
				if(item.label == '&laquo; Previous') {
					html += '<a href="'+(item.url != null ? item.url : '#')+'"><i class="fas fa-long-arrow-alt-left"></i></a>';
				} else if(item.label == 'Next &raquo;') {
					html += '<a href="'+(item.url != null ? item.url : '#')+'"><i class="fas fa-long-arrow-alt-right"></i></a>';
				} else {
					html += '<a href="'+item.url+'" class="'+(item.active ? 'active' : '')+'">'+item.label+'</a>';
				}
			});
			
			return html;
		};

		var init = function() {
			$('.pagination > a').click(function(e) {
				e.preventDefault();
				var href = $(this).attr('href');
				load(href);
			});
			$('[data-rating].has-star').each(function(i, item) {
		        var star = $(item).data('rating');
		        $(item).html(func.starRating(star));
		    });
		};
		
		var destinationList = $('.destination-list > .row');
		var pagination = $('.pagination');
		var options = {
			_token: __csrf,
			paging: true
		};
		var load = function(Url) {
			destinationList.html('<div class="col-12 text-center"><i class="fa fa-spin fa-spinner"></i> Mengambil data ...</div>')
			$.post(Url, options, function(response) {
				destinationList.fadeOut(300).html(__buildDestinationList(response.data)).fadeIn(2000);
				pagination.html(__buildPagination(response));
				init();
			});
		};
		load(baseUrl + '/ajax/get-destination');
	}
});