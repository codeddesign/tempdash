@extends('default')
@section('script-vars')
    <script>
        window.page.do_password_recovery = '{{ route('do_password_recovery', [], false) }}';
    </script>
@endsection
@section('content')
    <div id="auth_password_recovery" class="container">
        <h1>Recover Password</h1>
        <div v-cloak>
            <p v-if="!email_has_sent">Enter the email address registered with your account, and instructions to recover your password will be sent to you.</p>
            <p v-else>You should receive an email with instructions on resetting your password.</p>
        </div>
        <form v-on:submit="handleSubmit">
            <div v-cloak class="form-group required">
                <label>Email Address</label>
                <input v-bind:class="{'is-invalid': errors['email']}" v-model="email" class="form-control" type="email" name="email"/>
                <ul v-if="errors['email']" class="invalid-form-feedback">
                    <li v-for="error in errors['email']">@{{ error }}</li>
                </ul>
            </div>
            <div v-cloak class="form-group">
                <button type="submit" v-if="!email_has_sent" class="btn btn-primary">
                    @{{ is_ajaxing ? 'Please wait...' : 'Submit' }}
                </button>
                <button type="submit" v-else class="btn btn-primary">
                    @{{ is_ajaxing ? 'Please wait...' : 'Send Again' }}
                </button>
            </div>
        </form>
    </div>
@endsection