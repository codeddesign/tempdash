<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\AppUser;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /** @var AuthServiceInterface */
    protected $auth_service;

    /**
     * UserController constructor.
     * @param AuthServiceInterface $authService
     */
    public function __construct(AuthServiceInterface $authService)
    {
        $this->auth_service = $authService;
    }

    /**
     * GET user registration page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function register()
    {
        $type_of_companies = [];
        $config = config('business.types_of_companies');

        // Provide choices for 'Types Of Company' select drop down
        if (!empty($config)) {
            foreach ($config as $value => $label) {
                $type_of_companies[] = [
                    'value' => $value,
                    'label' => $label
                ];
            }
        }

        return view('user.register', [
            'types_of_companies' => $type_of_companies,
            'do_register_path' => route('user_do_register', [], false),
            'page_title' => 'Registration'
        ]);
    }

    /**
     * GET user management page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function management()
    {

        // Find other users under the same company as the logged in user
        $current_user = Auth::getCurrentUser();

        // Get permissions
        $access_control = config('access_control');
        $permissions = [];
        foreach ($access_control as $key => $value) {
            $label = str_replace('_', ' ', $value);
            $permissions[] = [
                'label' => ucfirst($label) . ' access.',
                'machine_name' => $value
            ];
        }

        $coworkers_results = $this->_getCoworkersOfUser($current_user);

        return view('user.management', [
            'company_employees' => $coworkers_results['results'],
            'total_num_of_employees' => $coworkers_results['total_count'],
            'permissions' => $permissions,
            'departments' => $current_user->getAllCoWorkerDepartments(),
            'sub_title' => 'Dashboard - Users',
            'page_title' => 'User Management'
        ]);
    }

    /**
     * GET returns the current set of users based on selection filters and current page
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshUserList(Request $request)
    {
        $current_user = Auth::getCurrentUser();
        return response()->json($this->_getCoworkersOfUser($current_user,
            $request->query('current_page'),
            $request->query('rows_per_page'), $request->query()));
    }

    /**
     * DELETE deletes a user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function doAdminDeleteUser(Request $request)
    {
        $id = $request->get('id');
        $current_user = Auth::getCurrentUser();

        $user_to_delete = AppUser::where('id', '=', $id)
            ->where('company_id', '=', $current_user->company_id)
            ->first();

        if ($user_to_delete instanceof AppUser) {
            $user_to_delete->delete();
            return response()->json(['error' => false, 'employees' => $this->_getCoworkersOfUser($current_user)['results']]);
        } else {
            throw new \Exception('User to be deleted could not be located.');
        }
    }

    /**
     * POST save user with the Add/Update user form in User Management.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function adminCreateUpdateUser(Request $request)
    {
        if (!empty($request->get('email')))
            $request->request->set('email', strtolower($request->get('email')));

        // Validate request
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|regex:/^\(?\d{3}[\-\)]?\s?\d{3}\-?\d{4}$/',
            'email' => 'required|email|unique:app_users' . ($request->get('id') ? ",email,{$request->get('id')}" : ''),
            'department' => 'required'
        ]);

        try {
            DB::beginTransaction();

            // Create new user, some attributes will be the same as the user creating the new user.
            $current_user = Auth::getCurrentUser();
            $user_data = [
                'company_id' => $current_user->company_id,
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'department' => $request->get('department'),
                'is_verified_by_admin' => true,
                'role' => $request->get('role') ?? 'Admin',
                'permissions' => $request->get('permissions'),
                'is_inactive' => $request->get('is_inactive')
            ];

            // Only if a new user is being created, modify the 'is_email_verified' and 'created_by_id'
            if (empty($request->get('id'))) {
                $user_data['is_email_verified'] = false;
                $user_data['created_by_id'] = Auth::getCurrentUser()->id;
            }

            // Determine if the user is being updated or created and act accordingly
            $app_user = $request->get('id') ? AppUser::find($request->get('id')) : new AppUser($user_data);

            if ($app_user->phone)
                $app_user->registerForAuthy();

            if ($app_user->exists) {
                $user_data['authy_id'] = $app_user->authy_id;
                $app_user->update($user_data);
                DB::commit();

            } else {

                $app_user->saveOrFail();
                DB::commit();

                // Create token and send invite email for new user
                $app_user->generateAndSaveNewToken()
                    ->sendUserAddWelcomeEmail();
            }

            return response()->json([
                'error' => false,
                'user_id' => $app_user->id,
                'employee' => $app_user
            ]);

        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * POST register user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     * @deprecated
     */
    public function doRegister(Request $request)
    {
        if (!empty($request->get('email')))
            $request->request->set('email', strtolower($request->get('email')));

        // Validate request
        $this->validate($request, [
            'first_name' => 'required|alpha_dash',
            'last_name' => 'required|alpha_dash',
            'email' => 'required|email|unique:app_users',
            'phone' => 'required|regex:/^\(?\d{3}[\-\)]?\s?\d{3}\-?\d{4}$/',
            'department' => 'required',
            'terms_of_service' => 'accepted'
        ], [
            'phone.regex' => 'Please provide a valid phone number.'
        ]);

        try {
            DB::beginTransaction();

            // Create user
            $app_user = new AppUser([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'department' => $request->get('department'),
                'company_id' => 1,
                'is_verified_by_admin' => false,
                'role' => 'Admin',
                'is_email_verified' => false,
                'is_inactive' => false,
                'recent_login_time' => Carbon::now()
            ]);

            // Register user in Authy
            $app_user->registerForAuthy();

            $app_user->saveOrFail();
            session()->put('user_id', $app_user->id);

            DB::commit();

            // Create token for email validation
            $app_user->generateAndSaveNewToken()
                ->sendVerificationEmail();

        } catch (\Throwable $ex) {
            DB::rollback();
            throw $ex;
        }

        // Save temporary user in session to generate email validation email
        $request->session()->put('user_id', $app_user->id);

        return response()->json([
            'user_id' => $app_user->id,
            'error' => false
        ]);
    }

    /**
     * POST resend email for email verification.
     *
     * @param Request $request
     * @throws \Throwable
     */
    public function doResendEmailValidation(Request $request)
    {
        $user_id = $request->session()->get('user_id');

        /** @var AppUser $app_user */
        $app_user = AppUser::findOrFail($user_id);
        $app_user
            ->generateAndSaveNewToken()
            ->sendVerificationEmail();
    }

    /**
     * GET verify email screen.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function verifyEmail()
    {

        $app_user = Auth::getCurrentUser();
        if ($app_user->is_email_verified) {
            return response()->redirectToRoute('home');
        }

        $do_resend_email_path = route('user_do_resend_email_validation', [], false);
        $page_title = 'Email Verification';

        return view('user.send_email_validation', compact('app_user', 'do_resend_email_path', 'page_title'));
    }

    /**
     * GET admin verification notice.
     */
    public function adminVerifyNotice()
    {
        $app_user = Auth::getCurrentUser();
        if ($app_user->is_verified_by_admin) {
            return response()->redirectToRoute('home');
        }

        return view('user.admin_verify_notice', ['page_title' => 'Account Verification']);
    }

    /**
     * GET user account.
     *
     * @param string $section
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function account()
    {
        $current_user = Auth::getCurrentUser();
        return view('user.account', [
            'do_update_path' => route('user_do_update', [], false),
            'current_user' => $current_user,
            'current_user_address' => $current_user->address,
            'page_title' => 'My Account',
            'sub_title' => 'Dashboard - My Account'
        ]);
    }

    /**
     * PUT update account information.
     *
     * @param Request $request
     * @throws \Exception
     * @throws \Throwable
     */
    public function doAccountUpdate(Request $request)
    {
        $this->validate($request, [
            'user_phone' => 'required|regex:/^\(?\d{3}[\-\)]?\s?\d{3}\-?\d{4}$/',
            'first_name' => 'required',
            'last_name' => 'required',
            'user_address_line_1' => 'required',
            'user_address_city' => 'required',
            'user_address_state' => 'required',
            'user_address_zip' => 'required',
            'user_address_country' => 'required',
            'company.billing_address_line_1' => 'required',
            'company.billing_address_city' => 'required',
            'company.billing_address_state' => 'required',
            'company.billing_address_zip' => 'required',
            'company.billing_address_country' => 'required'
        ], [
            'user_address_line_1.required' => 'Line 1 of the address is required.',
            'user_address_city.required' => 'The city of the address is required.',
            'user_address_state.required' => 'The state of the address is required.',
            'user_address_zip.required' => 'The zip code of the address is required.',
            'user_address_country.required' => 'The country of the address is required.',
            'user_phone.required' => 'A valid phone number is required.',
            'user_phone.regex' => 'This phone number does not seem to be valid.',
            'company.billing_address_line_1.required' => 'Line 1 of the address is required.',
            'company.billing_address_city.required' => 'The city of the address is required.',
            'company.billing_address_state.required' => 'The state of the address is required.',
            'company.billing_address_zip.required' => 'The zip code of the address is required.',
            'company.billing_address_country.required' => 'The country of the address is required.'
        ]);

        // Update user information
        $app_user = Auth::getCurrentUser();
        DB::beginTransaction();

        try {
            $app_user->phone = $request->get('user_phone');
            $app_user->first_name = $request->get('first_name');
            $app_user->last_name = $request->get('last_name');

            $address = Address::firstOrNew(['id' => $app_user->address_id]);
            $address_data = [
                'line_1' => $request->get('user_address_line_1'),
                'line_2' => $request->get('user_address_line_2'),
                'city' => $request->get('user_address_city'),
                'state' => $request->get('user_address_state'),
                'zipcode' => $request->get('user_address_zip'),
                'country' => $request->get('user_address_country')
            ];

            if ($address->exists)
                $address->update($address_data);
            else
                $address->setRawAttributes($address_data)->saveOrFail();


            $app_user->address()->associate($address);

            // Update company information
            $company_address = Address::firstOrNew(['id' => $request['company']['billing_address_id']]);
            $company_address_data = [
                'line_1' => $request['company']['billing_address_line_1'],
                'line_2' => $request['company']['billing_address_line_2'],
                'city' => $request['company']['billing_address_city'],
                'state' => $request['company']['billing_address_state'],
                'zipcode' => $request['company']['billing_address_zip'],
                'country' => $request['company']['billing_address_country']
            ];

            if ($company_address->exists)
                $company_address->update($company_address_data);
            else
                $company_address->setRawAttributes($company_address_data)->saveOrFail();

            $app_user->company->bank_name = $request['company']['bank_name'];
            $app_user->company->bank_routing_number = $request['company']['bank_routing_number'];
            $app_user->company->payout_method = $request['company']['payout_method'];
            $app_user->company->billing_address()->associate($company_address);
            $app_user->company->saveOrFail();

            // Update Authy
            if ($app_user->phone)
                $app_user->registerForAuthy();

            // Update activity
            $app_user
                ->addActivity('Updated account.')
                ->save();

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }

        session()->flash('flash-success', 'Your company information has been updated.');
    }

    /**
     * GET validate an email verification address.
     *
     * @param Request $request
     * @param string $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validateEmailVerification(Request $request, string $token)
    {
        $app_user = AppUser::where('token', '=', $token)->first();

        if (($app_user instanceof AppUser) && $app_user->validateToken($token)) {

            // Token is valid, update user
            $app_user->update([
                'token' => null,
                'token_expiry' => null,
                'is_email_verified' => true
            ]);

            // Update activity
            $app_user->addActivity('Validated email.');

            // If the user is not logged in, set user as current user
            if (!(Auth::getCurrentUser() instanceof AppUser)) {
                Auth::setCurrentUser($app_user);
                $app_user->recent_login_time = Carbon::now();
            }

            $app_user->save();

            $ret_val = response()->redirectToRoute('home');
        } else {

            $request->session()->flash('flash-error', 'The email token used cannot be found or has expired.');
            $ret_val = response()->redirectToRoute('auth_login');
        }

        return $ret_val;
    }

    /**
     * GET ajax request to search against users.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function doSearchUsers(Request $request)
    {
        $keyword = trim($request->get('keyword'));
        $current_user = Auth::getCurrentUser();

        $employees = AppUser::whereRaw("fts_doc @@ to_tsquery(quote_literal(?))",
            [$keyword])
            ->get()
            ->filter(function (AppUser $user) use ($current_user) {
                return $user->id != $current_user->id;
            });

        return response()->json(['employees' => $employees]);
    }

    /**
     * PUT ajax toggle user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function doToggleActive(Request $request)
    {
        $id = $request->get('id');
        $user = AppUser::find($id);
        $user->is_inactive = !$user->is_inactive;
        $user->save();

        return response()->json(['user' => $user]);
    }

    /**
     * Returns all the employees from the same company of the given user.
     *
     * @param AppUser $user
     * @param int $page
     * @param int $limit
     * @param array $filters
     *
     * @return array
     */
    private function _getCoworkersOfUser(AppUser $user, $page = 1, $limit = 15, $filters = [])
    {
        $query = AppUser::where('company_id', '=', $user->company_id)
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC');

        if (!empty($filters)) {
            if (!empty($filters['department_filter'])) {
                $query->where('department', 'LIKE', $filters['department_filter']);
            }

            if (!empty($filters['role_filter'])) {
                $query->where('role', '=', $filters['role_filter']);
            }

            if (!empty($filters['time_filter'])) {
                switch($filters['time_filter']) {
                    case 'Today':
                        $query->where('recent_login_time', '=', Carbon::today());
                        break;

                    case 'Yesterday':
                        $query->where('recent_login_time', '>=', Carbon::yesterday());
                        break;

                    case 'Last 30':
                        $query->where('recent_login_time', '>=', Carbon::today()->subDays(30));
                        break;

                    case 'This Year':
                        $query->where('recent_login_time', '>=', Carbon::today()->subYears(1));
                        break;

                    case 'Last 7':
                        $query->where('recent_login_time', '>=', Carbon::today()->subWeeks(1));
                        break;
                }
            }

            if (!empty($filters['user_filter']) && $filters['user_filter'] != 'all') {
                $query->where('is_inactive', '=', $filters['user_filter'] != 'active');
            }
        }

        $total_count = $query->count();

        $query->limit($limit)->offset(($page - 1) * $limit);

        $results = $query->get()
            ->filter(function ($u) use ($user) {
                return ($u->id != $user->id);
            })
            ->values();

        return ['results' => $results, 'total_count' => $total_count];
    }
}