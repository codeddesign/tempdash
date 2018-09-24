<?php

namespace App\Models;

use App\Company;
use App\Mail\EmailVerification;
use App\Mail\UserAddWelcomeEmail;
use Authy\AuthyResponse;
use Auth;
use Authy\AuthyUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use Mail;

/**
 * App\Models\AppUser
 *
 * @property int $id
 * @property string $company_name
 * @property string $first_name
 * @property string $last_name
 * @property string|null $phone
 * @property string $email
 * @property string $password
 * @property bool $is_verified
 * @property bool $is_inactive
 * @property string|null $token
 * @property string|null $token_expiry
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereIsInactive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereTokenExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $address_id
 * @property-read \App\Models\Address $address
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereAddressId($value)
 * @property string $company
 * @property string $department
 * @property string|null $company_type
 * @property bool $is_verified_by_admin
 * @property bool $is_email_verified
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereCompanyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereIsEmailVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereIsVerifiedByAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser wherePositionTitle($value)
 * @property string $role
 * @property string|null $recent_login_time
 * @property mixed|null $permissions
 * @property-read string $full_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser wherePermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereRecentLoginTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereRole($value)
 * @property int|null $created_by_id
 * @property mixed|null $activity
 * @property-read \App\Models\AppUser|null $created_by
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereActivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereCreatedById($value)
 * @property string|null $authy_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereAuthyId($value)
 * @property string|null $fts_doc
 * @property int|null $company_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppUser whereFtsDoc($value)
 * @property-read null|string $recent_login_time_for_humans
 */
class AppUser extends Model
{
    protected $guarded = [];
    protected $hidden = ['password', 'token', 'token_expiry', 'address_id'];
    protected $casts = ['permissions' => 'array', 'activity' => 'array'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address() {
        return $this->belongsTo(Address::class);
    }

    /**
     * Generates a new token and saves it to the database.
     *
     * @return self
     * @throws \Throwable
     */
    public function generateAndSaveNewToken() {

        // Find a token that is not taken
        while (true) {
            $token_string = str_random(100);
            $res = DB::table(AppUser::getTable())->select(DB::raw('COUNT(*) as user_count'))
                ->where('token', '=', $token_string)->get();

            if ($res->first()->user_count == 0)
                break;
        }

        $this->token = $token_string ?? null;
        $this->token_expiry = Carbon::now()->addMinutes(config('business.token_life'));
        $this->saveOrFail();

        return $this;
    }

    /**
     * Returns user that created this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function created_by() {
        return $this->belongsTo(AppUser::class);
    }

    /**
     * Returns the associated company of this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company() {
        return $this->belongsTo(Company::class);
    }

    /**
     * Creates an authy user with the Authy API and returns the response.
     *
     * @return AuthyUser|AuthyResponse
     */
    public function registerForAuthy() {

        // TODO: Country code should not be assumed
        $authy_response = Auth::getAuthyApi()
            ->registerUser($this->email, $this->phone, '1');

        if ($authy_response->ok()) {
            $this->authy_id = $authy_response->id();
        } else {
            // TODO: Log error
        }

        return $authy_response;
    }

    /**
     * Add activity to user.
     *
     * @param string $caption
     * @return $this
     */
    public function addActivity(string $caption) {
        $activity = $this->activity ?? [];
        $activity_count_limit = config('business.activity_count_limit');

        // Filter out duplicates
        $activity = array_filter($activity, function($val) use ($caption) {
            return($val != $caption);
        });

        $activity[time()] = $caption;
        krsort($activity);

        if (count($activity) > $activity_count_limit) {
            $activity = array_slice($activity, 0, $activity_count_limit, true);
        }

        $this->activity = $activity;

        return $this;
    }

    /**
     * Validates a token with the record and the time
     *
     * @param string $token
     * @return boolean
     */
    public function validateToken(string $token) {
        return ($token == $this->token && Carbon::now() <= $this->token_expiry);
    }

    /**
     * Send verification email
     */
    public function sendVerificationEmail() {
        $mail = new EmailVerification($this);
        Mail::to($this->email)->send($mail);
    }

    /**
     * Send welcome email after admin user added a user
     */
    public function sendUserAddWelcomeEmail() {
        $mail = new UserAddWelcomeEmail($this);
        try {
            Mail::to($this->email)->send($mail);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), ['email_recipient' => $this->email]);
        }
    }

    /**
     * Get full name of user.
     *
     * @return string
     */
    public function getFullNameAttribute() {
        return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }

    /**
     * Overrides native jsonSerialize.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $json = parent::jsonSerialize();
        $json['full_name'] = $this->full_name;
        $json['created_by'] = !empty($this->created_by) ? $this->created_by->full_name : null;
        $json['last_activity'] = $this->recent_login_time_for_humans;
        $json['company'] = $this->company->jsonSerialize();

        return $json;
    }

    /**
     * Return a human friendly recent login time
     *
     * @return null|string
     */
    public function getRecentLoginTimeForHumansAttribute() {

        if (!empty($this->recent_login_time)) {
            $recent_login_time = new Carbon($this->recent_login_time);
            return $recent_login_time->diffForHumans();
        }

        return null;
    }

    /**
     * Returns a list of all represented departments from co-workers
     *
     * @return array
     */
    public function getAllCoWorkerDepartments()
    {
        return DB::table($this->getTable())
            ->select('department')
            ->where('company_id', '=', $this->company_id)
            ->get()
            ->reduce(function($acc, $value) {
                if (!in_array($value->department, $acc))
                    $acc[] = $value->department;

                return $acc;
            }, []);
    }
}
