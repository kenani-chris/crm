<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
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
        'question',
        'priority',
    ];

    /**
     * answer
     * @return HasMany
     */
    public function answer()
    {
        return $this->hasMany(Answer::class)->oldest('created_at');
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
     * report
     * @return HasMany
     */
    public function report(): HasMany
    {
        return $this->hasMany(Report::class)->latest()->limit(10);
    }
}
