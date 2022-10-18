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
    }

    public function scopeActive($query)
    {
        return $query->where('order', '!=', 0);
    }

    public function data()
    {
        return $this->belongsToMany(\App\Models\Data::class);
    }

    /**
     * Extract data IDs from widgets
     *
     * @return array
     */
    public function extractedDataIds()
    {
        $dataIds = [];

        if ($this->widgets == null) {
            return $dataIds;
        }

        foreach ($this->widgets as $widget) {
            // Extract data IDs from numeric widgets
            if (optional($widget)['type']  == 'numeric') {
                if (is_array($widget['values'])) {
                    foreach ($widget['values'] as $data) {
                        if ($data['type'] == 'data') {
                            $dataIds[] = (int)$data['text'];
                        } elseif ($data['type'] == 'expression') {
                            foreach ($data['variables'] as $variable) {
                                $dataIds[] = $variable['data_id'];
                            }
                        }
                    }
                }
            }
        }

        return $dataIds;
    }
}
