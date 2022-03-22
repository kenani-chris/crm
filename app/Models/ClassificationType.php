<?php

namespace App\Models;

use App\Events\GlobalEvent;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassificationType extends Model
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
     * @return HasMany
     */
    public function classification(): HasMany
    {
        return $this->hasMany(Classification::class)
            ->orWhereIn('campaign_id', !is_null(request()->user()->campaign_id) ? [request()->user()->campaign_id] : Campaign::query()->get('id')->toArray());
    }

    /**
     * get the reports
     * @return HasMany
     */
    public function report(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * toyota_case
     * @return HasMany
     */
    public function toyota_case(): HasMany
    {
        return $this->hasMany(ToyotaCase::class)->latest();
    }
}
