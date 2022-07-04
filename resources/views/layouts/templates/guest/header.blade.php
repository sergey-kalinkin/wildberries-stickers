<header class="header">
    <div class="header__wrap">
        <div class="header__container container">
            <a href="{{route('guest.preview')}}" class="header__logo">
                <img src="/images/logo.png" alt="Привет! Я Робот Валя!">
            </a>
            <nav class="header__nav">
                <ul class="header__nav-list list-unstyled">
                    <li class="header__nav-item">
                        <a href="{{route('any.faq')}}" class="header__nav-link h6">FAQ</a>
                    </li>
                    <li class="header__nav-item">
                        <a href="#" class="header__nav-link h6">Обновления</a>
                    </li>
                </ul>
            </nav>
            <span class="header__separator"></span>
            <div id="authorization_widget" class="header__lk"></div>
        </div>
    </div>
</header>
