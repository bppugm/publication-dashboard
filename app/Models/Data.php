<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function dashboards()
    {
        return $this->belongsToMany(\App\Models\Dashboard::class);
    }
}
