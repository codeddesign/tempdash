@extends('default')
@section('script-vars')
    <script>
        window.page.do_set_password_link = '{{ $do_set_password_link }}';
    </script>
@endsection
@section('content')
    <div class="container">
        <h1>Set New Password</h1>
        <p>Please create a password using the form below.</p>
        <form v-on:submit="handleSubmit" id="auth_new_password">
            <div v-cloak class="form-group required">
                <label>Password</label>
                <input v-bind:class="{'is-invalid': errors['password']}" v-model="password" class="form-control"
                       type="password" name="password"/>
                <ul v-if="errors['password']" class="invalid-form-feedback">
                    <li v-for="error in errors['password']">@{{ error }}</li>
                </ul>
            </div>
            <div v-cloak class="form-group required">
                <label>Confirm Password</label>
                <input v-bind:class="{'is-invalid': errors['confirm_password']}" v-model="confirm_password"
                       class="form-control" type="password" name="confirm_password"/>
                <ul v-if="errors['confirm_password']" class="invalid-form-feedback">
                    <li v-for="error in errors['confirm_password']">@{{ error }}</li>
                </ul>
            </div>
            <div class="form-group">
                <button v-bind:disabled="is_ajaxing" class="btn btn-primary" type="submit">
                    @{{ is_ajaxing ? 'Please wait...' : 'Submit' }}
                </button>
            </div>
        </form>
    </div>
@endsection