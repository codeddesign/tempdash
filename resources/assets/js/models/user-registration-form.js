/**
 * Vue model used to provide data and events for user registration page.
 * @see resources/views/user/register.blade.php
 */

if (window.page.route_name === 'user_registration')
{
    const form = new Vue({
        el: '#registration-form',
        data: {
            errors: {},
            company: null,
            first_name: null,
            last_name: null,
            email: null,
            phone: null,
            department: null,
            terms_of_service: false,
            is_ajaxing: false,
            types_of_companies: window.page.types_of_companies
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

                if (!this.is_ajaxing && this.terms_of_service) {
                    try {
                        this.is_ajaxing = true;
                        this.errors = {};

                        let results = await axios.post(window.page.do_register_path, {
                            company: this.company,
                            first_name: this.first_name,
                            last_name: this.last_name,
                            department: this.department,
                            email: this.email,
                            phone: this.phone,
                            terms_of_service: this.terms_of_service,
                            is_ajaxing: this.is_ajaxing
                        });

                        this.is_ajaxing = false;
                        window.location.href = '/';

                    } catch (err) {
                        this.is_ajaxing = false;

                        // Handle validation errors
                        if (err.response && err.response.status === 422) {
                            this.errors = err.response.data.errors;
                        }  else {
                            alert('An error occurred while registering.');
                            window.console.error(err);
                        }
                    }
                }
            }
        }
    });
}