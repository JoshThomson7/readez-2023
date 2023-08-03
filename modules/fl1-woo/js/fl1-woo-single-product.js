/**
* Single Product JS
*
* Handles single product functionality
*
*/

(function ($, root, undefined) {

    $(window).on('load', function () {
        product_gallery();
    });

    function product_gallery() {
        var gallery = $('#wc_product_gallery');
        if(gallery.length > 0) {

            gallery.lightSlider({
                item: 1,
                loop: false,
                auto: false,
                pause: 4000,
                easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
                controls: false,
                gallery: true,
                slideMargin: 0,
                enableDrag: false,
                thumbItem: 6,
                thumbMargin:4,
                responsive : [
                    {
                        breakpoint:1200,
                        settings: {
                            verticalHeight: 400,
                        }
                    },
                    {
                        breakpoint: 900,
                        settings: {
                            vertical: false
                        }
                    }
                ]
            });

        }
    }


})(jQuery, this);