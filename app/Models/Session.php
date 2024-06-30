<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'acc_ses2';
    public $timestamps = false;

    protected $fillable = [
        'drop_ses',
    ];
}
