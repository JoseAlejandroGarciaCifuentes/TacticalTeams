<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    public function soldier(){

        return $this->hasMany(Soldier::class);
    }

    public function soldierOneToOne(){
        return $this->hasOne(Soldier::class);
    }

    public function mission(){
        return $this->hasOne(Mission::class);
    }
}
