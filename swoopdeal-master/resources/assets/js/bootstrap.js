/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

// window.$ = window.jQuery = require('jquery');
require('bootstrap-sass');
require('corejs-typeahead');

/**
 * More installed JS libraries
 */
require('jquery.event.move');

window.matchHeight = require('./legacy/match-height.js');

require('./legacy/reduce-menu.js');
require('./legacy/responsive-slider.js');
require('./legacy/faq');

$(window).on('load', function() {
    if ($(window).width() > 767) {
        matchHeight($('.info-thumbnail .caption .description'), 3);
    }

    $(window).on('resize', function(){
        if ($(window).width() > 767) {
            $('.info-thumbnail .caption .description').height('auto');
            matchHeight($('.info-thumbnail .caption .description'), 3);
        }
    });
});