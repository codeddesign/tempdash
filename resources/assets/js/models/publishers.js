import ResizeObserver from 'resize-observer-polyfill';

const moment = require('moment');
const errorHandler = require('../util/error-handler');
const convertCurrency = require('../util/convert-currency');

/**
 * Vue model that controls the publishers page
 * @see resources/views/publishers/index.blade.php
 */

const element_query = 'publishers-main';

let timerHandle = null;
const side_bar_width = $('#side-menu').width();

if (document.getElementById(element_query))
{
    new Vue({
        el: '#' + element_query,
        data: {
            pagination_bar_width: 0,
            pagination_bar_left_pos: side_bar_width,
            rows_per_page: 50,
            current_page: 1
        },
        created: function() {
            // Lifetime
            const ro = new ResizeObserver((entries, observer) => {
                for (const entry of entries) {
                    const {left, top, width, height} = entry.contentRect;

                    switch (entry.target.id) {
                        case 'graph-area': {
                            this.updateGraph(width);
                            break;
                        }

                        case 'main': {
                            this.pagination_bar_width = width;
                        }
                    }
                }
            });

            ro.observe($('#graph-area').get()[0]);
            ro.observe($('#main').get()[0]);

            // Add listener for menu toggle
            $(document).on('side-menu-toggle', (e, is_closed) => {
                this.pagination_bar_left_pos = is_closed ? 60 : side_bar_width;

                if (timerHandle)
                    clearTimeout(timerHandle);

                timerHandle = setTimeout(this.updateListingsHelper, 75);
            });

            $(window).on('resize', () => {
                this.updateListingsHelper();
            })
        },
        mounted: function() {

            // Update graph and listing helper
            this.updateGraph($('#graph-area').width());
            this.updateListingsHelper();
        },
        methods: {
            updateGraph(width) {
                const height = 20;
                const padding_top = 34;
                const graph = SVG.get('publishers-graph');
                graph
                    .attr('width', width)
                    .attr('height', height + 110)
                    .attr('viewBox', `0 0 ${width} ${height + 110}`);

                // Adjust section percentages, labels and values
                let first_percentage = 24,
                    second_percentage = 28,
                    third_percentage = 16,
                    fourth_percentage = 32;

                let first_label = 'Publisher Direct',
                    second_label = '1 Intermediary',
                    third_label = '2+',
                    fourth_label = 'Non-Transparent';

                let first_value = '$624,000',
                    second_value = '$728,000',
                    third_value = '$416,000',
                    fourth_value = '$832,000';

                let first_stat_width = width * (first_percentage / 100),
                    second_stat_width = width * (second_percentage / 100),
                    third_stat_width = width * (third_percentage / 100),
                    fourth_stat_width = width * (fourth_percentage / 100);

                $('path#graph-stat1').attr('d', `M15,${padding_top} h${first_stat_width} v${height} h${first_stat_width * -1} Q0,${height / 2 + padding_top },15,${padding_top} Z`);
                $('rect#graph-stat2').attr('y', padding_top).attr('x', first_stat_width + 15).attr('width', second_stat_width).attr('height', height);
                $('rect#graph-stat3').attr('y', padding_top).attr('x', first_stat_width + second_stat_width + 15).attr('width', third_stat_width).attr('height', height);
                $('path#graph-stat4').attr('d', `M${first_stat_width + second_stat_width + third_stat_width + 15},${padding_top} h${fourth_stat_width - 30}
                              q15,${height / 2 },0,${height} H${first_stat_width + second_stat_width + third_stat_width + 15} Z`);

                // Update percentages
                $('text#graph-stat-1-percentage').attr('x', 14).attr('y', 20).text(`${first_percentage}%`);
                $('text#graph-stat-2-percentage').attr('x', 14 + first_stat_width).attr('y', 20).text(`${second_percentage}%`);
                $('text#graph-stat-3-percentage').attr('x', 14 + first_stat_width + second_stat_width).attr('y', 20).text(`${third_percentage}%`);
                $('text#graph-stat-4-percentage').attr('x', 14 + first_stat_width + second_stat_width + third_stat_width).attr('y', 20).text(`${fourth_percentage}%`);

                // Update labels
                const label_y = 84;
                $('text#graph-stat-1-label').attr('x', 15).attr('y', label_y).text(`${first_label}`);
                $('text#graph-stat-2-label').attr('x', 15 + first_stat_width).attr('y', label_y).text(`${second_label}`);
                $('text#graph-stat-3-label').attr('x', 15 + first_stat_width + second_stat_width).attr('y', label_y).text(`${third_label}`);
                $('text#graph-stat-4-label').attr('x', 15 + first_stat_width + second_stat_width + third_stat_width).attr('y', label_y).text(`${fourth_label}`);

                // Update values
                const value_y = label_y + 24;
                $('text#graph-stat-1-value').attr('x', 15).attr('y', value_y).text(`${first_value}`);
                $('text#graph-stat-2-value').attr('x', 15 + first_stat_width).attr('y', value_y).text(`${second_value}`);
                $('text#graph-stat-3-value').attr('x', 15 + first_stat_width + second_stat_width).attr('y', value_y).text(`${third_value}`);
                $('text#graph-stat-4-value').attr('x', 15 + first_stat_width + second_stat_width + third_stat_width).attr('y', value_y).text(`${fourth_value}`);
            },

            updateListingsHelper() {
                const helper = SVG.get('listings-helper');
                const direct_listings = $('.direct-listing');
                const xpos = direct_listings.offset().left + 35;
                const ypos = direct_listings.offset().top - 46;
                const width = $('.two-listing').offset().left - xpos;
                const height = 15;
                const padding_top = 30;

                helper.attr('width', width + 26).attr('height', height + 30);
                $('#listings-helper').css('left', xpos).css('top', ypos);

                $('path#listings-helper-path1').attr('d', `M0,${height + padding_top} V${padding_top} H${width + 26} V${height + padding_top}`);
                $('path#listings-helper-path2').attr('d', `M${((width + 26) / 2) - 9},${padding_top} v15`);
                $('text#listings-helper-label').attr('y', padding_top - 8).attr('x', (width / 2) - 35);
            }
        },
        computed: {
            num_of_pages: function() {
                return 6;
            },
            pagination_links: function() {
                return [
                    {page: 1, selected: true},
                    {page: 2},
                    {page: 3},
                    {page: 4},
                    {page: 6},
                ];
            }
        }
    });
}

