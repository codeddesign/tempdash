@extends('default')
@section('script-vars')
    <script>
        window.page.do_company_info_update_path = '{{ $do_company_info_update_path }}';
        window.page.current_user = {!! $current_user->toJson() !!};
        window.page.current_user_address = {!! empty($current_user_address) ? 'null' : $current_user_address->toJson() !!};
    </script>
@endsection
@section('content')
    <div class="container">
        <h1>My Account</h1>
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $section == 'company-information' ? 'active' : '' }}" id="company_information_tab" data-toggle="pill" href="#company_information" role="tab" aria-controls="company_information" aria-selected="true">Company Information</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $section == 'user-management' ? 'active' : '' }}" id="user_management_tab" data-toggle="pill" href="#user_management" role="tab" aria-controls="user_management" aria-selected="false">User Management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $section == 'billing' ? 'active' : '' }}" id="billing_information_tab" data-toggle="pill" href="#billing_information" role="tab" aria-controls="billing_information" aria-selected="false">Billing Information</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $section == 'integration' ? 'active' : '' }}" id="integration_tab" data-toggle="pill" href="#integration" role="tab" aria-controls="integration" aria-selected="false">Integration</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade {{ $section == 'company-information' ? 'show active' : '' }}" id="company_information" role="tabpanel" aria-labelledby="company_information">
                @include('user.account.company_info_form')
            </div>

            <div class="tab-pane fade {{ $section == 'user-management' ? 'show active' : '' }}" id="user_management" role="tabpanel" aria-labelledby="user_management">

            </div>

            <div class="tab-pane fade {{ $section == 'billing' ? 'show active' : '' }}" id="billing_information" role="tabpanel" aria-labelledby="billing_information">

            </div>
            <div class="tab-pane fade {{ $section == 'integration' ? 'show active' : '' }}" id="integration" role="tabpanel" aria-labelledby="integration">

            </div>
        </div>
    </div>
@endsection