$(function () {
    $('.close').on('click', function () {
        $(this).parent().hide();
    });

    $('.header-menu').on('click', function () {
        $('aside').toggleClass('compact');
    });

    $('.header-user').on('click', function () {
        $('.header-user-menu').slideToggle();
    });

    $(document).on('mouseup', function (e){
        let searchResult = $(".header-results"),
            search = $('#search');

        if (!search.is(e.target) && !searchResult.is(e.target) && searchResult.has(e.target).length === 0) {
            searchResult.hide();
        }
    });

    $('#search').onDelay({
        action: 'keyup',
        interval: 500
    }, function(){
        let query = $(this).val().trim();

        if (query.length > 2) {
            $.ajax({
                method: "GET",
                dataType: 'text',
                url: "/search/",
                data: {'q': query},
                beforeSend: function() {
                    $('.header-results').slideUp();
                },
                success: function(data, textStatus, jqXHR){//console.log(data);
                    if (textStatus === 'success' && jqXHR.status === 200 && data.length > 0) {
                        $('header .search-block').html(data);
                        $('.header-results').slideDown();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    if (textStatus === 'error' && jqXHR.status === 403 && errorThrown === 'Forbidden')
                        window.location.href = '/auth/';
                }
            });
        }
    });
});
