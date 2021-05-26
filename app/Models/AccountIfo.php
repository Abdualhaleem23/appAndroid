<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountIfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'set_get',
        'money',
    ];
   /* public function getJWTIdentifier() {
        return $this->getKey();
    }
    public function getJWTCustomClaims() {
        return [];
    }*/
}
