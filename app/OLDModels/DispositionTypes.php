<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class DispositionTypes extends Model
{
    use Uuids;
    
    protected $fillable=[
        'title',
        'isactive'
    ];
    

    public function disposition(): HasMany
    {
        return $this->hasMany(Disposition::class)->oldest('created_at');
    }

}
