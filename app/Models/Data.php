<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

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
    }
    public function dashboards()
    {
        return $this->belongsToMany(\App\Models\Dashboard::class);
    }

    public function categories()
    {
        return $this->belongsToMany(\App\Models\Category::class, 'category_data');
    }
}
