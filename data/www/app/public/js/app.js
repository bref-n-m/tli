/**
 * Created by bastienplaza on 26/04/2019.
 */

/**
 * Fonction pour rendre visible ou non menu
 */
(function ($) {
    $('#header_icon').click(function (e) {
        e.preventDefault();
        $('body').toggleClass('with-sidebar');
    })
    $('#site-cache').click(function (e) {
        e.preventDefault();
        $('body').removeClass('with-sidebar');
    })
})(jQuery);