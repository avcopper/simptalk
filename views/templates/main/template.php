<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <title>Chat</title>

    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="description" content="Chat"/>
    <meta name="keywords" content="chat, web chat, communication, group chat, message, messenger"/>
    <meta name="author" content="Andrew Cooper"/>

    <link rel="shortcut icon" href="/favicon.ico" id="tabIcon">

    <link href="/css/fonts.css" rel="stylesheet" type="text/css"/>
    <link href="/css/glightbox.min.css" rel="stylesheet">
    <link href="/css/swiper-bundle.min.css" rel="stylesheet">
    <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/css/icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="/css/styles.css" rel="stylesheet" type="text/css"/>
    <link href="/css/media.css" rel="stylesheet" type="text/css"/>

    <script src="/js/jquery-3.7.1.min.js"></script>
</head>

<body data-bs-theme="dark">
<?= $this->render('menu/top') ?>
<?= $this->render('menu/side') ?>

<div class="layout-wrapper d-lg-flex main">

    <?= $view ?? null; ?>
</div>

<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/simplebar.min.js"></script>
<script src="/js/glightbox.min.js"></script>
<script src="/js/swiper-bundle.min.js"></script>
<script src="/js/fgEmojiPicker.js"></script>
<script src="/js/functions.js"></script>
<script src="/js/scripts.js"></script>

</body>
</html>
