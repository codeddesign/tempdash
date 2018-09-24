/**
 * Vue model used to provide data and events for the auth login form
 * @see resources/views/auth/login.blade.php
 */

if (window.page.route_name === 'auth_two_factor') {

    const form = new Vue({
        el: '#auth_two_factor',
        data: {
            errors: {},
            verification_code: '',
            is_ajaxing: false
        },
        methods: {


        }
    });
}