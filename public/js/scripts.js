$(function () {
    let body = $('body');

    $('.header-menu-button').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('menu');
    });

    $('.user-profile-show').on('click', function () {
        $('.user-profile-sidebar').toggleClass('d-block');
    });

    $('.nav-link.light-dark').on('click', function (e) {
        e.preventDefault();
        let theme = body.attr('data-bs-theme');
        if (theme === 'dark') body.attr('data-bs-theme', 'light');
        if (theme === 'light') body.attr('data-bs-theme', 'dark');
    });

    // $(document).on('mouseup', function (e){
    //     let popover = $(".popover");
    //
    //     if (!popover.is(e.target) && popover.has(e.target).length === 0) {
    //         popover.hide();
    //     }
    // });
});

GLightbox({selector: ".popup-img", title: !1})
