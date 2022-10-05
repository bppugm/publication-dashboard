<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Data extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = ['id'];
    protected static $logAttributes = ['name', 'description', 'value', 'notes', 'user.name', 'user.id'];
    protected static $logOnlyDirty = true;

    public function scopeFilter($query, array $filters)
    {

        $query->when(
            $filters['search'] ?? false,
            fn ($query, $search) =>
            $query->where(
                fn ($query) =>
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
            )
        );

        $query->when($filters['categories'] ?? false, function ($query) use ($filters) {
            $categories = $filters['categories'];
            foreach ($categories as $category) {
                $query->whereHas('categories', function ($query) use ($category) {
                    $query->where('categories.name', $category);
                });
            }
        });

        $query->when($filters['user'] ?? false, function ($query) use ($filters) {
            $query->whereHas('user', function ($query) use ($filters) {
                $query->where('user_id', $filters['user']);
            });
        });
    }

    public function dashboards()
    {
        return $this->belongsToMany(\App\Models\Dashboard::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(\App\Models\Category::class, 'category_data');
    }
}
