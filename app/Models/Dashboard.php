<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'widgets' => 'array'
    ];

    public function data()
    {
        return $this->belongsToMany(\App\Models\Data::class);
    }
}
