<!DOCTYPE html>
<html lang="en-gb" dir="ltr">

<head>
    <meta charset="UTF-8" />
    <title>{{ $config['sitetitle'] }}</title>
    <meta name="description" content="{{ $config['sitemetadesc'] }}" />
    <meta name="keywords" content="{{ $config['sitemetakeyword'] }}" />
    <meta name="generator" content="DAIRI" />
    <meta name="robots" content="index, follow" />
    <meta name="author" content="{{ $config['siteauthor'] }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta property="og:url" content="{{url()->current()}}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $config['sitetitle'] }}" />
    <meta property="og:description" content="{{ $config['sitemetadesc'] }}" />
    <meta property="og:image" content="{{ asset('images/favicon.ico') }}" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="baseurl" content="{{ url('/') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

    <!-- font-->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!--  Second Font  -->
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- ICON -->
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome/fontawesome-all.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/flaticon/flaticon.css') }}">

    <!-- Paper Kit -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/paperkit/paper-kit.css?v=2.2.0.css').'?k='.rand() }}">
    
    <!--Style-->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css').'?k='.rand() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/owl/owl.carousel.css') }}">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-confirm.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/slick/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/slick/slick-theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/homepage.css').'?k='.rand() }}">
   
    @yield('styles')
    <script type="text/javascript">
        //<![CDATA[ 
        var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.trust-provider.com/" : "http://www.trustlogo.com/");
        document.write(unescape("%3Cscript src='" + tlJsHost + "trustlogo/javascript/trustlogo.js' type='text/javascript'%3E%3C/script%3E"));
        //]]>
    </script>
</head>

