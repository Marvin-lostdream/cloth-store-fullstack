<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/ico" href="{{asset('img/shopping-bag-16.ico')}}" />
    <link rel="stylesheet" href="{{asset('css/main.css')}}" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@100..900&display=swap"
        rel="stylesheet" />

    @stack('style')

    <title>@yield('title' , "CTS | الصفحة الرئيسية")</title>
</head>

<body>
    <header class="header">
        <span class="logo">C.T.S</span>
        <div class="bars">
            <span class="spans"></span>
            <span class="spans"></span>
            <span class="spans"></span>
        </div>
        <ul class="sections">
            @guest
            <li>
                <a href="{{ route('register') }}">التسجيل</a>
            </li>
            @endguest

            <li>
                <a href="{{ route("home") }}"> الصفحة الرئيسية </a>
            </li>
            <li>
                <a href="#footer">
                    <i class="fa-solid fa-phone"></i>
                    تواصل معنا
                </a>
            </li>

            @auth
            <li class="dropDown">
                <a class="dropdown-toggle" style="user-select:none; cursor: pointer; position: relative; display:flex;justify-content:center;align-items:center;">
                    {{ auth()->user()->name }}
                    <i class="fa-solid fa-caret-down" style="position:absolute; right: 0;"></i>
                </a>
                <ul class="dropDown-menu">
                    <li>
                        <a href="{{ route('cart') }}">
                            <i class="fas fa-shopping-cart"></i>
                            السلة المشتريات
                        </a>
                    </li>
                    @if(auth()->user()->isAdmin())
                    <li>
                        <a href="{{ route('dashboard') }}">
                            <i class="fa-solid fa-gauge-high"></i>
                            لوحة التحكم
                        </a>
                    </li>
                    @endif
                    <li>
                    <li>
                        <a href="#" id="logout-Btn">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            تسجيل الخروج
                        </a>
                    </li>
            </li>
        </ul>
        </li>
        @endauth


        </ul>
    </header>

    @yield("content")


    @if(!isset($hideFooter) || !$hideFooter)
    <footer id="footer" class="footer">
        <div class="social">
            <h2>C.T.S</h2>
            <div class="links">
                <p>تواصل معنا عبر مواقع التواصل</p>
                <ul>
                    <li>
                        <a class="facebook" href="#"><i class="fab fa-facebook-f fa-lg"></i></a>
                    </li>
                    <li>
                        <a class="instagram" href="#"><i class="fa-brands fa-instagram fa-lg"></i></a>
                    </li>
                    <li>
                        <a class="x" href="#"><i class="fa-brands fa-x-twitter fa-lg"></i></a>
                    </li>
                </ul>
            </div>
            <div>
                <p>
                    <i class="fa-solid fa-phone"></i>
                    او من الأرقام التالية
                </p>
                <p>+963973217286</p>
                <p>+963984217986</p>
            </div>
        </div>
        <div class="copyrights">
            <p>&copy;2026 <span>C.T.S</span> جميع الحقوق محفوظة</p>
        </div>
    </footer>


    @endif

    <!-- Logout Modal -->
    <div id="logoutModal" class="logout-modal">
        <div class="logout-modal-content">
            <div class="logout-modal-icon">
                <i class="fa-solid fa-right-from-bracket"></i>
            </div>

            <h3 class="logout-modal-title">تأكيد تسجيل الخروج</h3>
            <p class="logout-modal-text">هل أنت متأكد من رغبتك في تسجيل الخروج؟</p>

            <div class="logout-modal-buttons">
                <button id="confirmLogoutBtn" class="btn-confirm-logout">
                    <i class="fa-solid fa-check"></i> نعم، تسجيل خروج
                </button>
                <button id="cancelLogoutBtn" class="btn-cancel-logout">
                    <i class="fa-solid fa-xmark"></i> إلغاء
                </button>
            </div>
        </div>
    </div>


    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
    @stack('script')
</body>

</html>