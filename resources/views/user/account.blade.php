@extends('default')
@section('script-vars')
    <script>
        window.page.do_update_path = '{{ $do_update_path }}';
        window.page.current_user = {!! $current_user->toJson() !!};
        window.page.company = {!! $current_user->company->toJson() !!}
        window.page.current_user_address = {!! empty($current_user_address) ? 'null' : $current_user_address->toJson() !!};
    </script>
@endsection
@section('content')
    <div class="container" id="user_account_management">
        <div class="account-name-area clearfix">
            <div class="account-photo-section">
                <img src="{{ $current_user->company->profile_pic }}"/>
            </div>
            <div class="account-name-section">
                <h3>Account</h3>
                <h2><strong>{{ $current_user->company->name }}</strong> ({{ $current_user->company->account_id }})</h2>
            </div>
        </div>
        <div class="account-details-section">
            <div class="account-detail">
                <h3>Account</h3>
                <h4>Admin</h4>
            </div>
            <div class="account-detail">
                <h3>Phone</h3>
                <h4>{{ $current_user->company->phone_number ?? 'N/A' }}</h4>
            </div>
            <div class="account-detail">
                <h3>Website</h3>
                <h4><a href="{{ $current_user->company->website }}">{{ $current_user->company->website }}</a></h4>
            </div>
            <div class="account-detail">
                <h3>Account Owner</h3>
                <h4><div class="owner-container"></div> {{ $current_user->company->account_owner->full_name }}</h4>
            </div>
        </div>
        <div class="form-area">
            <form v-on:submit="handleSubmit($event)">
                <h2>Details</h2>
                <div class="form-section address">
                    <h3>Address</h3>
                    <table class="form-table">
                        <tr>
                            <td class="form-label-cell">First Name</td>
                            <td class="form-field-input">
                                <input placeholder="Enter First Name" v-model="form_data.first_name" type="text"/>
                                <ul v-if="errors['first_name']" class="invalid-form-feedback">
                                    <li v-for="error in errors['first_name']" v-text="error"></li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">Last Name</td>
                            <td class="form-field-input">
                                <input placeholder="Enter Last Name" v-model="form_data.last_name" type="text"/>
                                <ul v-if="errors['last_name']" class="invalid-form-feedback">
                                    <li v-for="error in errors['last_name']" v-text="error"></li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">Phone</td>
                            <td class="form-field-input">
                                <input placeholder="Enter Phone Number" v-model="form_data.user_phone" type="text"/>
                                <ul v-if="errors['user_phone']" class="invalid-form-feedback">
                                    <li v-for="error in errors['user_phone']" v-text="error"></li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">Address Line 1</td>
                            <td class="form-field-input">
                                <input placeholder="Enter Address Line 1" v-model="form_data.user_address_line_1" type="text"/>
                                <ul v-if="errors['user_address_line_1']" class="invalid-form-feedback">
                                    <li v-for="error in errors['user_address_line_1']" v-text="error"></li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">Address Line 2</td>
                            <td class="form-field-input">
                                <input placeholder="Enter Address Line 2" v-model="form_data.user_address_line_2" type="text"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">City</td>
                            <td class="form-field-input">
                                <input placeholder="Enter Address City" v-model="form_data.user_address_city" type="text"/>
                                <ul v-if="errors['user_address_city']" class="invalid-form-feedback">
                                    <li v-for="error in errors['user_address_city']" v-text="error"></li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">State</td>
                            <td class="form-field-input">
                                <input placeholder="Enter Address State" v-model="form_data.user_address_state" type="text"/>
                                <ul v-if="errors['user_address_state']" class="invalid-form-feedback">
                                    <li v-for="error in errors['user_address_state']" v-text="error"></li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">Zip Code</td>
                            <td class="form-field-input">
                                <input placeholder="Enter Address Zip Code" v-model="form_data.user_address_zip" type="text"/>
                                <ul v-if="errors['user_address_zip']" class="invalid-form-feedback">
                                    <li v-for="error in errors['user_address_zip']" v-text="error"></li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">Country</td>
                            <td class="form-field-input">
                                <input placeholder="Enter Address Country" v-model="form_data.user_address_country" type="text"/>
                                <ul v-if="errors['user_address_country']" class="invalid-form-feedback">
                                    <li v-for="error in errors['user_address_country']" v-text="error"></li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">Account Created</td>
                            <td class="form-field-info">{{ $current_user->created_at->format('F d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">Last Visit</td>
                            <td class="form-field-info">{{ $current_user->recent_login_time_for_humans }}</td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">Status</td>
                            <td class="form-field-info">This account is listed as {{ $current_user->is_inactive ? 'inactive' : 'active' }}. <a class="more-info-link" href="">More information</a></td>
                        </tr>
                    </table>
                </div>
                <div class="form-section integration">
                    <h3>Integration</h3>
                    <table class="form-table">
                        <tr>
                            <td class="form-label-cell">Customer ID</td>
                            <td class="form-field-info">5293984292</td>
                        </tr>
                    </table>
                </div>
                <div class="form-section financial">
                    <h3>Financial</h3>
                    <table class="form-table">
                        <tr>
                            <td class="form-label-cell">Banking Institution Name</td>
                            <td class="form-field-input">
                                <input placeholder="Enter Bank Name" v-model="form_data.company.bank_name" type="text"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">Bank Routing Number</td>
                            <td class="form-field-input">
                                <input placeholder="Enter Bank Routing Number" v-model="form_data.company.bank_routing_number" type="text"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">Bank Account Number</td>
                            <td class="form-field-input">
                                <input placeholder="Enter Bank Account Number" type="text"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">Payout Option</td>
                            <td class="form-field-input">
                                <select v-model="form_data.company.payout_method">
                                    <option>Select Payout Option</option>
                                    <option value="direct_deposit">Direct Deposit</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">Billing Address Line 1</td>
                            <td class="form-field-input">
                                <input placeholder="Enter Address Line 1" v-model="form_data.company.billing_address_line_1" type="text"/>
                                <ul v-if="errors['company.billing_address_line_1']" class="invalid-form-feedback">
                                    <li v-for="error in errors['company.billing_address_line_1']" v-text="error"></li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">Billing Address Line 2</td>
                            <td class="form-field-input">
                                <input placeholder="Enter Address Line 2" v-model="form_data.company.billing_address_line_2" type="text"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">City</td>
                            <td class="form-field-input">
                                <input placeholder="Enter Address City" v-model="form_data.company.billing_address_city" type="text"/>
                                <ul v-if="errors['company.billing_address_city']" class="invalid-form-feedback">
                                    <li v-for="error in errors['company.billing_address_city']" v-text="error"></li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">State</td>
                            <td class="form-field-input">
                                <input placeholder="Enter Address State" v-model="form_data.company.billing_address_state" type="text"/>
                                <ul v-if="errors['company.billing_address_state']" class="invalid-form-feedback">
                                    <li v-for="error in errors['company.billing_address_state']" v-text="error"></li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">Zip Code</td>
                            <td class="form-field-input">
                                <input placeholder="Enter Address Zip" v-model="form_data.company.billing_address_zip" type="text"/>
                                <ul v-if="errors['company.billing_address_zip']" class="invalid-form-feedback">
                                    <li v-for="error in errors['company.billing_address_zip']" v-text="error"></li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="form-label-cell">Country</td>
                            <td class="form-field-input">
                                <input placeholder="Enter Address Country" v-model="form_data.company.billing_address_country" type="text"/>
                                <ul v-if="errors['company.billing_address_country']" class="invalid-form-feedback">
                                    <li v-for="error in errors['company.billing_address_country']" v-text="error"></li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="button-section">
                    <button class="btn-primary" type="submit"><span v-text="is_ajaxing ? 'Please wait...' : 'Save Changes'"></span></button>
                </div>
            </form>
        </div>
    </div>
@endsection