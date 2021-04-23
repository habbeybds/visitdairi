@extends('layouts.main')

@section('contents')
<section class="head-title-destination" style="background-image: url({{ asset('images/bg-head-4.jpg') }})">
    <div class="container">
        <div class="wrap-head">
            <h3>Cara Pemesanan</h3>
            <div class="breadcrumb-style">
                <nav aria-label="breadcrumb" class="list-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Cara Pemesanan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="why-become-partner space pb-0 pt-0">
    <div class="background-refund bg-gray">
        <div class="container">
            <div class="title-section-5 pt-5">
                <h3>How to Refund ?</h3>
            </div>
            <div class="content-how-to-refund">
                <h6>Anda dapat mengajukan refund melalui 2 cara:</h6>
                <ol class="title-refund">
                    <li><h3>Hubungi Kami</h3></li>
                    <li><h3>Melalui Email</h3></li>
                </ol>
                <div class="wrap-content col-12 col-md-6">
                    <div class="icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="content">
                        <h3>Refund melalui Hubungi kami:</h3>
                        <ol class="desc-ol">
                            <li>Silakan isi data Anda</li>
                            <li>Isi subject dengan REFUND Silakan isi data Anda</li>
                            <li>Isi pesan dengan:
                                <ul>
                                    <li>Kode Booking</li>
                                    <li>Alasan Refund</li>
                                </ul>
                            </li>
                            <li>Klik tombol submit</li>
                        </ol>
                    </div>
                </div>

                <div class="wrap-content col-12 col-md-6">
                    <div class="icon">
                        <i class="fas fa-envelope-open"></i>
                    </div>
                    <div class="content">
                        <h3>Refund melalui email:</h3>
                        <ol class="desc-ol">
                            <li>Silahkan kirim email ke cs@visitdairi.com</li>
                            <li>Isi subject dengan REFUND</li>
                            <li>Isi pesan dengan:
                                <ul>
                                    <li>Kode Booking</li>
                                    <li>Alasan Refund</li>
                                </ul>
                            </li>
                        </ol>
                    </div>
                </div>

                <div class="wrap-content col-12 col-md-6">
                    <div class="content">
                        <ul class="more_desc">
                            <li>Admin akan mengonfirmasi data Anda melalui email. Jika data Anda valid, admin akan meminta nomor rekening Anda untuk pengembalian dana. Silahkan kirim data yang diminta oleh Admin.</li>
                            <li>Jika sudah sesuai, Admin akan menyetujui permintaan Anda dan mengirimkan notifikasi serta bukti transfer kepada Anda.</li>
                            <li>Proses Refund memakan waktu 7-14 hari kerja, dihitung dari saat Anda membarikan data kepada admin.</li>
                            <li>Admin tidak akan pernah meminta username, password, atau informasi pribadi seperti nomor kartu kredit.</li>
                        </ul>
                    </div>
                </div>


            </div>

        </div>
    </div>
</section>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        'use strict';

        const Tabs = {
            init() {
                let promise = $.Deferred();
                this.$tabs = $('ul.nav-tabs');
                this.checkHash();
                this.bindEvents();
                this.onLoad();
                return promise;
            },

            checkHash() {
                if (window.location.hash) {
                    this.toggleTab(window.location.hash);
                }
            },

            toggleTab(tab) {
                // targets
                var $paneToHide = $(tab).closest('.container').find('div.content-pane').filter('.is-active'),
                    $paneToShow = $(tab),
                    $tab = this.$tabs.find('a[href="' + tab + '"]');

                // toggle active tab
                $tab.closest('li').addClass('active').siblings('li').removeClass('active');

                // toggle active tab content
                $paneToHide.removeClass('is-active').addClass('is-animating is-exiting');
                $paneToShow.addClass('is-animating is-active');
            },

            showContent(tab) {
                //
            },

            animationEnd(e) {
                $(e.target).removeClass('is-animating is-exiting');
            },

            tabClicked(e) {
                e.preventDefault();
                this.toggleTab(e.target.hash);
            },

            bindEvents() {
                // show/hide the tab content when clicking the tab button
                this.$tabs.on('click', 'a', this.tabClicked.bind(this));

                // handle animation end
                $('div.content-pane').on('transitionend webkitTransitionEnd', this.animationEnd.bind(this));
            },

            onLoad() {
                $(window).load(function() {
                    $('div.content-pane').removeClass('is-animating is-exiting');
                });
            }
        }

        Tabs.init();
    })
</script>
@endsection