@section('styles')
<style>
    .profile .wrap-tab-body {
        overflow: initial;
    }
    section .pagination {
        float: none;
    }

    ul.star-comment {
        padding: 0;
        list-style: none;
        display: flex;
    }
    .star-comment li{
        list-style: none;
        color: #dd660f;
        font-size: 24px;
        cursor: pointer;
    }
    .btn-sm {
        font-size: 11px;
        border-radius: 13px;
        padding: 5px 15px;
    }
    .shipment-status {
        color: #0064d2 !important;
        background-color: #0064d2;
        background-color: rgba(0, 100, 210, 0.1);
        display: inline-block;
        min-height: 24px;
        padding: 3px 12px;
        font-size: 12px !important;
        font-weight: 200;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    .shipment-status > b {
        font-weight: 600;
    }
    span.order-date {
        font-size: 12px;
        font-weight: 200;
    }
    .time-limit > span {
        font-weight: 200;
    }
    .tracking-result div.item-row > span.label {
        font-weight: 600;
    }
    .tracking-result div.item-row > span.datetime {
        font-size: 12px;
        font-style: italic;
    }
    .tracking-result div.manifest-row {
        font-size: 11px;
        font-family: Arial;
        font-weight: 100;
    }
    .tracking-result div.manifest-row .manifest-desc {
        color: #0d82be;
    }

    @media screen and (max-width: 768px) {

        .profile .wrap-tab-body .content .card-history .title-order-history p.status {
            position: initial;
        }

        section .pagination a {
            padding: 6px 12px;
            font-size: 11px;
        }

    }

</style>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/functions.js') }}"></script>
<script type="text/javascript">
    var orderList = $('.order-list')
    var filter = function(val) {
        var items = orderList.find('.order-item');
        if(val == 'all') {
            items.removeClass('d-none');
        } else {
            items.addClass('d-none');
            $.each(items, function(i, item) {
                console.log($(item));
                $(item).toggleClass('d-none', $(item).data('status') != val);
            });
        }
    }

    $('#input_filter').focus(function(){
        $('#menu_filter').fadeIn(300);
        $(this).val('')
    }).focusout(function(){
        $('#menu_filter').fadeOut(300);
    });
    $('#menu_filter input[name="status"]').change(function(){
        var checked = $('#menu_filter input[name="status"]:checked');
        var radioLabel = checked.data().label;
        var radioValue = checked.val();
        $('#input_filter').val(radioLabel);
        
        // 
        filter(radioValue);
    });

    $('.btn-comment').click(function(e) {
        var orderItem = $(this).closest('.order-item');
        var rating = parseInt(orderItem.attr('data-rating'));
        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        var comments = '';
        if(orderItem.find('[name=hdcomment]').val().length > 0) {
            comments = orderItem.find('[name=hdcomment]').val();
        }
        var string = func.starRating(4);
        
        $.confirm({
            title: 'Komentar',
            content: '<div>' +
                '<div class="form-group">'+
                '<textarea class="form-control" name="txcomment" rows="3">'+comments+'</textarea>' +
                '<input type="hidden" name="txstar" />' +
                '</div>' +
                '<div  class="form-group mb-0">Beri Penilaian: <ul class="star-comment">' +
                string +
                '</ul></div>' +
                '</div>',
            buttons: {
                Batal: {
                    //
                },
                Kirim: {
                    btnClass: 'btn-success',
                    action: function() {
                        let options= {
                            '_token': '{{csrf_token()}}',
                            'trans_id': id,
                            'comment': this.$content.find('[name=txcomment]').val(),
                            'star': this.$content.find('[name=txstar].fas').length
                        };
                        $.post('{{ url('customer/ajax/submit-review')}}', options, function(response) {
                            $.alert('Terimakasih telah memberikan ulasan terhadap produk VisitDairi!', 'Info');
                        });
                    }
                }
            },
            onContentReady: function() {
                var that = this;
                var ul = that.$content.find('.star-comment');
                ul.find('li').click(function(e) {
                    var index = $(this).index();
                    ul.find('li').each(function(i, item) {
                        if(i <= index) {
                            var star = $(item);
                            star.find('i').removeClass('far').addClass('fas');
                        } else {
                            var star = $(item);
                            star.find('i').removeClass('fas').addClass('far');
                        }
                    });
                    that.$content.find('[name=txstar]').val(ul.find('i.fas').length);
                });
            }
        });
    });

    $('.btn-tracking').click(function(e) {
        e.preventDefault();
        var formData = {
            _token: '{{csrf_token()}}',
            waybill: $(this).attr('data-awb'),
            courier: $(this).attr('data-courier')
        };
        $.confirm({
            title: 'Lacak Pengiriman',
            columnClass: 'medium',
            content: function () {
                var self = this;
                return $.ajax({
                    url: '{{route('getTracking')}}',
                    data: formData,
                    method: 'post'
                }).done(function (response) {
                    if(response.status == 'success') {
                        var summary = response.data.summary;
                        var delivered = '';
                        if(typeof response.data.delivered !== 'undefined') {
                            if(response.data.delivered) {
                                var delivery = response.data.delivery_status;
                                delivered = '<div class="item-row"><span class="label">Penerima</span>: '+delivery.pod_receiver+
                                ' <span class="datetime">[Tgl: '+delivery.pod_date+' '+delivery.pod_time+']</span></div>';
                            }
                        }

                        var trackingStatus = '';
                        if(typeof response.data.manifest !== 'undefined') {
                            $.each(response.data.manifest, function(i, item) {
                                trackingStatus += '<div class="item-row manifest-row">['+item.manifest_date+' '+item.manifest_time+'] '+
                                    item.city_name.trim().trim('-')+' (<span class="manifest-desc">'+item.manifest_description+'</span>)</div>';
                            });
                        }
                        
                        var contentHTML = '' +
                            '<div class="tracking-result">' +
                            '<div class="item-row"><span class="label">Status</span>: '+summary.status+'</div>' + delivered + 
                            '<div class="item-row"><span class="label">TRACKING</span>: </div>' + trackingStatus +
                            '</div>';
                        self.setContent(contentHTML);

                    } else {
                        self.setContent(response.message);
                    }
                }).fail(function(){
                    self.setContent('Pelacakan gagal. Silahkan refresh halaman ini dan coba kembali');
                });
            },
            buttons: {
                Tutup: {
                    action: function() {
                        //
                    }
                }
            }
        });
    });
    
</script>
@endsection