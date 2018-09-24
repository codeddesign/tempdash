/**
 * Performs various initialization tasks to ensure layout looks appropriate.
 */

export default class Layout {

    constructor() {
        this.sidemenu_element = $('#side-menu');
    }

    initialize() {

        // Create tooltip box that follows mouse pointer
        const floating_tooltip_markup = `
            <div id="floating-tooltip"></div>
        `;

        window.page.tooltip_element = $(floating_tooltip_markup);

        $('body')
            .prepend(window.page.tooltip_element);

        // Add events to update content, hide and show floating tooltip
        window.page.tooltip_element
            .on('update-content', function (e, new_content) {
                $(e.target).html(new_content);
            })
            .on('show', function (e) {
                $(e.target).fadeIn(150);
            })
            .on('hide', function (e) {
                $(e.target).fadeOut(150);
            });

        // Ensure menu collapse re-sizes main area
        $(document)
            .on('side-menu-toggle', (e, is_menu_collapsed) => {
            if (is_menu_collapsed)
                $('#main').addClass('menu-collapsed');
            else
                $('#main').removeClass('menu-collapsed');
        })
            .on('mousemove', (e) => {
                window.page.mouse_x = e.pageX;
                window.page.mouse_y = e.pageY;

                // Update floating tooltip position
                window.page.tooltip_element.css({left: e.pageX + 15, top: e.pageY + 15});
            });
    }
}