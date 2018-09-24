<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

/**
 * @see app/Http/Middleware/RedirectAuthUsers.php
 */
Route::group(['middleware' => 'redirect_auth_users'], function() {
    Route::get('/register', ['as' => 'user_registration', 'uses' => 'UserController@register']);
    Route::get('/login', ['as' => 'auth_login', 'uses' => 'AuthController@login']);
    Route::get('/auth/recover-password', ['as' => 'auth_recovery_password', 'uses' => 'AuthController@passwordRecovery']);
    Route::get('/login/sms/validate/{id}', ['as' => 'auth_two_factor', 'uses' => 'AuthController@twoFactor']);
});

/**
 * @see app/Http/Kernel.php
 */
Route::group(['middleware' => 'auth'], function() {
    Route::get('/account', ['as' => 'user_account', 'uses' => 'UserController@account']);
    Route::get('/dashboard/contact', ['as' => 'auth_contact', 'uses' => 'SupportController@authContact']);
    Route::get('/dashboard/payments', ['as' => 'financial', 'uses' => 'FinancialController@index']);
    Route::get('/dashboard/advertiser', ['as' => 'financial', 'uses' => 'FinancialController@index']);
    Route::get('/dashboard/publishers', ['as' => 'publishers', 'uses' => 'PublishersController@index']);
    Route::get('/dashboard/transparency', ['as' => 'transparency', 'uses' => 'TransparencyController@index']);
    Route::get('/users/management', ['as' => 'user_management', 'uses' => 'UserController@management']);
    Route::get('/payees/management', ['as' => 'payee_management', 'uses' => 'PayeeController@management']);
    Route::get('/users/management/ajax/refresh-user-list', ['as' => 'ajax_refresh_user_list', 'uses' => 'UserController@refreshUserList']);
    Route::get('/financial/ajax/refresh-list', ['as' => 'ajax_refresh_payments_listing', 'uses' => 'FinancialController@refreshList']);
    Route::get('/users/management/ajax/search-users', ['as' => 'ajax_search_users', 'uses' => 'UserController@doSearchUsers']);
});

/**
 * @see app/Http/Middleware/HandleUnauthorizedPostRequests.php
 */
Route::group(['middleware' => 'handle_unauth_posts'], function() {
    Route::post('/register/email-verification/send', ['as' => 'user_do_resend_email_validation', 'uses' => 'UserController@doResendEmailValidation']);
    Route::post('/auth/set-password', ['as' => 'auth_do_set_password', 'uses' => 'AuthController@doSetPassword']);
    Route::put('/user/account/update', ['as' => 'user_do_update', 'uses' => 'UserController@doAccountUpdate']);
    Route::post('/user/create-update-user', ['as' => 'admin_create_update_user', 'uses' => 'UserController@adminCreateUpdateUser']);
    Route::delete('/user/delete', ['as' => 'admin_delete_user', 'uses' => 'UserController@doAdminDeleteUser']);
    Route::put('/user/toggle-active', ['as' => 'admin_toggle_active', 'uses' => 'UserController@doToggleActive']);
});

/**
 * @see app/Http/Middleware/AuthenticateForApiAccess.php
 */
Route::group(['middleware' => 'api'], function() {
    Route::get('/api/v1/cf/domain/{domain}', ['uses' => 'CFController@getCompanyAccountIdByDomain', 'as' => 'get_company_id_by_domain']);
});

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index'])->middleware('check_auth_verified');
Route::get('/register/email-verification', ['as' => 'user_email_verification', 'uses' => 'UserController@verifyEmail'])->middleware('check_auth_user');
Route::get('/register/admin-verify-notice', ['as' => 'user_admin_verify_notice', 'uses' => 'UserController@adminVerifyNotice'])->middleware('check_auth_user');
Route::get('/auth/set-password', ['as' => 'auth_set_password', 'uses' => 'AuthController@setNewPassword']);
Route::get('/register/verify-email/{token}', ['as' => 'user_validate_email', 'uses' => 'UserController@validateEmailVerification']);
Route::get('/auth/password-recovery/{token}', ['as' => 'auth_init_password_recov', 'uses' => 'AuthController@initPasswordRecovery']);
Route::get('/logout', ['as' => 'auth_logout', 'uses' => 'AuthController@logout']);
Route::post('/login', ['as' => 'auth_do_login', 'uses' => 'AuthController@doAuthentication']);
Route::post('/login/sms/validate/{id}', ['as' => 'do_auth_check_two_factor', 'uses' => 'AuthController@doTwoFactorCheck']);
Route::post('/login/sms/validate/send-again/{id}', ['as' => 'do_auth_resend_two_factor_code', 'uses' => 'AuthController@doResendTwoFactorCode']);
Route::post('/register', ['as' => 'user_do_register', 'uses' => 'UserController@doRegister']);
Route::post('/auth/recover-password', ['as' => 'do_password_recovery', 'uses' => 'AuthController@doPasswordRecovery']);
Route::get('/ajax/support/search-support-topics', ['as' => 'auth_contact_do_search', 'uses' => 'SupportController@ajaxDoSearchSupportTopics']);

