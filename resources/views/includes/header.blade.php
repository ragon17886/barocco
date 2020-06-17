<header class="header pt-2 mb-4">
    <div class="container-fluid">
        <div class="row wrapper align-items-center">
            <button class="btn navbar-toggler js-show-main-menu" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>

            


            <div class="account-btns">
                <button class="btn p-0 d-inline-block d-lg-none js-mobile-search" type="button" data-toggle="collapse" data-target="#collapseExample">
                    <img src="images/icons/search.svg" alt="Поиск" class="img-fluid">
                </button>
            </div>
            <div class="col-auto d-none d-lg-block">
                <p style="line-height: 1.15rem;"><small>
                    Ежедневно&nbsp;с 10:00&nbsp;до&nbsp;22:00<br>
                    +375&nbsp;44&nbsp;728&nbsp;66&nbsp;06<br>
                    Viber / Telegram / What’s App
                </small></p>
                
            </div>
            <div class="col-auto ml-auto">
                <a href="{{ route('index-page') }}">
                    <h2 class="text-uppercase m-0">{{ config('app.name') }}</h2>
                </a>
            </div>
            <div class="col-auto ml-auto account-btns d-flex">
                <a href="{{ route('login') }}" class="btn border-right-0 p-0">
                    <img src="images/icons/account.svg" alt="личный кабинет" class="img-fluid">
                </a><!--
                --><button class="btn border-right-0  p-0 d-none d-md-inline-block">
                    <img src="images/icons/favorites.svg" alt="избранное" class="img-fluid">
                </button><!--
                --><button class="btn p-0 ml-2 ml-md-0">
                    <div class="position-relative">
                        <img src="images/icons/cart.svg" alt="корзина" class="img-fluid">
                        <div class="cart-count position-absolute d-flex justify-content-center align-items-center">
                            <span class="js-cart-count">4</span>
                        </div>
                    </div>
                </button>
            </div>

            
            <div class="col-12 navbar-main-menu" id="mainMenu">
                <div class="row justify-content-center align-items-center text-uppercase font-size-15 position-relative">
                    <div class="col-12 col-lg-auto hover-dropdown">
                        Каталог
                        <div class="col-12 dropdown-menu position-absolute">
                            <a class="dropdown-item" href="#">Лоферы</a>
                            <a class="dropdown-item" href="#">Балетки</a>
                            <a class="dropdown-item" href="#">Сабо</a>
                            <a class="dropdown-item" href="#">Ботинки</a>
                            <a class="dropdown-item" href="#">Туфли</a>
                            <a class="dropdown-item" href="#">Сандали</a>
                            <a class="dropdown-item" href="#">Босоножки</a>
                            <a class="dropdown-item" href="#">Сапоги</a>
                            <a class="dropdown-item" href="#">Ботильоны</a>
                            <a class="dropdown-item" href="#">Слипоны</a>
                            <a class="dropdown-item" href="#">Кеды</a>
                            <a class="dropdown-item" href="#">Эспадрильи</a>
                        </div>
                    </div>
                    <div class="col-12 d-lg-none"><hr></div>
                    <div class="col-12 col-lg-auto">
                        Магазины
                    </div>
                    <div class="col-12 d-lg-none"><hr></div>
                    <div class="col-12 col-lg-auto hover-dropdown">
                        Онлайн покупки
                        <div class="col-12 dropdown-menu position-absolute">
                            @include('includes.static-pages-menu')
                        </div>
                    </div>
                    <div class="col-12 d-lg-none"><hr></div>
                    <div class="col-12 col-lg-auto">Рассрочка</div>
                    <div class="col-12 d-lg-none"><hr></div>
                    <div class="col-12 col-lg-auto">Отзывы</div>
                    <div class="col-12 d-lg-none"><hr></div>
                    <div class="col-12 d-lg-none">
                        <strong class="text-danger">Распродажа</strong>
                    </div>
                    <div class="col-12 d-lg-none"><hr></div>
                    <div class="col-12 col-lg-auto">Карта клиента</div>
                    <div class="col-12 d-lg-none"><hr></div>
                    <div class="col-12 col-lg-auto d-none d-lg-inline-block">
                        <div class="form-inline">
                            <input type="text" class="search-input" placeholder="Поиск">
                            <button class="btn p-0 js-search">
                                <img src="images/icons/search.svg" alt="Поиск" class="img-fluid">
                            </button>
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-12 collapse" id="collapseExample">
                <div class="row">
                    <input type="text" class="form-control border-0" placeholder="Поиск">
                </div>
            </div>





        </div>





        <div class="collapse navbar-collapse" >
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>

        </div>








    </div>




    {{-- <section class="extras alternative">
        <nav class="container extras-items">
            <span>Регистрация на <a href="https://phprussia.ru" target="_blank" rel="nofollow">PHPRussia 2020</a> уже открыта:</span>
            <a href="https://phprussia.ru/moscow/2020/abstracts" target="_blank" rel="nofollow">Доклады</a>
            <span>·</span>
            <a href="https://phprussia.ru/moscow/2020/meetups" target="_blank" rel="nofollow">Митапы</a>
            <span>·</span>
            <a href="https://phprussia.ru/moscow/2020#prices" target="_blank" rel="nofollow">Цены</a>
        </nav>
    </section>

    

    <section class="menu">
        <section class="container menu-content">
            <a href="https://laravel.su" class="logo">
                <h1>Laravel Framework Russian Community</h1>
            </a>

            <aside class="menu-aside">
                

                <nav class="menu-items">
                    
                    <a href="https://laravel.su" class="">Главная</a>
                    <a href="https://laravel.su/docs" class="active">Документация</a>
                    <a href="https://laravel.su/status" class="">Перевод</a>
                    <span>Статьи</span>
                    <span>Пакеты</span>
                </nav>
            </aside>
        </section>
    </section> --}}




</header>