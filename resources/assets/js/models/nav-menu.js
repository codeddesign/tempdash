/**
 * Vue model that controls the dashboard side menu
 * @see resources/views/default.blade.php
 */

const element_query = 'side-menu';
const menu_data = require('../../../../config/nav-menu.json');
const side_bar_width = $('#side-menu').width();

if (document.getElementById(element_query)) {
    const menu = new Vue({
        el: '#' + element_query,
        data: {
            menu: Object.assign({}, menu_data),
            is_collapsed: false
        },
        created: function() {
            $(document).on('open-side-menu', () => { this.toggleCollapseMenu(); });
        },
        methods: {
            expandMenuItem: function (e, menu_item) {
                e.preventDefault();
                menu_item.expanded = !menu_item.expanded;
                this.$forceUpdate();
            },

            toggleCollapseMenu: function (e) {
                if(e)
                    e.preventDefault();

                this.is_collapsed = !this.is_collapsed;
                $(document).trigger('side-menu-toggle', [this.is_collapsed]);
            },

            showTooltip: function (menu_item) {
                $(window.page.tooltip_element)
                    .trigger('update-content', [menu_item.label])
                    .trigger('show');
            },

            hideTooltip: function () {
                $(window.page.tooltip_element)
                    .trigger('hide');
            },

            findSelectedMenuItem: function (items) {
                let selected_item = null;

                for (let itm of items) {
                    if (itm.url === window.location.pathname) {
                        selected_item = itm;
                        break;
                    }

                    if (Array.isArray(itm.children) && itm.children.length > 0) {
                        let recursive_result = this.findSelectedMenuItem(itm.children);
                        if (recursive_result) {
                            selected_item = itm;

                            return recursive_result;
                        }
                    }
                }

                return !selected_item ? {label: null} : selected_item;
            }
        },
        computed: {
            ordered_menu_items: function () {
                let ret_val = [];

                for (let menu_section of this.menu.sections) {
                    for (let menu_item of this.menu.items) {
                        if (menu_item.section !== menu_section.machine_name)
                            continue;

                        ret_val.push(menu_item);
                    }
                }

                return ret_val;
            },

            selected_item: function () {
                return this.findSelectedMenuItem(menu_data.items)
            }
        }
    });
}