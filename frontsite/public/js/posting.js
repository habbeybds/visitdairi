// Make sure jQuery has been loaded
if (typeof jQuery === 'undefined') {
	throw new Error('template requires jQuery');
}

$(function () {
	'use strict';

	var __csrf = $('meta[name="csrf-token"]').attr('content');
	var baseUrl = $('meta[name="baseurl"]').attr('content'); 

	if($('.popular-post > .sidebar-content').length > 0) {
		var popularpostOptions = {
		_token: __csrf,
		limit: 5
		};

		$.post(baseUrl + '/ajax/get-popularpost', popularpostOptions, function(response) {
			try {
				var html = '';
				var elm = $('.popular-post > .sidebar-content > ul.sidebar-list');
				if(response.length > 0) {
					$.each(response, function(i, item) {
						html += '<li>' +
	                        '<a href="'+baseUrl+'/post/'+item.slug+'">' +
	                        '    <img src="'+ImgPath+'/'+item.image+'" alt="img"/>' +
	                        '    <div class="title-popular">' +
	                        '        <h6>'+item.title+'</h6>' +
	                        '        <small>'+item.date+'</small>' +
	                        '    </div>' +
	                        '</a>' +
	                    '</li>';
                	});
				} else {
					html = '<li><small>Tidak ada data untuk ditampilkan</small></li>'
				}
				elm.fadeOut(300).html(html).fadeIn(2000);
			} catch(err) {
				//
				console.log(err);
			}
		});

	}

	if($('.posting-list > .row').length > 0) {
		var __buildPostingList = function(data) {
			var html = '';
			$.each(data, function(i, item) {
				var Url = baseUrl+'/destination/'+item.destination_id+'-'+item.slug;
				let strippedString = item.post_content.replace(/(<([^>]+)>)/gi, "");
				html += '<div class="col-12 col-md-4 mb-5">' +
                    '    <div class="wrap-news"><a href="'+baseUrl+'/post/'+item.id+'-'+item.slug+'">' +
                    '        <div class="wrap-img">' +
                    '            <div class="bg-news"></div>' +
                    '            <img src="'+item.image+'" alt="img-news"/>' +
                    '        </div></a>' +
                    '        <div class="caption-news">' +
                    '            <a href="'+baseUrl+'/post/'+item.id+'-'+item.slug+'">' +
                    '                <h5>'+item.title+'</h5>' +
                    '            </a>' +
                    '            <p>'+strippedString+'</p>' +
                    '            <div class="date">' +
                    '                <p>' +
                    '                    <i class="far fa-calendar-alt"></i>' +
                    '                    <span>'+item.date+'</span>' +
                    '                </p>' +
                    '            </div>' +
                    '        </div>' +
                    '    </div>' +
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
			})
		};
		
		var destinationList = $('.posting-list > .row');
		var pagination = $('.pagination');
		var options = {
			_token: __csrf,
			paging: true
		};
		var load = function(Url) {
			destinationList.html('<div class="col-12 text-center"><i class="fa fa-spin fa-spinner"></i> Mengambil data ...</div>')
			$.post(Url, options, function(response) {
				destinationList.fadeOut(300).html(__buildPostingList(response.data)).fadeIn(2000);
				pagination.html(__buildPagination(response));
				init();
			});
		};

		if(typeof postCategory !== 'undefined') {
			options['category'] = postCategory;
		}
		
		load(baseUrl + '/ajax/get-allpost');
	}
});