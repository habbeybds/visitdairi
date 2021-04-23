// Make sure jQuery has been loaded
if (typeof jQuery === 'undefined') {
	throw new Error('template requires jQuery')
}

var func = function() {

	var __priceFormat = function(num) {
		if(typeof num === 'undefined') {
			num = 0;
		}
		num = num.toString().replace(/\./g,'');
        num = num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
		return num;
	};

	var __star = function(star) {
        if(typeof star === 'undefined' || typeof star != 'number' || Number.isNaN(star)) {
            star = 0;
        }

    	var max = 5;
    	var html = '';
    	star = Math.floor(star);
    	html += '<li><i class="fas fa-star"></i></li>'.repeat(star);
    	html += '<li><i class="far fa-star"></i></li>'.repeat(max - star);
    	return html;
    };

    var __starValue = function(star) {
    	star = parseFloat(star);
    	return star.toFixed(1);
    };

    var __copy = function(string) {
        
    };

    var __stripHtml = function(html){
        // Create a new div element
        var temporalDivElement = document.createElement("div");
        // Set the HTML content with the providen
        temporalDivElement.innerHTML = html;
        // Retrieve the text property of the element (cross-browser support)
        return temporalDivElement.textContent || temporalDivElement.innerText || "";
    }

	return {
		starValue: function(rating) {return __starValue(rating);},
		starRating: function(rating) {return __star(rating);},
		priceFormat: function(num) {return __priceFormat(num);},
        copy: function(string) {__copy(string);},
        stripHtml: function(html) {return __stripHtml(html)}
	}
}();

$('.numeric-only').keypress(function(e) {
    if (e.which != 8 && (e.which < 48 || e.which > 57)) {
        e.preventDefault();
        return false;
    }
});

$('img.replaceWithAvatar').error(function() {
    var img = $(this);
    var name = 'Visit Dairi';
    if(typeof img.attr('avatar-name') !== 'undefined' && img.attr('avatar-name').length > 0) {
        name = img.attr('avatar-name');
    }
    var rounded = false;
    if(typeof img.attr('avatar-rounded') !== 'undefined') {
        if(img.attr('avatar-rounded') == 'true') {
            rounded = true;
        }
    }
    img.attr('src', 'https://ui-avatars.com/api/?name='+name+'&rounded='+rounded);
});
