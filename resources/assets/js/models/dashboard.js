/**
 * Vue model used to provide data and events for the dashboard home page
 * @see resources/views/home/dashboard.blade.php
 */

if (window.page.route_name === 'home') {

    // Create connection to socket server
    const io = window.io(`${window.location.origin}:4584`, {
        query: {
            user_data: window.page.user_data
        }
    });

    const dashboard = new Vue({
        el: '#dashboard_home',
        data: {
            current_graph: 'sine'
        },
        created: function() {
            let _this = this;

            $(window).on('side-menu-toggle', function(e, is_collapsed) {
                let graph_element = $('#home-sine-graph-container')
                    .find('.graph-container');

                let screen_width = $(window).width();

                if (screen_width <= 2000) {
                    if (is_collapsed)
                        graph_element.addClass('center-block');
                    else
                        graph_element.removeClass('center-block');
                }
            });
        },
        methods: {
            setGraph: function(e, graph_name) {
                e.preventDefault();
                this.current_graph = graph_name;
            }
        }
    });
}