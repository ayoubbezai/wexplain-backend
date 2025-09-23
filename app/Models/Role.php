<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;
    //
    protected $fillable = ["name"];

    // link the role with the user

    public function user(){
        return $this->hasOne(User::class);
    }
}
