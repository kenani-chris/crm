<?php

namespace App\Models;


use App\Events\GlobalEvent;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable
{
    use Notifiable, Uuids,SoftDeletes;

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
        'branch_id',
        'campaign_id',
        'role_id',
        'name',
        'slug',
        'email',
        'phone_number',
        'pf_no',
        'brand',
        'cases',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'brand'
    ];



    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'brand' => 'array',
        'email_verified_at' => 'datetime',
    ];

    /**
     * set the column username to be
     * the default username when authorizing
     */
    public function username()
    {
        return 'username'; //or return the field which you want to use.
    }



    /**
     * role
     * @return BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

     /**
     * member
     * @return HasMany
     */
    public function member()
    {
        return $this->hasMany(Member::class)
            ->where('attempts', '<', env('MAX_CALL_ATTEMPTS'))
            ->latest();
    }
  

    /**
     * get branch
     * @return BelongsTo
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

      /**
     * get campaign
     * @return BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * get team
     * @return HasOne
     */
    public function team()
    {
        return $this->hasOne(Team::class);
    }

    /**
     * get team
     * @return HasOne
     */
    public function team_user()
    {
        return $this->hasOne(TeamUser::class);
    }
    
    /**
     * get brand
     * @return BelongsToMany
     */
    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brand_users')->latest();
    }


     /**
     * schedule
     * @return HasMany
     */
    public function schedule()
    {
        return $this->hasMany(Schedule::class)
            ->orWhereDate('scheduled_at', '>=', today())
            ->oldest();
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
     * toyota_case
     * @return HasMany
     */
    public function toyota_case()
    {
        return $this->hasMany(ToyotaCase::class)->latest();
    }
    
/**
     * escalate
     * @return HasMany
     */
    public function escalate()
    {
        return $this->hasMany(Escalate::class)->latest();
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
