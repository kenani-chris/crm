<?php

namespace App\Models;

// use App\Events\GlobalEvent;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
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
        'code',
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
     * report
     * @return HasMany
     */
    public function report()
    {
        return $this->hasMany(Report::class)->latest();
    }

    /**
     * branch_brand
     * @return HasOne
     */
    public function branch_brand()
    {
        return $this->hasOne(BranchBrand::class);
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

    /**
     * get user
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'brand_users')->latest();
    }

    /**
     * member
     * @return HasMany
     */
    public function member(): HasMany
    {
        return $this->hasMany(Member::class)->latest();
    }
}
