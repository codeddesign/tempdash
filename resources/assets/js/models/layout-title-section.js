if (document.getElementById('live_toggle')) {

    new Vue({
        el: '#layout-title-section',
        data: {
            show_menu_expand: false
        },
        created: function () {
            let _this = this;
            $(document).on('side-menu-toggle', function(e, is_collapsed) {
                _this.show_menu_expand = is_collapsed;
            });
        },
        methods: {
            openMenu: function(e) {
                e.preventDefault();
                $(document).trigger('open-side-menu');
            }
        }
    });
}