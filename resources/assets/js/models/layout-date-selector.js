if (document.getElementById('layout_date_selector')) {

    new Vue({
        el: '#layout_date_selector',
        data: {
            selected_range: ''
        },
        created: function() {
            let _this = this;
            $(document).on('update-live-mode', function(e, is_live) {
                if (is_live)
                    _this.selected_range = '';
            });
        },
        methods: {
            selectRange: function(e, range) {
                e.preventDefault();
                this.selected_range = range;
                $(document).trigger('update-layout-date-range', [this.selected_range]);
            },

            doActivateTimeframe: function(e) {
                e.preventDefault();

            }
        }
    });
}