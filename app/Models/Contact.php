<?php

namespace App\Models;

use App\Traits\Uuids;
use App\Models\Answer;
use App\Models\Report;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    use Uuids, SoftDeletes;

    // stop autoincrement
    public $incrementing = false;

    /**
     * type of auto-increment
     *
     * @string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer',
        'customer_description',
        'license_plate_number',
        'telephone_one',
        'telephone_two',
        'fax_number',
        'contact_person',
        'order_number',
        'created_by',
        'order_type',
        'plant_code',
        'dist_channel',
        'odo_reading',
        'odo_reading_unit',
        'reason_for_visit',
        'vehicle_model',
        'date_of_delivery',
        'cust_classification',
        'description',
        'vin_number',
        'header_text',
        'new_used_vehicle',
        'gate_pass_ind',
    ];

    /**
     * member
     * @return HasMany
     */
    public function member()
    {
        return $this->hasMany(Member::class)->latest();
    }

}
