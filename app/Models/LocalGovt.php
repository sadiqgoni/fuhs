<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalGovt extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'state_id', 'status'];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
