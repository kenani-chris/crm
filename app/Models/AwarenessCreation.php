<?php

namespace App\Models;

use App\Traits\Uuids;
use App\Models\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AwarenessCreation extends Model
{
    use  Uuids;

    // stop autoincrement
    public $incrementing = false;
    
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'aware',
       'satisfaction',
       'comment',
       'member_id',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
