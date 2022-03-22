<?php

namespace App\Models;

use App\Events\GlobalEvent;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disposition extends Model
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
        'disposition_type_id',
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
     * disposition types
     * @return BelongsTo
     */
    public function disposition_type(): BelongsTo
    {
        return $this->belongsTo(DispositionType::class);
    }

    /**
     * members
     * @return HasMany
     */
    public function member(): HasMany
    {
        return $this->hasMany(Member::class)->latest();
    }
}
