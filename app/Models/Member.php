<?php

namespace App\Models;

// use App\Events\GlobalEvent;
use App\Traits\Uuids;
use App\Models\AwarenessCreation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    use  Uuids, SoftDeletes;

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
        'disposition_id',
        'campaign_id',
        'branch_id',
        'brand_id',
        'user_id',
        'contact_id',
        'attempts',
        'batch',
        'is_reachable',
        'is_active',
        'is_preview',
        'is_complete',
        'is_enabled',
        'last_called_at',
        'call_ended_at',
        'next_call_scheduled_at',
    ];

    /**
     * trigger this to create a slug before
     * any save happens
     */
    protected $dispatchesEvents = [
        // 'saving' => GlobalEvent::class,
        // 'creating' => GlobalEvent::class,
        // 'updating' => GlobalEvent::class,
    ];

    /**
     * campaign
     * @return BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * disposition
     * @return BelongsTo
     */
    public function disposition()
    {
        return $this->belongsTo(Disposition::class);
    }

    /**
     * user
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * contact
     * @return BelongsTo
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * brand
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * branch
     * @return BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * schedule
     * @return HasMany
     */
    public function schedule()
    {
        return $this->hasMany(Schedule::class)
            ->orWhereDate('scheduled_at', '>=', today())
            ->oldest();
    }

    /**
     * report
     * @return HasMany
     */
    public function report()
    {
        return $this->hasMany(Report::class)->oldest('question_priority');
    }

    /**
     * toyota_case
     * @return HasOne
     */
    public function toyota_case(): HasOne
    {
        return $this->hasOne(ToyotaCase::class)->latest();
    }

    public function awareness_creation(): HasOne
    {
        return $this->hasOne(AwarenessCreation::class);
    }
    
}
