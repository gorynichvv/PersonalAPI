<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'prepay_day_traf',
        'exp_date',
        'activate_day',
        'blocking_id',
        'mac',
        'mac2',
        'mac3',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function street()
    {
        return $this->hasOne(Street::class, 'id', 'street_id');
    }

    public function tariff()
    {
        return $this->hasOne(Tariff::class, 'id', 'tarif_id');
    }

    public function connections()
    {
        return $this->hasMany(Connection::class);
    }

    public function session()
    {
        return $this->hasOne(Session::class);
    }

    public function block()
    {
        return $this->hasMany(Block::class);
    }

    public function servicelog()
    {
        return $this->hasMany(ServiceLog::class);
    }
}
