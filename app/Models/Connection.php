<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;

    protected $table = 'stat';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
