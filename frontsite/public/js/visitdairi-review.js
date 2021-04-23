// Make sure jQuery has been loaded
if (typeof jQuery === 'undefined') {
	throw new Error('template requires jQuery');
};

let __csrf = $('meta[name=csrf-token]').attr('content');
let baseUrl = $('meta[name=baseurl]').attr('content');

(function($){
    'use strict';

    var Review = function(){};

    $.fn.reviews = function(options) {
        this.stopProcess = false;
        this.errorCode = 0;
        this.errorMsg = {};
        var r = $.extend(this, new Review(options));
        
        // init function
        r._init();
        if(this.stopProcess) {
            r._show();
            return false;
        };
        r._buildHTML();
        r._callAjax();
    };

    Review = function(options) {
        $.extend(this, options);
    };

    Review.prototype = {
        _init: function() {
            if(typeof this.url === 'undefined') { 
                this.stopProcess = true; 
            };
            if(typeof this.title === 'undefined') {
                this.title = 'Reviews';
            };
            if(typeof this.method === 'undefined') {
                this.method = 'POST';
            };
            if(typeof this.limit === 'undefined') {
                this.limit = 3;
            }
        },
        _callAjax: function() {
            var that = this;
            that.ajaxResponse = {};

            var opt = {
                _token: __csrf,
                limit: this.limit
            };
            opt = $.extend(opt, this.data);
            $.ajax({
                url: this.url, 
                type: this.method,
                data: opt, 
                success: function(response) {
                    try {
                        if(response.status == 'success') {
                            that.ajaxResponse = response;
                            that._parse();
                        } else {
                            that._noparse();
                        }
                    } catch(err) {
                        console.log(err); 
                    }
                }
            })
            .error(function(err) {
                console.log(err);
            });
        },
        _reset: function() {
            this.html('');
            this.addClass('card').addClass('card-section');
            this.ajaxResponse = {};
        },
        _buildHTML: function() {
            this._reset();
            this._createHeader();
            this._createBody();
        },
        _createHeader: function() {
            let html = '' +
                '<div class="block-header">' +
                '<h3>'+this.title+' </h3>' +
                '</div>';
            this.append(html);
            this.pointStar = this.find('.block-header span.point-star');
        },
        _createBody: function() {
            let html = '' + 
                '<div class="block-body reviews">' +
                '<div class="content-reviews"></div>' +
                '</div>';
            this.append(html);
            this.contentReviews = this.find('.block-body > .content-reviews');
        },
        _setPointStar: function(value) {
            value = parseFloat(value);
            this.pointStar.html(value.toFixed(1));
        },
        _stopProcess: function(errorCode, errorMsg) {
           this.stopProcess = true;
           this.errorCode = errorCode;
           this.errorMsg = errorMsg; 
        },
        _show: function() {

        },
        _parse: function() {
            var that = this;
            var data = that.ajaxResponse.data;
            var starValue = 0;

            that._removeMore();
            $.each(data, function(i, item) {
                that._addItem(item);
                // starValue = parseInt(item.avg);
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
            if(that.ajaxResponse.more == true) {
                that._addMore(that.ajaxResponse.next_page);
                this.url = that.ajaxResponse.next_page;
                this.contentReviews.find('.review-more').click(function(e) {
                    e.preventDefault();
                    that._callAjax();
                });
            }
            // this.pointStar.html(func.starValue(starValue))
        },
        _noparse: function() {
            $('#reviews').append('Belum ada Review');
        },
        _addItem: function(item) {
            var html = '' +
                '<div class="body-reviews review-animate" style="opacity: 0">' +
                '<div class="img-traveler" style="text-align:center; margin-right: 30px;">' +
                '    <img src="'+item.image+'" class="replaceWithAvatar" avatar-name="'+item.name+'" avatar-rounded="true" style="width:70px; height:70px;" />' +
                '    <h6>'+item.name+'</h6>' +
                '</div>' +
                '<div class="t-reviews">' +
                '    <ul class="has-star" data-rating="'+item.star+'">'+func.starRating(item.star)+'</ul>' +
                '    <p>'+item.message+'</p>' +
                '    <h6 class="d-none">'+item.created_at+'</h6>' +
                '</div>' +
                '</div>';
            this.contentReviews.append($(html));
            $('.review-animate').animate({ opacity: 1 }, 1500, function() {
                $('.review-animate').removeClass('review-animate');
            });
        },
        _removeMore: function() {
            this.contentReviews.find('.review-more').remove();
        },
        _addMore: function(nextPage) {
            var html = '<a href="'+nextPage+'" class="review-more btn btn-primary btn-sm" style="font-size:10px;">Tampilkan lagi <i class="fa fa-more"></i></a>';
            this.contentReviews.append(html);
        }
    };
})(jQuery);
