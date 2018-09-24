<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Payment
 *
 * @property int $id
 * @property string $payout_method
 * @property string $pay_schedule
 * @property string $pay_period_start
 * @property string $pay_period_end
 * @property int $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $company_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment wherePayPeriodEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment wherePayPeriodStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment wherePaySchedule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment wherePayoutMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Payment extends Model
{
    protected $guarded = [];
}