<?php

namespace App\Models;

// use App\Events\GlobalEvent;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
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
        'name',
        'slug',
        'description',
        'is_enabled',
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
     * questions
     * @return HasMany
     */
    public function question()
    {
        return $this->hasMany(Question::class)->oldest('priority');
    }

    /**
     * member
     * @return HasMany
     */
    public function member(): HasMany
    {
        return $this->hasMany(Member::class)
            ->oldest('next_call_scheduled_at')
            ->whereDate('next_call_scheduled_at', '<=', date('Y-m-d H:i:s', strtotime(now())))
            ->where('attempts', '<=', env('MAX_CALL_ATTEMPTS'))
            ->whereNull('call_ended_at')
            ->where('is_complete', false)
            ->where('is_active', false)
            ->where('is_enabled', true)
            ->whereIn('campaign_id', request()->user()->role->level == 1 ? Campaign::query()->get('id')->toArray() : [request()->user()->campaign_id]);
    }

    /**
     * get users
     * @return HasMany
     */
    public function user()
    {
        return $this->hasMany(User::class)->latest();
    }

    /**
     * report
     * @return HasMany
     */
    public function report()
    {
        return $this->hasMany(Report::class)->latest();
    }

    /**
     * get the classifications
     * @return HasMany
     */
    public function classification()
    {
        return $this->hasMany(Classification::class);
    }

    /**
     * toyota_case
     * @return HasMany
     */
    public function toyota_case()
    {
        return $this->hasMany(ToyotaCase::class)->latest();
    }

    /**
     * resolution
     * @return HasMany
     */
    public function resolution()
    {
        return $this->hasMany(Resolution::class)->latest();
    }
}
