import ResizeObserver from 'resize-observer-polyfill';

const moment = require('moment');
const errorHandler = require('../util/error-handler');
const convertCurrency = require('../util/convert-currency');

/**
 * Vue model that controls the financial/payments page
 * @see resources/views/financial/index.blade.php
 */

const element_query = 'financial-page';
const side_bar_width = $('#side-menu').width();

if (document.getElementById(element_query))
{
    new Vue({
        el: '#' + element_query,
        created: function (e) {
            // Make the width of the pagination bar match the main section's width
            this.pagination_bar_width = $(document).width() - side_bar_width;

            const ro = new ResizeObserver((entries, observer) => {
                for (const entry of entries) {
                    const {left, top, width, height} = entry.contentRect;
                    this.pagination_bar_width = width;
                }
            });

            ro.observe($('#main').get()[0]);

            // Add listener for menu toggle
            $(document).on('side-menu-toggle', (e, is_closed) => {
                this.pagination_bar_left_pos = is_closed ? 60 : side_bar_width;
            });
        },
        data: {
            payments: window.page.payments,
            total_num: window.page.total_num,
            pagination_bar_width: 0,
            pagination_bar_left_pos: side_bar_width,
            rows_per_page: 50,
            current_page: 1,
            is_ajaxing: false
        },
        methods: {
            convertDate(date, pattern) {
                return moment(date).format(pattern);
            },

            goToPage: function (e, num_of_pages) {
                e.preventDefault();
                this.current_page = num_of_pages;
                return this.refreshListings();
            },

            convertCurrency(value) {
                return convertCurrency(value);
            },

            goToNextPage: function () {
                if (this.current_page < this.num_of_pages) {
                    this.current_page = this.current_page + 1;
                    return this.refreshListings();
                }
            },

            updateNumOfRows: function () {
                this.current_page = 1;
                return this.refreshListings();
            },

            refreshListings: async function () {
                try {
                    this.is_ajaxing = true;
                    let results = await axios.get(window.page.refresh_list_url,
                        {
                            params: {
                                current_page: this.current_page,
                                rows_per_page: this.rows_per_page
                            }
                        });

                    this.is_ajaxing = false;

                    // Populate results
                    this.payments = results.data.results;
                    this.total_num = results.data.total_count;

                } catch (err) {
                    this.is_ajaxing = false;
                    errorHandler(err);
                }
            }
        },
        computed: {
            num_of_pages: function () {
                return Math.round(this.total_num / 50);
            },

            pagination_links: function () {
                let ret_val = [];

                if (this.num_of_pages > 1) {
                    let end_page = (this.num_of_pages >= 7) ? 7 : this.num_of_pages;
                    if (this.num_of_pages <= end_page) {
                        for (let x = 1; x <= end_page; x++)
                            ret_val.push({page: x, selected: x === parseInt(this.current_page)});
                    }
                    else {
                        let start_page = this.current_page - 2;
                        start_page = (start_page < 1) ? 1 : start_page;

                        let end_page = this.current_page + 4;
                        if (end_page > this.num_of_pages)
                            end_page = this.num_of_pages;

                        for (let x = start_page; x <= end_page; x++)
                            ret_val.push({page: x, selected: x === parseInt(this.current_page)});
                    }
                }

                return ret_val;
            }
        },
        watch: {
            rows_per_page: function(val) {
                this.current_page = 1;
                return this.refreshListings();
            }
        }
    });
}

