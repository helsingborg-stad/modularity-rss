var ModularityRss = {};

ModularityRss = ModularityRss || {};

ModularityRss.ToggleVisiblility = (function ($) {

    function ToggleVisiblility() {
        this.handleEvents();
    }

    /**
     * Handle events
     * @return {void}
     */
    ToggleVisiblility.prototype.handleEvents = function() {
        $(document).on('click', '.js-mod-rss-toggle-visibility', function (e) {
            e.preventDefault();

            $(this).toggleClass("is-hidden");

            var data = {
                'action': 'mod_rss_toggle_inlay_visibility',
                'inlay_id': $(this).attr('data-inlay-id'),
                'module_id': $(this).attr('data-module-id'),
            };

            $.post(ajaxurl, data, function(response) {
            });
        });
    };

    return new ToggleVisiblility();

}(jQuery));
