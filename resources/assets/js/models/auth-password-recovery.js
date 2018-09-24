/**
 * Vue model used to provide data and events for the password recovery form
 * @see resources/views/auth/password_recovery.blade.php
 */

if (window.page.route_name === 'auth_recovery_password') {
    const form = new Vue({
        el: '#auth_password_recovery',
        data: {
            errors: {},
            email: null,
            is_ajaxing: false,
            email_has_sent: false
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
                try {
                    this.is_ajaxing = true;
                    await axios.post(window.page.do_password_recovery, {email: this.email});
                    this.is_ajaxing = false;
                    this.email_has_sent = true;

                } catch (err) {
                    this.is_ajaxing = false;

                    // Handle validation errors
                    if (err.response && err.response.status === 422) {
                        this.errors = err.response.data.errors;
                    } else if (err.response && err.response.status === 401) {
                        this.errors = {email: ['The email address entered is not associated with any account on record.']};
                    } else {
                        alert('An error occurred while attempting password recovery.');
                        window.console.error(err);
                    }
                }
            }
        }
    });
}