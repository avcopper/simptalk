<?php
use Models\User;
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Mesigo</title>

    <link rel="stylesheet" href="/css/styles.css" media="all">
    <link rel="stylesheet" href="/css/media.css" media="all">
    <link rel="stylesheet" href="/css/loader.css" media="all">

    <script src="/js/jquery-3.4.1.min.js"></script>
    <script src="/js/ondelay.jquery.js"></script>
    <script src="/js/jquery.cookie.js"></script>
    <script src="/js/functions.js"></script>
    <script src="/js/scripts.js"></script>
    <script src="/js/ResizeSensor.js"></script>

    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
</head>
<body>
<header>
    <div class="container">
        <?php if (User::isAuthorized()): ?>
            <div class="header-menu"></div>
        <?php endif; ?>
        header
    </div>
</header>
<section class="main container">
    <div class="main-body">
        <?php if (User::isAuthorized()): ?>
            <aside class="compact">
                <nav>
                    <div class="nav-item"><a href="/" class="nav-home" title="Моя страница">Моя страница</a></div>
                    <div class="nav-item"><a href="/messages/" class="nav-messages" title="Сообщения">Сообщения</a></div>
                    <div class="nav-item"><a href="/friends/" class="nav-friends" title="Друзья">Друзья</a></div>
                    <div class="nav-item"><a href="/auth/logout/" class="nav-exit" title="Выход">Выход</a></div>
                </nav>
            </aside>
        <?php endif; ?>

        <main>
            <?php echo $view ?? null; ?>
        </main>
    </div>
</section>
<footer>
    <div class="container">
        <a href="https://t.me/andrewcooper">@mesigo</a>
    </div>
</footer>
</body>
</html>
