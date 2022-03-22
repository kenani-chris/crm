<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
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
        'user_id',
        'member_id',
        'scheduled_at',
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
     * member
     * @return BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
