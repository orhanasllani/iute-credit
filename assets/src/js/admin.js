(function ($) {

    $('.woocommerce-save-button').click(function () {
        $.post(iute.url, { action: 'woo_save' }, function (resp) {
            console.log(resp);
        });

    });

})(jQuery)