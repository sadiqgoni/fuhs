<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'country', 'status'];

    public function localGovts()
    {
        return $this->hasMany(LocalGovt::class, 'state_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
