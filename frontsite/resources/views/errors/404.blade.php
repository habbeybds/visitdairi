
<!DOCTYPE html>
<html lang="en-gb" dir="ltr">

<head>
    <meta charset="UTF-8"/>
    <title>VISITDAIRI - Halaman tidak ditemukan</title>
    <meta name="description" content="{{ $config['sitemetadesc']}}" />
    <meta name="keywords" content="{{ $config['sitemetakeyword']}}" />
    <meta name="generator" content="DAIRI" />
    <meta name="robots" content="index, follow" />
    <meta name="author" content="{{ $config['siteauthor'] }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('images/logo/favicon.ico') }}">

    <!-- font-->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- ICON -->
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/fontawesome/fontawesome-all.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/flaticon/flaticon.css') }}">

    <!--Style-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/owl/owl.carousel.css') }}">


</head>

<body>

    <section class="s-404-n">
        <div class="container">
            <div class="row">
                <div class="img-404">
                    <img src="{{ asset('images/not-found-404-min.jpg') }}" alt=""/>
                    <div class="content-404">
                        <h1>Halaman Tidak Ditemukan</h1>
                        <p>Terima kasih, Anda telah mengunjungi <a href="{{ url('/') }}">visitdairi.com</a></p>
                        <div class="wrap_btn mt-2 mb-5">
                            <a class="default_btn" href="{{ url('/') }}">Back To Home</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('js/owl/owl.carousel.js') }}"></script>

    <!-- Theme JS-->
    <script src="{{ asset('js/theme.js') }}"></script>


</body>
</html>