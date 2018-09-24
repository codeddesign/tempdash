<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Address
 *
 * @property int $id
 * @property string $line_1
 * @property string|null $line_2
 * @property string $city
 * @property string $state
 * @property string $zipcode
 * @property string $country
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereZipcode($value)
 * @mixin \Eloquent
 */
class Address extends Model
{
    protected $guarded = [];
}
