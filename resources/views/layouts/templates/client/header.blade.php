<header class="header">
    <div class="header__wrap">
        <div class="header__container container">
            <a href="{{route('client.home')}}" class="header__logo">
                <img src="/images/logo.png" alt="Привет! Я Робот Валя!">
            </a>
            <nav class="header__nav">
                <ul class="header__nav-list list-unstyled">
                    <li class="header__nav-item">
                        <a href="{{route('client.home')}}" class="header__nav-link header__nav-link--active h6">Создать наклейки</a>
                    </li>
                    <li class="header__nav-item">
                        <a href="{{route('client.spreadsheets')}}" class="header__nav-link h6">Мои наклейки</a>
                    </li>
                    <li class="header__nav-item">
                        <a href="#" class="header__nav-link h6">Мои WB товары</a>
                    </li>
                    <li class="header__nav-item">
                        <a href="#" class="header__nav-link h6">Настройки</a>
                    </li>
                    <li class="header__nav-item">
                        <a href="#" class="header__nav-link h6">FAQ</a>
                    </li>
                    <li class="header__nav-item">
                        <a href="#" class="header__nav-link h6">Обновления</a>
                    </li>
                </ul>
            </nav>
            <span class="header__separator"></span>
            <div class="header__lk">
                <div class="header__lk-dropdown dropdown">
                    <a href="#" class="header__lk-button dropdown-toggle h6" data-bs-toggle="dropdown">Личный кабинет</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Мои данные</a></li>
                        <li><a class="dropdown-item" href="#">Подписка</a></li>
                        <li><a class="dropdown-item" href="#">История платежей</a></li>
                        <li>
                            <form method="post" action="{{route('logout')}}">
                                @csrf
                                <button type="submit" class="dropdown-item">Выйти из аккаунта</button>
                            </form>
                        </li>
                    </ul>
                </div>
                <p class="header__lk-info small text-success">3 дня (тестовый период)</p>
            </div>
        </div>
    </div>
</header>
