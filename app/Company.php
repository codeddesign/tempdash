<?php

namespace App;

use App\Models\Address;
use App\Models\AppUser;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Company
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $account_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $phone_number
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company wherePhoneNumber($value)
 * @property string|null $website
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereWebsite($value)
 * @property int|null $account_owner_id
 * @property string|null $bank_name
 * @property string|null $bank_routing_number
 * @property string|null $bank_account_number_hash
 * @property string|null $payout_method
 * @property int|null $billing_address_id
 * @property-read \App\Models\AppUser|null $account_owner
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereAccountOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereBankAccountNumberHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereBankRoutingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereBillingAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company wherePayoutMethod($value)
 * @property string|null $profile_pic
 * @property-read \App\Models\Address|null $billing_address
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereProfilePic($value)
 */
class Company extends Model
{
    public function account_owner()
    {
        return $this->belongsTo(AppUser::class, 'account_owner_id');
    }

    public function billing_address()
    {
        return $this->belongsTo(Address::class);
    }

    public function jsonSerialize()
    {
        $json = parent::jsonSerialize();

        // Add billing address info
        $json = array_merge_recursive($json, [
            'billing_address_line_1' => empty($this->billing_address_id) ? null : $this->billing_address->line_1,
            'billing_address_line_2' => empty($this->billing_address_id) ? null : $this->billing_address->line_2,
            'billing_address_city' => empty($this->billing_address_id) ? null : $this->billing_address->city,
            'billing_address_state' => empty($this->billing_address_id) ? null : $this->billing_address->state,
            'billing_address_zip' => empty($this->billing_address_id) ? null : $this->billing_address->zipcode,
            'billing_address_country' => empty($this->billing_address_id) ? null : $this->billing_address->country
        ]);

        unset($json['bank_account_number_hash']);

        return $json;
    }
}
