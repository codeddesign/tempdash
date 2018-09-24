/**
 * Vue model used to provide data to the support topics and auth contact page
 * @see resources/views/support/auth_contact.blade.php
 */

const errorHandler = require('../util/error-handler');

if (window.page.route_name === 'auth_contact') {
    const contactPage = new Vue({
        el: '#auth_contact_page',
        data: {
            support_topics: window.page.support_topics.map(topic => {
                topic.is_closed = true;
                return topic;
            }),
            original_support_topics: window.page.support_topics.map(topic => {
                topic.is_closed = true;
                return topic;
            }),
            search_term: '',
            input_timer: null,
            is_ajaxing: false,
            support_filter_type: null
        },
        methods: {
            toggleTopicReveal: function(e, index) {
                e.preventDefault();
                let support_topics = this.support_topics;
                support_topics[index].is_closed = !support_topics[index].is_closed;
                this.support_topics = support_topics;
            },

            filterSupportTopics(support_topics) {
                return support_topics
                    .filter(topic => (!this.support_filter_type || topic.category === this.support_filter_type))
                    .slice(0, 10);
            },

            updateSupportTypeFilter: function(e, filter_type) {
                e.stopPropagation();
                this.support_filter_type = filter_type;
            },

            removeSupportFilter: function() {
                this.support_filter_type = null;
            },

            handleSearchInput: function(e) {
                e.preventDefault();
                if (this.input_timer) {
                    clearTimeout(this.input_timer);
                }

                let _this = this;

                this.input_timer = setTimeout(async () => {
                    if (_this.search_term && !_this.is_ajaxing) {
                        try {
                            _this.is_ajaxing = true;
                            let result = await axios.get(window.page.do_search_route, {params: {keyword: _this.search_term}});
                            _this.support_topics = result.data.support_topics;
                            _this.is_ajaxing = false;

                        } catch (err) {

                            _this.is_ajaxing = false;
                            errorHandler(err);
                        }
                    } else if (!_this.search_term) {
                        _this.support_topics = _this.original_support_topics;
                    }
                }, 350);
            }
        }
    });
}