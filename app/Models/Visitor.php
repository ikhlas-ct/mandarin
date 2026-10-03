<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $table = 'visitors';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ip_address',
        'user_agent',
    ];
}
