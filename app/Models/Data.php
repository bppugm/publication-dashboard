<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Data extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = ['id'];
    protected static $logAttributes = ['name', 'description', 'value', 'notes'];
    protected static $logOnlyDirty = true;

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, fn ($query, $search) =>
            $query->where(fn ($query) =>
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
            )
        );
    }

    public function dashboards()
    {
        return $this->belongsToMany(\App\Models\Dashboard::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
