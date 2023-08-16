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

    <title>Simptalk</title>

    <link rel="stylesheet" href="/css/styles.css" media="all">
    <link rel="stylesheet" href="/css/media.css" media="all">
    <link rel="stylesheet" href="/css/loader.css" media="all">
    <link rel="stylesheet" href="/css/colors.css" media="all">
    <link rel="stylesheet" href="/css/fontawesome.min.css" media="all">

    <script src="/js/jquery-3.4.1.min.js"></script>
    <script src="/js/ondelay.jquery.js"></script>
    <script src="/js/jquery.cookie.js"></script>
    <script src="/js/jquery.autocomplete.min.js"></script>
    <script src="/js/jquery.inputmask.js"></script>
    <script src="/js/cities.js"></script>
    <script src="/js/functions.js"></script>
    <script src="/js/scripts.js"></script>
    <script src="/js/ResizeSensor.js"></script>

    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
</head>
<body>
<header style="display: none">
    <div class="container">header</div>
</header>
<section class="main container">
    <div class="main-body">
        <?php if (User::isAuthorized()): ?>
            <aside>
                <nav>
                    <div class="nav-item"><a href="/">Моя страница</a></div>
                    <div class="nav-item"><a href="/messages/">Сообщения</a></div>
                    <div class="nav-item"><a href="/friends/">Друзья</a></div>
                    <div class="nav-item"><a href="/auth/logout/">Выход</a></div>
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
        <a href="https://t.me/andrewcooper">@simptalk</a>
    </div>
</footer>
</body>
</html>
