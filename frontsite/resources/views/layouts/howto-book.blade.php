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

<section class="how-to-section space">
    <div class="container">
        <div class="title-section-3 mb-4 how-to-title">
            <h3>Cara Pesan Produk di Visit Dairi</h3>
            <h6>Booking produk Anda hanya dalam 5 menit!</h6>
        </div>

        <div class="how-to-book">
            <div id="wrapper">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active"><a href="#tab1" class="text-uppercase"><i class="flaticon-suitcase"></i> Pesan Paket Wisata</a></li>
                    <li><a href="#tab2" class="text-uppercase"><i class="flaticon-bag"></i> Pesan Souvenir</a></li>
                    <li><a href="#tab3" class="text-uppercase"><i class="flaticon-restaurant-cutlery-circular-symbol-of-a-spoon-and-a-fork-in-a-circle"></i> Pesan Kuliner</a></li>
                    <li><a href="#tab4" class="text-uppercase"><i class="flaticon-location"></i> Pesan Akomodasi</a></li>
                    <li><a href="#tab5" class="text-uppercase"><i class="flaticon-rent-a-car-sign"></i> Pesan Transportasi</a></li>
                </ul>

                <div class="tab-content">
                    <div id="tab1" class="content-pane is-active">

                        <div class="wrap-content">
                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/paket-wisata.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">1</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Cara Pesan Paket Wisata</h3>
                                            <p class="l-desc">Di halaman utama Visit Dairi pada bagian Paket Wisata, silahkan mencari paket yang anda ingin book lalu pilih pesan pada paket tersebut.</p>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/paket-wisata2.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">2</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Pilih Ketersedian dan Jumlah Pax</h3>
                                            <p class="l-desc">Pada halaman detil Paket Wisata, isi jadwal dan jumlah pax pada kotak booking lalu klik Booking Sekarang.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/paket-wisata3.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">3</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Isi Data Pemesan</h3>
                                            <p class="l-desc">Isi Data Pemesan. Mohon pastikan data yang diisi sudah benar dan sesuai. Jika data yang diisi sudah dipastikan sesuai, klik Booking Pesanan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/paket-wisata4.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">4</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Pilih Metode Pembayaran</h3>
                                            <p class="l-desc">Pilih metode pembayaran yang Anda inginkan dan klik Proses Pembayaran untuk melakukan pembayaran. Mohon pastikan bahwa nominal pembayaran sudah sesuai dan lakukan pembayaran sebelum batas waktu habis. Jika tidak, Anda harus memesan ulang bookingan anda.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/paket-wisata5.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">5</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Berhasil</h3>
                                            <p class="l-desc">Setelah pembayaran berhasil dilakukan, Anda akan mendapatkan E-Tiket melalui email anda.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div id="tab2" class="content-pane">
                        <div class="wrap-content">
                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/souvenir.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">1</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Cara Pesan Souvenir</h3>
                                            <p class="l-desc">Di halaman utama Visit Dairi pada bagian Souvenir, silahkan mencari souvenir yang anda ingin pesan lalu pilih pesan pada souvenir tersebut.</p>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/souvenir2.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">2</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Tentukan Jumlah Barang</h3>
                                            <p class="l-desc">Pada halaman detil Souvenir, isi jumlah barang pada kotak booking lalu klik Booking Sekarang.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/souvenir3.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">3</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Lengkapi Alamat & Pilih Kurir </h3>
                                            <p class="l-desc">Isi Data Alamat Pemesan. Mohon pastikan data yang diisi sudah benar dan sesuai. Kemudian pilih kurir yang tersedia</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/souvenir4.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">4</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Isi Data Pemesan</h3>
                                            <p class="l-desc">Isi Data Pemesan. Mohon pastikan data yang diisi sudah benar dan sesuai. Jika data yang diisi sudah dipastikan sesuai, klik Booking Pesanan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/souvenir5.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">5</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Pilih Metode Pembayaran</h3>
                                            <p class="l-desc">Pilih metode pembayaran yang Anda inginkan dan klik Proses Pembayaran untuk melakukan pembayaran. Mohon pastikan bahwa nominal pembayaran sudah sesuai dan lakukan pembayaran sebelum batas waktu habis. Jika tidak, Anda harus memesan ulang bookingan anda.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/souvenir6.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">5</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Berhasil</h3>
                                            <p class="l-desc">Setelah pembayaran berhasil dilakukan, Anda akan mendapatkan E-Tiket melalui email anda.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>

                    <div id="tab3" class="content-pane">
                        <div class="wrap-content">
                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/kuliner.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">1</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Cara Pesan Kuliner</h3>
                                            <p class="l-desc">Di halaman utama Visit Dairi pada bagian Kuliner, silahkan mencari restoran kuliner yang anda ingin book lalu pilih pesan pada kuliner tersebut.</p>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/kuliner2.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">2</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Tentukan Tanggal Booking & Jumlah Pax</h3>
                                            <p class="l-desc">Pada halaman detil Kuliner, isi tanggal booking, jam booking dan jumlah pax pada kotak booking lalu klik Booking Sekarang.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/kuliner3.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">3</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Isi Data Pemesan</h3>
                                            <p class="l-desc">Isi Data Pemesan. Mohon pastikan data yang diisi sudah benar dan sesuai. Jika data yang diisi sudah dipastikan sesuai, klik Booking Pesanan. Silahkan tunggu email pemberitahuan konfirmasi pemesanan dari penyedia kuliner yang telah dipilih.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div id="tab4" class="content-pane">
                        <div class="wrap-content">
                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/akomodasi.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">1</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Cara Pesan Akomodasi</h3>
                                            <p class="l-desc">Di halaman utama Visit Dairi pada bagian Akomodasi, silahkan mencari akomodasi yang anda ingin book lalu pilih pesan pada akomodasi tersebut.</p>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/akomodasi2.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">2</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Tentukan Tanggal, Kamar & Tamu</h3>
                                            <p class="l-desc">Pada halaman detil Akomodasi, isi tanggal booking, jumlah kamar dan jumlah tamu pada kotak booking lalu klik Cek Ketersediaan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/akomodasi3.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">3</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Pilih Kamar</h3>
                                            <p class="l-desc">Pilih kamar yang Anda inginkan, kemudian klik Pesan Sekarang.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/akomodasi4.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">4</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Isi Data Pemesan</h3>
                                            <p class="l-desc">Isi Data Pemesan. Mohon pastikan data yang diisi sudah benar dan sesuai. Jika data yang diisi sudah dipastikan sesuai, klik Booking Pesanan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/akomodasi5.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">5</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Pilih Metode Pembayaran</h3>
                                            <p class="l-desc">Pilih metode pembayaran yang Anda inginkan dan klik Proses Pembayaran untuk melakukan pembayaran. Mohon pastikan bahwa nominal pembayaran sudah sesuai dan lakukan pembayaran sebelum batas waktu habis. Jika tidak, Anda harus memesan ulang bookingan anda.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/akomodasi6.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">6</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Berhasil</h3>
                                            <p class="l-desc">Setelah pembayaran berhasil dilakukan, Anda akan mendapatkan E-Tiket melalui email anda.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>


                    </div>

                    <div id="tab5" class="content-pane">
                        <div class="wrap-content">
                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/transportasi.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">1</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Cara Pesan Transportasi</h3>
                                            <p class="l-desc">Di halaman utama Visit Dairi pada bagian Transportasi, silahkan mencari jenis transportasi yang anda ingin pesan lalu pilih pesan pada transportasi tersebut.</p>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/transportasi2.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">2</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Tentukan Tanggal Booking & Jumlah Kendaraan</h3>
                                            <p class="l-desc">Pada halaman detil Transportasi, isi jumlah kendaraan pada kotak booking lalu klik Cek Ketersediaan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/transportasi3.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">3</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Pilih Penyedia Kendaraan</h3>
                                            <p class="l-desc">Pilih penyedia kendaraan yang tersedia, kemudian klik Detail</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/transportasi4.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">4</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Detail Kendaraan</h3>
                                            <p class="l-desc">Cek kembali detail informasi yang diberikan penyedia kendaraan kemudian klik Pesan Sekarang</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/transportasi5.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">5</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Isi Data Penjemputan - Pengembalian</h3>
                                            <p class="l-desc">Isi Data Penjemputan - Pengembalian. Mohon pastikan data yang diisi sudah benar dan sesuai. Jika data yang diisi sudah dipastikan sesuai, klik Lanjutkan Pesanan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/transportasi6.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">6</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Isi Data Pemesan</h3>
                                            <p class="l-desc">Isi Data Pemesan. Mohon pastikan data yang diisi sudah benar dan sesuai. Jika data yang diisi sudah dipastikan sesuai, klik Booking Pesanan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/transportasi7.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">7</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Pilih Metode Pembayaran</h3>
                                            <p class="l-desc">Pilih metode pembayaran yang Anda inginkan dan klik Proses Pembayaran untuk melakukan pembayaran. Mohon pastikan bahwa nominal pembayaran sudah sesuai dan lakukan pembayaran sebelum batas waktu habis. Jika tidak, Anda harus memesan ulang bookingan anda.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row mb-5">
                                <div class="col-12 col-md-6">
                                    <div class="wrap-image">
                                        <img src="images/how-to/transportasi8.JPG" alt=""/>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 line">
                                    <div class="wrap-desc">
                                        <div class="wrap-numb">
                                            <span class="l-number">8</span>
                                        </div>
                                        <div class="wrap-desc-content">
                                            <h3 class="l-title">Berhasil</h3>
                                            <p class="l-desc">Setelah pembayaran berhasil dilakukan, Anda akan mendapatkan E-Tiket melalui email anda.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

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