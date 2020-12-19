<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;
    protected $hidden = ['updated_at','created_at'];

    public function soldier(){
        return $this->belongsToMany(Soldier::class, 'soldier_missions');
    }

    public function teamOne(){
        
        return $this->hasOne(Team::class);
    }
}