<body class="landing-page sidebar-collapse">
    <!-- header-->
    <header class="site-header">

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg fixed-top navbar-transparent" color-on-scroll="300">
            <div class="container clearfix">
                <div class="navbar-translate">
                    <div class="logo-box logo_head">
                        <a class="navbar-brand" href="{{ route('home') }}">
                            <img class="logo" src="{{ asset('images/logo.png') }}" alt="LOGO-DAIRI" rel="tooltip" title="Logo Visit Dairi" data-placement="bottom" target="_blank" />
                        </a>
                    </div>

                    <div class="navmobile">
                        <ul class="navbar-nav-mobile mb-0">
                            <li class="nav-item position-relative hide-desktop">
                                <a class="nav-link cursor-pointer search-icon" rel="tooltip" title="Search" href="{{route('search')}}">
                                    <i class="fa fa-search"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <button class="navbar-toggler navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-bar bar1"></span>
                                    <span class="navbar-toggler-bar bar2"></span>
                                    <span class="navbar-toggler-bar bar3"></span>
                                </button>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="collapse navbar-collapse justify-content-end" id="navigation">

                    @if($auth->logged())
                    <div class="wrap-menu-header">
                        @include('extensions.header.customer-menu')
                    </div>

                    @else
                    @include('extensions.header.navmenu')
                    @endif
                </div>
            </div>
        </nav>
        <!-- End Navbar -->

    </header>
    <section class="form-search">
        <div class="wrap-search-body">
            <i class="fas fa-times icon-close"></i>
            <form action="{{ route('search') }}" method="GET">
                <div class="search-input">
                    <input class="input-search" name="k" placeholder="Ketikkan kata pencarian" type="text">
                </div>
            </form>
        </div>
    </section>
    <!-- /header -->

    @yield('contents')

    <footer>
        <div class="footer-section bg-footer">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-2">
                        <img class="logo-footer" src="{{ asset('images/logo.png') }}" alt="logo-footer">
                        <div class="sosmed">
                            <ul>
                                @foreach($config['socials'] as $social)
                                <li><a href="{{ $social['link'] }}"> <i class="{{ $social['icon'] }}"></i></a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <h5>Hubungi Kami</h5>
                        <p><i class="fas fa-map-marker-alt"></i> {{ $config['sitecontact']['address'] }}</p>
                        <p><i class="fas fa-envelope"></i> {{ $config['sitecontact']['email'] }}</p>
                        <p><i class="fas fa-phone-volume"></i> {{ $config['sitecontact']['phone'] }}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <h5>Tentang Visit Dairi</h5>
                                @include('extensions.footer.menulink1')
                            </div>
                            <div class="col-12 col-md-4">
                                <h5>Menu</h5>
                                @include('extensions.footer.menulink2')
                            </div>
                            <div class="col-12 col-md-4">
                                <h5>Ketentuan Penggunaan</h5>
                                @include('extensions.footer.menulink3')
                            </div>
                        </div>
                    </div>
                    <div class="wrap-logo-footer">
                        <div class="col-12 col-md-12 wrap-logo">
                            <a href="https://www.banggabuatanindonesia.co.id/" target="_blank"><img class="logo-lainnya" src="{{ asset('images/bbi-logo.png') }}"></a>
                            <a href="https://www.indonesia.travel/id/id/campaign/beli-kreatif-danau-toba" target="_blank"><img class="logo-lainnya" src="{{ asset('images/bkdt-logo.png') }}"></a>
                            <a href="https://www.indonesia.travel/id/id/home" target="_blank"><img class="logo-lainnya" src="{{ asset('images/wi-logo-footer2.png') }}"></a>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 wrap-pemkab no-border">
                        <div class="pemkab-logo-style">
                            <a href="https://dairikab.go.id/" target="_blank"><img class="pemkab" src="{{ asset('images/dairikab.png') }}"></a>
                            <p class="desc-pemkab"><span>visitdairi.com</span> adalah website promosi pariwisata Kabupaten Dairi yang dimiliki & dikelola oleh Dinas Kebudayaan dan Pariwisata Kabupaten Dairi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="second-footer">
            <div class="container">
                <small>{!! $config['copyrights'] !!}</small>
            </div>
        </div>
    </footer>

    <div class="icon-how-to">
        <a href="{{ url('cara-pemesanan') }}" class="float" target="_blank">
            <i class="fas fa-question-circle"></i>
            Cara Pesan
        </a>
    </div>

    <div class="icon-wa">
        <a href="https://wa.me/{{ $config['whatsapp'] }}" class="float" target="_blank">
            <i class="fab fa-whatsapp my-float"></i>
        </a>
    </div>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/owl/owl.carousel.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
    <script src="{{ asset('js/jquery-confirm.js') }}"></script>

    <!-- Theme JS-->
    <script src="{{ asset('js/theme.js') }}"></script>

    <!--- Slick Slider-->
    <script defer type="text/javascript" src="{{ asset('js/paperkit/paper-kit.min.js?v=2.2.0') }}"></script>
    <script defer type="text/javascript" src="{{ asset('js/slick/slick.min.js?').rand() }}"></script>

    @yield('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            if (window.matchMedia('(max-width: 768px)').matches) {
                $('.wrap_btn_menu input#openSidebarMenu').change(function() {
                    if ($(this).is(":checked")) {
                        $('#sidebarMenu').css('transform', 'translateX(40%)');
                    } else {
                        $('#sidebarMenu').css('transform', 'translateX(100%)');
                    }
                });
            }
        });

        let webstatOptions = {
            _token: $('meta[name=csrf-token]').attr('content'),
            currpath: window.location.href,
            referer: '{{ Request::server('
            HTTP_REFERER ') }}'
        };
        $.post('{{ url(' / ajax / webstats ') }}', webstatOptions);

        let authOptions = {
            _token: $('meta[name=csrf-token]').attr('content')
        };
        $('.cust-logout').click(function(e) {
            e.preventDefault();
            $.post('{{ route('authLogout') }}', authOptions, function(response) {
               window.location.href = '{{ url('/') }}';
            });
        });
    </script>
</body>

</html>