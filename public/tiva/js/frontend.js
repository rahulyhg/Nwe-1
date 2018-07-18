$(document).ready(function($) {

    var vp_w = $(window).width();

    if (vp_w > 640) {
        // $(".sticky").stick_in_parent({
        //     offset_top: 64,
        //     inner_scrolling: false,
        // });
        $('.sticky').sticky({topSpacing:64});
    }

    $('#modal-login .login-content .blk .switch_toggle').click(function() {
        $('#modal-login .login-content').toggleClass('on_employer');
    });

    $('.carousel').flickity({
        cellAlign: 'left',
        contain: true,
    });

    $('#main_menu-toggle').click(function() {
        $(this).toggleClass('open');
        $('body').toggleClass('on_menu');
    });

});