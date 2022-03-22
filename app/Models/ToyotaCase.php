<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class ToyotaCase extends Model
{
    use Uuids;

    //stop autoincrement
    public $incrementing = false;

    /**
     * type of auto increment
     */
    protected $keyType = 'string';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brand_id',
        'branch_id',
        'campaign_id',
        'user_id',
        'member_id',
        'voc_category_id',
        'classification_type_id',
        'classification_id',
        'voc_customer',
        'comments',
        'action',
        'is_negative',
        'is_disabled',
        'is_closed',
        'is_total_counted',
        'is_closed_counted',
        'closed_at'
    ];


     /**
     * user
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);// who is an agent
    }

    /**
     * campaign
     * @return BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * branch
     * @return BelongsTo
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * brand
     * @return BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * member
     * @return BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * voc_category
     * @return BelongsTo
     */
    public function voc_category()
    {
        return $this->belongsTo(VocCategory::class);
    }

    /**
     * classification_type
     * @return BelongsTo
     */
    public function classification_type()
    {
        return $this->belongsTo(ClassificationType::class);
    }

    /**
     * classification
     * @return BelongsTo
     */
    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }

    /**
     * escalation
     * @return HasOne
     */
    public function escalate()
    {
        return $this->hasOne(Escalate::class);
    }

}
