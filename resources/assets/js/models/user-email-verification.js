/**
 * Vue model used to provide data and events for the email verification page.
 * @see resources/views/user/send_email_validation.blade.php
 */

if (window.page.route_name === 'user_email_verification')
{
    const form = new Vue({
        el: '#user_email_verification',
        data: {
            is_ajaxing: false
        },
        methods: {

            /**
             * Makes an async call to the server to resend email verification
             * email to the currently logged in user.
             *
             * @param e
             * @returns {Promise<void>}
             */
            async resendEmailVerification(e) {
                e.preventDefault();
                if (!this.is_ajaxing) {
                    try {
                        this.is_ajaxing = true;
                        await axios.post(decodeURIComponent(window.page.resend_link));
                        this.is_ajaxing = false;
                        alert('Email has successfully been sent.');
                    } catch (err) {
                        this.is_ajaxing = false;
                        if (err.response && err.response.status === 401) {
                            // No user is logged in, so redirect them to login page
                            window.location = window.page.auth_login_path;
                        } else {
                            alert('An error occurred while re-sending the verification email, please try again.');
                            window.console.error(err);
                        }
                    }
                }
            }
        }
    });
}