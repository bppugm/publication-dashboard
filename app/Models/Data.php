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

        $query->when($filters['category'] ?? false, function ($query) use ($filters) {
            $categories = $filters['category'];
            foreach ($categories as $category) {
                $query->whereHas('categories', function ($query) use ($category) {
                    $query->where('categories.name', $category);
                });
            }
        });

        // $query->when(
        //     $filters['category'] ?? false,
        //     fn ($query, $category) =>
        //     $query->whereHas('categories', function ($query) use ($category) {
        //         $query->where(function ($queryc) use ($category) {
        //             dump($category);
        //             foreach ($category as $key => $value) {
        //                 $queryc->where('categories.name', $value);
        //             }
        //         });
        //     })
        // );
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
