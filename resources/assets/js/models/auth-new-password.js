/**
 * Vue model used to provide data and events for the new password form
 * @see resources/views/auth/set_new_password.blade.php
 */

if (window.page.route_name === 'auth_set_password') {

    const form = new Vue({
        el: '#auth_new_password',
        data: {
            errors: {},
            password: null,
            confirm_password: null,
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
                    this.is_ajaxing = true;
                    try {
                        await axios
                            .post(window.page.do_set_password_link, {
                                password: this.password,
                                confirm_password: this.confirm_password
                            });

                        window.is_ajaxing = false;
                        window.location = '/';
                    } catch (err) {
                        this.is_ajaxing = false;

                        // Handle validation errors
                        if (err.response && err.response.status === 422) {
                            this.errors = err.response.data.errors;
                        } else if (err.response && err.response.status === 401) {
                            // No user is logged in, so redirect them to the login page
                            window.location = window.page.auth_login_path;
                        } else {
                            window.alert('An error occurred while submitting the form.');
                            window.console.error(err);
                        }
                    }
                }
            }
        }
    });
}