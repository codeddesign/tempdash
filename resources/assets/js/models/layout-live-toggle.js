if (document.getElementById('live_toggle')) {

    new Vue({
        el: '#live_toggle',
        data: {
            is_live: true
        },
        created: function() {
            let _this = this;
            $(document).on('update-layout-date-range', function(e, date_range) {
                if (date_range)
                    _this.is_live = false;
            });
        },
        methods: {
            toggle: function() {
                this.is_live = !this.is_live;
                $(document).trigger('update-live-mode', [this.is_live]);
            }
        }
    });
}