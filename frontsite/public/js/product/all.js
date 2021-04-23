// Make sure jQuery has been loaded
if (typeof jQuery === 'undefined') {
	throw new Error('template requires jQuery');
}

$(function () {
	'use strict';

	var __csrf = $('meta[name="csrf-token"]').attr('content');
	var baseUrl = $('meta[name="baseurl"]').attr('content');

	var buildItem = function(item) {
		var html = '<div class="list col-12 col-md-6">' +
	        '    <a href="'+baseUrl + '/' + item.product_type +'/' +item.product_id+ '-'+item.slug+'">' +
	        '        <div class="wrap-img">' +
	        '            <div class="side-effect"></div>' +
	        '            <img src="'+item.product_thumbnail+'" alt="img"/>' +
	        '        </div>' +
	        '        <div class="body-list">' +
	        '        <div class="content">' +
	        '            <div class="badge-product">' +
	        '                <h6>'+item.availability_type+'</h6>' +
	        '                <span>'+item.subtitle+'</span>' +
	        '            </div>' +
	        '            <h3>'+item.title+'</h3>' +
	        '            <p>'+func.stripHtml(item.description)+'</p>' +
	        '            <p class="price">' +
	        '                <span class="strike d-none">IDR. 0</span>' +
	        '                <span>IDR. '+func.priceFormat(item.product_price)+'</span>' +
	        '            </p>' +
	        '        </div>' +
	        '    </div>' +
	        '    </a>' +
	        '</div>';
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

	var getProduct = function(type, _url) {
		var pagination = $('.pagination');
		var options = {
			_token: __csrf
		}
		var Url=baseUrl + '/product/ajax/' + type
		if(typeof url !== 'undefined') {
			Url = _url;
		}

		$.post(Url, options, function(response) {
			try {
				var data = response.data;
				var list = $('#paket-wisata > .wrap-list > .row');
				list.html('');
				$.each(data, function(i, item) {
					list.append(buildItem(item));
				});

				pagination.html(__buildPagination(response));
				pagination.find('a').click(function(e) {
					e.preventDefault();
					var href = $(this).attr('href');
					getProduct(type, href);
				});
			} catch(err) {
				console.log(err)
			}
		});
	};


	getProduct('tour');

});