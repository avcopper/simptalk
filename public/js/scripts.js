$(function () {
    let body = $('body');

    $('.user-profile-show').on('click', function () {
        $('.user-profile-sidebar').toggleClass('d-block');
    });

    $('.nav-link.light-dark').on('click', function (e) {
        e.preventDefault();
        let theme = body.attr('data-bs-theme');
        if (theme === 'dark') body.attr('data-bs-theme', 'light');
        if (theme === 'light') body.attr('data-bs-theme', 'dark');
    });
});

GLightbox({selector: ".popup-img", title: !1})


