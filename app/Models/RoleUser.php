<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $fillable = [
        'role_id', 'user_id'
    ];

    protected $table = 'role_user';

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
