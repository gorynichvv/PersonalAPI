<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $table = 'users_block';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'cause',
        'admin_name',
        'date',
        'auto_unblock',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
