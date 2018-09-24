@extends('no-menu-default')
@section('script-vars')
    <script>
        window.page.name = 'user-registration';
        window.page.types_of_companies = @json($types_of_companies);
        window.page.do_register_path = '{{ $do_register_path }}';
    </script>
@endsection
@section('content')
    <div class="container">
        <div class="user-registration">
            <div class="logo-section">
                <img src="http://localhost/img/ternio_login_logo.png">
            </div>
            <form v-on:submit="handleSubmit" id="registration-form" novalidate class="registration-form">
                <div class="row">
                    <div class="col-sm-12">
                        <div v-cloak class="form-group required">
                            <label class="required">First Name</label>
                            <input v-model="first_name" v-bind:class="{'is-invalid': errors['first_name']}"
                                   class="form-control" type="text" name="first_name"/>
                            <ul v-if="errors['first_name']" class="invalid-form-feedback">
                                <li v-for="error in errors['first_name']">@{{ error }}</li>
                            </ul>
                        </div>
                        <div v-cloak class="form-group required">
                            <label>Last Name</label>
                            <input v-bind:class="{'is-invalid': errors['last_name']}" v-model="last_name"
                                   class="form-control" type="text" name="last_name"/>
                            <ul v-if="errors['last_name']" class="invalid-form-feedback">
                                <li v-for="error in errors['last_name']">@{{ error }}</li>
                            </ul>
                        </div>
                        <div v-cloak class="form-group required">
                            <label>Company</label>
                            <input v-model="company" v-bind:class="{'is-invalid': errors['company']}"
                                   class="form-control" type="text" name="company"/>
                            <ul v-if="errors['company']" class="invalid-form-feedback">
                                <li v-for="error in errors['company']">@{{ error }}</li>
                            </ul>
                        </div>
                        <div v-cloak class="form-group required" required>
                            <label>Department</label>
                            <input v-model="department" v-bind:class="{'is-invalid': errors['department']}"
                                   class="form-control" type="text" name="department"/>
                            <ul v-if="errors['department']" class="invalid-form-feedback">
                                <li v-for="error in errors['department']">@{{ error }}</li>
                            </ul>
                        </div>
                        <div v-cloak class="form-group required">
                            <label>Cell Phone Number</label>
                            <input v-bind:class="{'is-invalid': errors['phone']}" placeholder="XXX-XXX-XXXX"
                                   v-model="phone" class="form-control" type="phone" name="phone"/>
                            <ul v-if="errors['phone']" class="invalid-form-feedback">
                                <li v-for="error in errors['phone']">@{{ error }}</li>
                            </ul>
                        </div>
                        <div v-cloak class="form-group required">
                            <label>Email Address</label>
                            <input v-bind:class="{'is-invalid': errors['email']}" v-model="email" class="form-control"
                                   type="email" name="email"/>
                            <ul v-if="errors['email']" class="invalid-form-feedback">
                                <li v-for="error in errors['email']">@{{ error }}</li>
                            </ul>
                        </div>
                        <div v-cloak class="form-check required">
                            <input v-model="terms_of_service" class="form-check-input" type="checkbox"/>
                            <label class="form-check-label">I have read and agree to the <a href="">Terms of Service</a></label>
                        </div>
                        <div v-cloak class="form-group">
                            <button v-bind:disabled="!terms_of_service || is_ajaxing"
                                    class="btn btn-primary register-submit"
                                    type="submit">@{{ is_ajaxing ? 'Please wait...' : 'Register' }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection