<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Mesigo</title>

    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="description" content="Chat"/>
    <meta name="keywords" content="chat, web chat, communication, group chat, message, messenger"/>
    <meta name="author" content="Andrew Cooper"/>

    <link rel="shortcut icon" href="/favicon.ico" id="tabIcon">

    <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/css/fonts.css" rel="stylesheet" type="text/css"/>
    <link href="/css/icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="/css/styles.css" rel="stylesheet" type="text/css"/>
    <link href="/css/media.css" rel="stylesheet" type="text/css"/>
</head>

<body class="flex-lg-row">

<div class="auth-bg">
    <div class="authentication-page-content">
        <div class="d-flex flex-column h-100 px-4 pt-4">
            <div class="row justify-content-center my-auto">
                <div class="col-sm-8 col-lg-6 col-xl-5 col-xxl-4">
                    <?php echo $view ?? null; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="text-center text-muted p-4">
                        <p class="mb-0">
                            &copy; <script>document.write(new Date().getFullYear())</script>
                            Mesigo. Developed with <i class="mdi mdi-heart text-danger"></i> by Andrew Copper
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/js/jquery-3.7.1.min.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/glightbox.min.js"></script>
<script src="/js/scripts.js"></script>

</body>
</html>
