<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Uuids;


class Disposition extends Model
{

    use Uuids;
    
    protected $fillable=[
        'title',
        'slug',
        'disposition_type_id',
       'isactive'
    ];

    public function disposition_type(): BelongsTo
    {
        return $this->belongsTo(DispositionTypes::class);
    }

}
