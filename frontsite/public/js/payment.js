// Make sure jQuery has been loaded
if (typeof jQuery === 'undefined') {
  throw new Error('template requires jQuery')
}

'use strict';
var csrfToken = $('meta[name="csrf-token"]').attr('content');
var baseUrl = $('meta[name="baseurl"]').attr('content');

var payment = function() {

  var __payCreditCard = function(cardData, orderId) {
    MidtransNew3ds.getCardToken(cardData, {
      onSuccess: function(response) {
        var options = {
          '_token': csrfToken,
          'token_id': response.token_id,
          'order_id': orderId
        }
        __transactionCharge(options)
      },
      onFailure: function(response) {
        return {
          'status': 'error',
          'message': response
        }
      }
    });
  }

  var __transactionCharge = function(data) {
    var options = {
      '_token': csrfToken,
      'token_id': data.token_id,
      'order_id': data.order_id,
      'channel': data.channel
    }
    $.post(baseUrl + '/payment/transaction-charge', options, function(response) {
      try {
        if(response.status == 'success') {
          if(typeof response.redirect !== 'undefined') {
            window.location.href = response.redirect;
          }
        } else {

        }
      } catch(err) {
        //
        console.log(err);
      }
    });
  }

  return {
    payCreditCard: function(cardData, orderId) {return __payCreditCard(cardData, orderId)},
    transactionCharge: function(options) {return __transactionCharge(options)}
  }
}();
