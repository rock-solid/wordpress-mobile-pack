(function($) {

    $(document).ready(function() {

        function showSection() {

            var menu = $('#menu-nav');
            var menuShow = $('#menu-block');

            function switchClass($myvar) {
                if ($myvar.hasClass('active')) {
                    $myvar.removeClass('active');
                } else {
                    $myvar.addClass('active');
                }
            }

            menu.on('click', function() {
                switchClass($(this));
                menuShow.slideToggle();
            });

        }

        $(window).on('load', showSection);
    });

    /*
     * A function to move the image above the entry-title if post format is video.
     *
     * Function taken from Singl theme to work for responsive video
     */
    function images() {
        var image = $('.format-image.hentry img');

        image.each(function() {
            $(this).first().prependTo($(this).closest('.format-image.hentry'));
            $(this).addClass('active');
            $(this).show();
        });

    }
    $(window).load(images);
    $(document).on('post-load', images);

})(jQuery);