<?php

namespace App\Models;

use App\Events\GlobalEvent;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classification extends Model
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
        'campaign_id',
        'classification_type_id',
        'name',
        'slug',
    ];

    /**
     * trigger this to create a slug before
     * any save happens
     */
    protected $dispatchesEvents = [
        'saving' => GlobalEvent::class,
        'creating' => GlobalEvent::class,
        'updating' => GlobalEvent::class,
    ];

    /**
     * get the classifications
     * @return BelongsTo
     */
    public function classification_type()
    {
        return $this->belongsTo(ClassificationType::class);
    }

    /**
     * get the reports
     * @return HasMany
     */
    public function report()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * get the campaign
     * @return BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * toyota_case
     * @return HasMany
     */
    public function toyota_case()
    {
        return $this->hasMany(ToyotaCase::class)->latest();
    }
}
