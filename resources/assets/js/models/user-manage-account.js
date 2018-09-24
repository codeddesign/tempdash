/**
 * Vue model used to provide data and events for the account page.
 * @see resources/views/user/account.blade.php
 */

const errorHandler = require('../util/error-handler');

if (window.page.route_name === 'user_account') {
    new Vue({
        el: '#user_account_management',
        data: {
            errors: {},
            form_data: {
                user_phone: window.page.current_user.phone,
                first_name: window.page.current_user.first_name,
                last_name: window.page.current_user.last_name,
                user_address_line_1: window.page.current_user_address ? window.page.current_user_address.line_1 : null,
                user_address_line_2: window.page.current_user_address ? window.page.current_user_address.line_2 : null,
                user_address_city: window.page.current_user_address ? window.page.current_user_address.city : null,
                user_address_state: window.page.current_user_address ? window.page.current_user_address.state : null,
                user_address_zip: window.page.current_user_address ? window.page.current_user_address.zipcode : null,
                user_address_country: window.page.current_user_address ? window.page.current_user_address.country : null,
                company: window.page.company
            },
            is_ajaxing: false
        },
        methods: {

            /**
             * Handle form submission.
             *
             * @param e
             * @returns {Promise<void>}
             */
            async handleSubmit(e) {
                e.preventDefault();

                if (!this.is_ajaxing) {
                    try {
                        this.is_ajaxing = true;
                        this.errors = {};

                        let results = await axios.put(window.page.do_update_path, this.form_data);

                        this.is_ajaxing = false;
                        window.location.reload();

                    } catch (err) {
                        this.is_ajaxing = false;

                        // Handle validation errors
                        if (err.response && err.response.status === 422) {
                            this.errors = err.response.data.errors;
                        } else if (err.response && err.response.status === 401) {
                            window.location = window.page.auth_login_path;
                        } else {
                            errorHandler(err);
                        }
                    }
                }

            }
        }
    });
}