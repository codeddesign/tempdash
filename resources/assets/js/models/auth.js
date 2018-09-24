/**
 * Vue model used to provide data and events for the auth login and two factor verification forms
 * @see resources/views/auth/login.blade.php
 * @see resources/views/auth/two_factor_form.blade.php
 */

const errorHandler = require('../util/error-handler');

if (document.getElementById('auth')) {

    const form = new Vue({
        el: '#auth',
        data: {
            errors: {},
            password: null,
            email: null,
            is_ajaxing: false,
            remember: true,
            two_factor_verification_code: '',
            success_message: ''
        },
        methods: {

            /**
             * Handle form submission for two factor verification.
             *
             * @param e
             * @returns {Promise<void>}
             */
            async handleSubmitTwoFactor(e) {
                e.preventDefault();
                if (!this.is_ajaxing) {
                    this.is_ajaxing = true;
                    this.errors = {};

                    try {
                        let result = await axios.post('', {code: this.two_factor_verification_code});

                        if (result.data.error) {
                            this.is_ajaxing = false;
                            this.errors = {two_factor_verification_code: [result.data.error]};
                        } else if (!result.data.is_valid) {
                            this.is_ajaxing = false;
                            this.errors = {two_factor_verification_code: ['The token used is invalid.']};
                        } else {
                            window.location = window.page.whence || '/';
                        }

                    } catch (err) {
                        this.is_ajaxing = false;
                        errorHandler(err);
                    }
                }
            },

            /**
             * Closes success message.
             *
             * @param e
             */
            closeSuccessMessage(e) {
                e.preventDefault();
                this.success_message = '';
            },

            /**
             * Handle re-sending two factor verification code.
             *
             * @param e
             * @returns {Promise<void>}
             */
            async sendTwoFactorCodeAgain(e) {
                e.preventDefault();

                if (!this.is_ajaxing) {
                    try {
                        this.is_ajaxing = true;
                        let result = await axios.post(window.page.resend_code_link);
                        this.is_ajaxing = false;

                        this.success_message = 'Verification code resent.';
                    } catch (err) {
                        this.is_ajaxing = false;
                        errorHandler(err);
                    }
                }
            },

            /**
             * Handle form submission for login.
             *
             * @param e
             * @returns {Promise<void>}
             */
            async handleSubmitLogin(e) {
                e.preventDefault();
                if (!this.is_ajaxing) {
                    this.is_ajaxing = true;
                    this.errors = {};

                    try {
                        let result = await axios
                            .post(window.page.login_url, {
                                email: this.email,
                                password: this.password,
                                remember: this.remember,
                                whence: window.page.whence
                            });

                        if (result.data.redirect) {
                            window.location = result.data.to;
                        } else {
                            window.location = window.page.whence || '/';
                        }

                    } catch (err) {
                        this.is_ajaxing = false;

                        // Handle validation errors
                        if (err.response && err.response.status === 422) {
                            this.errors = err.response.data.errors;
                        } else if (err.response && err.response.status === 401) {
                            this.errors = {email: [err.response.data.error]};
                        } else {
                            errorHandler(err);
                        }
                    }
                }
            }
        }
    });
}