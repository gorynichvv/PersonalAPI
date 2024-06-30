<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    use HasFactory;

    protected $table = 'tarifs';
    public $timestamps = false;

    public function user()
    {
        return $this->hasMany(User::class, 'tarif_id', 'id');
    }
}
